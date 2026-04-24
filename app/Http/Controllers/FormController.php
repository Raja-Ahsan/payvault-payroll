<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\State;
use App\Models\StateReportingTaxType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class FormController extends Controller
{
    public function index(): View
    {
        return view('screens.admin.forms.index');
    }

    public function stateTaxReporting(): View
    {
        abort_if(userHasRole('employee'), 403);

        $states = State::query()->orderBy('name')->get();
        $wizardTableEmployees = $this->employeesForStateTaxReportingStep();
        $stateReportingEmployeeCounts = $this->stateReportingEmployeeCountsByStateCode();
        $amountsForYear = (int) now()->format('Y');

        $stateReportingConfig = StateReportingTaxType::query()
            ->where('is_active', true)
            ->with(['methodOptions' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('state_code')
            ->orderBy('sort_order')
            ->get()
            ->map(static function (StateReportingTaxType $t) {
                return [
                    'id' => $t->id,
                    'state_code' => $t->state_code,
                    'slug' => $t->slug,
                    'label' => $t->label,
                    'methods' => $t->methodOptions->map(static function ($m) {
                        return [
                            'id' => $m->id,
                            'slug' => $m->slug,
                            'label' => $m->label,
                            'description' => $m->description,
                            'link_text' => $m->link_text,
                            'flow_kind' => $m->flow_kind,
                            'meta' => $m->meta,
                        ];
                    })->values()->all(),
                ];
            })->values()->all();

        return view('screens.admin.forms.state-tax-reporting', [
            'states' => $states,
            'wizardTableEmployees' => $wizardTableEmployees,
            'stateReportingEmployeeCounts' => $stateReportingEmployeeCounts,
            'amountsForYear' => $amountsForYear,
            'stateReportingConfig' => $stateReportingConfig,
        ]);
    }

    /**
     * Uppercase state code => count of in-scope, active employees tied to that state
     * (address on file or state withholding on employee details).
     *
     * @return array<string, int>
     */
    private function stateReportingEmployeeCountsByStateCode(): array
    {
        $out = [];
        $states = State::query()->orderBy('name')->get();
        foreach ($states as $state) {
            $id = (int) $state->id;
            $base = $this->baseQueryEmployeesForStateReporting();
            $n = $base
                ->where(function ($w) use ($id) {
                    $w->where('state_id', $id)
                        ->orWhereHas('detail', function ($d) use ($id) {
                            $d->where('withholding_state_id', $id);
                        });
                })
                ->count();
            $out[strtoupper((string) $state->code)] = $n;
        }

        return $out;
    }

    private function baseQueryEmployeesForStateReporting(): Builder
    {
        return Employee::query()
            ->where('inactive', false)
            ->when(! userHasRole('admin'), function ($query) {
                $query->whereHas('company', fn ($q) => $q->where('user_id', auth()->id()));
            });
    }

    /**
     * @return list<array{
     *     id: int,
     *     full_name: string,
     *     ssn: string,
     *     first_name: string,
     *     middle_initial: string,
     *     last_name: string,
     *     phone: string,
     *     email: string,
     *     include_in_state_reporting: bool
     * }>
     */
    private function employeesForStateTaxReportingStep(): array
    {
        $employees = Employee::query()
            ->with('company')
            ->where('inactive', false)
            ->when(! userHasRole('admin'), function ($query) {
                $query->whereHas('company', fn ($q) => $q->where('user_id', auth()->id()));
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $rows = $employees->map(function (Employee $e) {
            $fn = trim((string) ($e->first_name ?? ''));
            $ln = trim((string) ($e->last_name ?? ''));
            $middleRaw = trim((string) ($e->middle_name ?? ''));
            $mi = $middleRaw !== '' ? mb_strtoupper(mb_substr($middleRaw, 0, 1)) : '';
            $nameParts = array_filter([$fn, $mi, $ln], static fn (string $p) => $p !== '');
            $full = $nameParts !== [] ? implode(' ', $nameParts) : 'Employee #'.$e->id;

            return [
                'id' => (int) $e->id,
                'full_name' => $full,
                'ssn' => (string) ($e->ssn !== null && $e->ssn !== '' ? $e->ssn : '—'),
                'first_name' => $fn,
                'middle_initial' => $mi,
                'last_name' => $ln,
                'phone' => (string) ($e->phone ?? ''),
                'email' => (string) ($e->email ?? ''),
                'include_in_state_reporting' => true,
            ];
        })->values()->all();

        if ($rows === []) {
            return [
                [
                    'id' => 0,
                    'full_name' => 'George B Orange',
                    'ssn' => '123-45-6788',
                    'first_name' => 'George',
                    'middle_initial' => 'B',
                    'last_name' => 'Orange',
                    'phone' => '',
                    'email' => '',
                    'include_in_state_reporting' => true,
                ],
            ];
        }

        return $rows;
    }

    public function w2(): View
    {
        abort_if(userHasRole('employee'), 403);

        $employees = Employee::query()
            ->with(['company.address', 'company.federalTaxInformation'])
            ->when(! userHasRole('admin'), function ($query) {
                $query->whereHas('company', fn ($q) => $q->where('user_id', auth()->id()));
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $wizardEmployees = $employees->map(function (Employee $e) {
            $addr = $e->company?->address;
            $fti = $e->company?->federalTaxInformation;
            $cityLine = collect([$addr?->city, trim(implode(' ', array_filter([$addr?->state ?? '', $addr?->zip_code ?? ''])))])
                ->filter()
                ->implode(', ');

            return [
                'id' => $e->id,
                'label' => trim(($e->last_name ?: '').', '.($e->first_name ?: '')).' [SSN: '.($e->ssn ?: '---').']',
                'first_name' => (string) ($e->first_name ?? ''),
                'last_name' => (string) ($e->last_name ?? ''),
                'ssn' => (string) ($e->ssn ?? ''),
                'company' => [
                    'name' => $e->company?->company_name ?? 'Sample Company',
                    'ein' => $fti?->employer_identification_number ?? '12-3456789',
                    'line1' => $addr?->address_1 ?? '1515 South Apple Drive',
                    'cityStateZip' => $cityLine !== '' ? $cityLine : 'Anytown, IL 60827',
                    'contact' => $e->company?->contact_name ?? 'Mr. Peach',
                    'phone' => $e->company?->tel_number ?? '123 455-6789',
                    'email' => $e->company?->email ?? 'Peach@fruitland.com',
                ],
            ];
        })->values()->all();

        if ($wizardEmployees === []) {
            $wizardEmployees = [
                [
                    'id' => 0,
                    'label' => 'Orange, George [SSN: 123-45-6788]',
                    'first_name' => 'George',
                    'last_name' => 'Orange',
                    'ssn' => '123-45-6788',
                    'company' => [
                        'name' => 'Sample Company',
                        'ein' => '12-3456789',
                        'line1' => '1515 South Apple Drive',
                        'cityStateZip' => 'Anytown, IL 60827',
                        'contact' => 'Mr. Peach',
                        'phone' => '123 455-6789',
                        'email' => 'Peach@fruitland.com',
                    ],
                ],
            ];
        }

        return view('screens.admin.forms.w2', [
            'wizardEmployees' => $wizardEmployees,
            'w2TaxYear' => (int) now()->format('Y'),
        ]);
    }

    public function w2Pdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $snap = $request->input('snapshot');
        if (! is_array($snap)) {
            $snap = [];
        }
        $taxYear = max(2000, min(2100, (int) ($snap['taxYear'] ?? now()->format('Y'))));
        $boxes = is_array($snap['boxes'] ?? null) ? $snap['boxes'] : [];
        $employee = is_array($snap['employee'] ?? null) ? $snap['employee'] : [];
        $company = is_array($snap['company'] ?? null) ? $snap['company'] : [];
        $empId = (string) ($snap['employeeId'] ?? '');

        return Pdf::loadView('pdf.form-w2', [
            'taxYear' => $taxYear,
            'boxes' => $boxes,
            'employee' => $employee,
            'company' => $company,
        ])
            ->setPaper('letter', 'portrait')
            ->download('Form-W-2-'.$taxYear.'-'.$empId.'.pdf');
    }

    public function w3Pdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $snap = $request->input('snapshot');
        if (! is_array($snap)) {
            $snap = [];
        }
        $taxYear = max(2000, min(2100, (int) ($snap['taxYear'] ?? now()->format('Y'))));
        $totals = is_array($snap['totals'] ?? null) ? $snap['totals'] : [];
        $company = is_array($snap['company'] ?? null) ? $snap['company'] : [];
        $employeeCount = max(0, (int) ($snap['employeeCount'] ?? 0));

        return Pdf::loadView('pdf.form-w3', [
            'taxYear' => $taxYear,
            'totals' => $totals,
            'company' => $company,
            'employeeCount' => $employeeCount,
        ])
            ->setPaper('letter', 'portrait')
            ->download('Form-W-3-'.$taxYear.'.pdf');
    }

    public function form940(): View
    {
        abort_if(userHasRole('employee'), 403);

        return view('screens.admin.forms.form-940', [
            'taxYear' => (int) now()->format('Y'),
            'employer940' => $this->employerFormPayload(),
        ]);
    }

    public function form944(): View
    {
        abort_if(userHasRole('employee'), 403);

        return view('screens.admin.forms.form-944', [
            'taxYear' => (int) now()->format('Y'),
            'employer944' => $this->employerFormPayload(),
        ]);
    }

    public function form941(): View
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();

        return view('screens.admin.forms.form-941', [
            'taxYear' => (int) now()->format('Y'),
            'employer941' => $this->employerPayloadFromCompany($company),
            'form941Metrics' => $this->form941PayrollSnapshot($company),
        ]);
    }

    public function form941X(): View
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $ty = (int) now()->format('Y');
        $m = $this->form941PayrollSnapshot($company);
        $cq = max(1, min(4, (int) ($m['current_quarter'] ?? 1)));

        return view('screens.admin.forms.form-941-x', [
            'taxYear' => $ty,
            'currentQuarter' => $cq,
            'employer941x' => $this->employerPayloadFromCompany($company),
        ]);
    }

    public function form941XPdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $snap = $request->input('snapshot');
        $fields = is_array($snap) && isset($snap['fields']) && is_array($snap['fields']) ? $snap['fields'] : [];
        $checks = is_array($snap) && isset($snap['checks']) && is_array($snap['checks']) ? $snap['checks'] : [];
        $ty = (int) now()->format('Y');
        $cq = max(1, min(4, (int) ($this->form941PayrollSnapshot($company)['current_quarter'] ?? 1)));
        for ($i = 1; $i <= 4; $i++) {
            if (! empty($checks['f941x-cq-'.$i])) {
                $cq = $i;
                break;
            }
        }
        $correctYear = isset($fields['f941x-year-correct']) && preg_match('/^\d{4}$/', (string) $fields['f941x-year-correct'])
            ? (int) $fields['f941x-year-correct']
            : $ty;

        return Pdf::loadView('pdf.form-941-x', [
            'taxYear' => $ty,
            'correctingYear' => $correctYear,
            'currentQuarter' => $cq,
            'emp' => $this->employerPayloadFromCompany($company),
            'fields' => $fields,
            'checks' => $checks,
        ])
            ->setPaper('letter', 'portrait')
            ->download('Form-941-X-'.$correctYear.'-Q'.$cq.'.pdf');
    }

    /**
     * @return array{ein: string, legal_name: string, trade_name: string, address_line1: string, city: string, state_code: string, zip_code: string}
     */
    private function employerFormPayload(): array
    {
        return $this->employerPayloadFromCompany($this->resolveEmployerCompany());
    }

    /**
     * @return array{ein: string, legal_name: string, trade_name: string, address_line1: string, city: string, state_code: string, zip_code: string}
     */
    private function employerPayloadFromCompany(?Company $company): array
    {
        $addr = $company?->address;
        $fti = $company?->federalTaxInformation;

        $stateRaw = trim((string) ($addr?->state ?? ''));
        $stateCode = '';
        if ($stateRaw !== '') {
            if (strlen($stateRaw) === 2 && ctype_alpha($stateRaw)) {
                $stateCode = strtoupper($stateRaw);
            } else {
                $stateCode = State::query()->where('name', $stateRaw)->value('code')
                    ?? State::query()->where('code', strtoupper(substr($stateRaw, 0, 2)))->value('code')
                    ?? '';
            }
        }

        $line1 = trim((string) ($addr?->address_1 ?? ''));
        $line2 = trim((string) ($addr?->address_2 ?? ''));
        $addressLine1 = $line2 !== '' ? trim($line1.' '.$line2) : $line1;

        return [
            'ein' => $fti?->employer_identification_number ?? '',
            'legal_name' => $company?->company_name ?? '',
            'trade_name' => $fti?->trade_name ?? '',
            'address_line1' => $addressLine1,
            'city' => $addr?->city ?? '',
            'state_code' => $stateCode,
            'zip_code' => $addr?->zip_code ?? '',
        ];
    }

    private function resolveEmployerCompany(): ?Company
    {
        $uid = auth()->id();

        $company = Company::query()
            ->with(['address', 'federalTaxInformation'])
            ->where('user_id', $uid)
            ->whereHas('federalTaxInformation')
            ->orderBy('company_name')
            ->first()
            ?? Company::query()
                ->with(['address', 'federalTaxInformation'])
                ->where('user_id', $uid)
                ->orderBy('company_name')
                ->first();

        if (! $company && userHasRole('admin')) {
            $company = Company::query()
                ->with(['address', 'federalTaxInformation'])
                ->whereHas('federalTaxInformation')
                ->orderBy('company_name')
                ->first()
                ?? Company::query()
                    ->with(['address', 'federalTaxInformation'])
                    ->orderBy('company_name')
                    ->first();
        }

        return $company;
    }

    /**
     * Form 941 figures from active employees and configured income categories.
     * Per-check payroll is not stored yet: line 3 (FIT withheld) and line 13 (deposits) stay 0; SS/Medicare use IRS combined rates on taxable wages from employee income setup.
     *
     * @return array<string, float|int|bool>
     */
    private function form941PayrollSnapshot(?Company $company): array
    {
        $defaults = [
            'line16_semiweekly' => false,
            'line1' => 0,
            'line2' => 0.0,
            'line3' => 0.0,
            'line4_no_ss_medicare' => false,
            'line5a_c1' => 0.0,
            'line5a_c2' => 0.0,
            'line5b_c1' => 0.0,
            'line5b_c2' => 0.0,
            'line5c_c1' => 0.0,
            'line5c_c2' => 0.0,
            'line5d_c1' => 0.0,
            'line5d_c2' => 0.0,
            'line5e' => 0.0,
            'line5f' => 0.0,
            'line6' => 0.0,
            'line7' => 0.0,
            'line8' => 0.0,
            'line9' => 0.0,
            'line10' => 0.0,
            'line11' => 0.0,
            'line12' => 0.0,
            'line13' => 0.0,
            'line14' => 0.0,
            'line15a' => 0.0,
            'current_quarter' => max(1, min(4, (int) ceil(now()->month / 3))),
            'line12_under_2500' => true,
        ];

        if (! $company) {
            return $defaults;
        }

        $employees = Employee::query()
            ->where('company_id', $company->id)
            ->where('inactive', false)
            ->with(['detail', 'incomeCategories.incomeCategory'])
            ->get();

        /** @var float SS wage base (combined wages + tips subject to SS); update when IRS publishes annual figure. */
        $ssAnnualWageBase = 176100.00;

        $line2 = 0.0;
        $line5a_c1 = 0.0;
        $line5b_c1 = 0.0;
        $line5c_c1 = 0.0;
        $line1 = 0;

        foreach ($employees as $e) {
            $totalIncluded = 0.0;
            $tipsIncluded = 0.0;

            foreach ($e->incomeCategories as $eic) {
                $cat = $eic->incomeCategory;
                if (! $cat || $cat->omit_net_pay) {
                    continue;
                }
                $amt = (float) $eic->amount;
                $totalIncluded += $amt;
                if ($cat->reported_tips) {
                    $tipsIncluded += $amt;
                }
            }

            if ($totalIncluded > 0.00001) {
                $line1++;
            }

            $line2 += $totalIncluded;

            $zeroSsMed = (bool) ($e->detail?->tax_zero_ss_med_employee);
            if (! $zeroSsMed) {
                $wagePortion = max(0.0, $totalIncluded - $tipsIncluded);
                $ssCombined = $wagePortion + $tipsIncluded;
                if ($ssCombined > $ssAnnualWageBase && $ssCombined > 0.00001) {
                    $scale = $ssAnnualWageBase / $ssCombined;
                    $wagePortion *= $scale;
                    $tipsIncluded *= $scale;
                }
                $line5a_c1 += $wagePortion;
                $line5b_c1 += $tipsIncluded;
                $line5c_c1 += $totalIncluded;
            }
        }

        $line5a_c2 = round($line5a_c1 * 0.124, 2);
        $line5b_c2 = round($line5b_c1 * 0.124, 2);
        $line5c_c2 = round($line5c_c1 * 0.029, 2);
        $line5d_c1 = 0.0;
        $line5d_c2 = 0.0;
        $line5e = round($line5a_c2 + $line5b_c2 + $line5c_c2 + $line5d_c2, 2);
        $line5f = 0.0;

        $line3 = 0.0;

        $line6 = round($line3 + $line5e + $line5f, 2);
        $line7 = 0.0;
        $line8 = 0.0;
        $line9 = 0.0;
        $line10 = round($line6 + $line7 + $line8 + $line9, 2);
        $line11 = 0.0;
        $line12 = round($line10 - $line11, 2);
        $line13 = 0.0;
        $line14 = round(max(0, $line12 - $line13), 2);
        $line15a = round(max(0, $line13 - $line12), 2);

        $line4_no_ss_medicare = $line2 > 0.00001
            && ($line5a_c1 + $line5b_c1 + $line5c_c1) < 0.00001;

        return [
            'line16_semiweekly' => false,
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'line4_no_ss_medicare' => $line4_no_ss_medicare,
            'line5a_c1' => $line5a_c1,
            'line5a_c2' => $line5a_c2,
            'line5b_c1' => $line5b_c1,
            'line5b_c2' => $line5b_c2,
            'line5c_c1' => $line5c_c1,
            'line5c_c2' => $line5c_c2,
            'line5d_c1' => $line5d_c1,
            'line5d_c2' => $line5d_c2,
            'line5e' => $line5e,
            'line5f' => $line5f,
            'line6' => $line6,
            'line7' => $line7,
            'line8' => $line8,
            'line9' => $line9,
            'line10' => $line10,
            'line11' => $line11,
            'line12' => $line12,
            'line13' => $line13,
            'line14' => $line14,
            'line15a' => $line15a,
            'current_quarter' => max(1, min(4, (int) ceil(now()->month / 3))),
            'line12_under_2500' => $line12 < 2500.00001,
        ];
    }

    public function form941Pdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $employer = $this->employerPayloadFromCompany($company);
        $metrics = $this->form941PayrollSnapshot($company);

        $snap = $request->input('snapshot');
        if (is_array($snap)) {
            $metrics = $this->mergeForm941FromClientSnapshot($metrics, $snap);
        }

        $fields = is_array($snap) && isset($snap['fields']) && is_array($snap['fields'])
            ? $snap['fields']
            : [];

        $months = [
            'm1' => isset($fields['f941-m1']) ? (string) $fields['f941-m1'] : '',
            'm2' => isset($fields['f941-m2']) ? (string) $fields['f941-m2'] : '',
            'm3' => isset($fields['f941-m3']) ? (string) $fields['f941-m3'] : '',
            'mtot' => isset($fields['f941-mtot']) ? (string) $fields['f941-mtot'] : '',
        ];

        $ty = (int) now()->format('Y');
        $cq = max(1, min(4, (int) ($metrics['current_quarter'] ?? 1)));
        $filename = 'Form-941-'.$ty.'-Q'.$cq.'.pdf';

        $checks = is_array($snap) && isset($snap['checks']) && is_array($snap['checks'])
            ? $snap['checks']
            : [];

        return Pdf::loadView('pdf.form-941', [
            'ty' => $ty,
            'emp' => $employer,
            'm' => $metrics,
            'months' => $months,
            'checks' => $checks,
            'formFields' => $fields,
        ])
            ->setPaper('letter', 'portrait')
            ->download($filename);
    }

    private function parseMoneyInput(mixed $raw): float
    {
        if ($raw === null || $raw === '') {
            return 0.0;
        }
        $s = preg_replace('/[^\d.-]/', '', (string) $raw);
        if ($s === '' || $s === '-' || $s === '.') {
            return 0.0;
        }

        return round((float) $s, 2);
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array{fields?: array<string, string>, checks?: array<string, bool>}  $snap
     * @return array<string, mixed>
     */
    private function mergeForm941FromClientSnapshot(array $base, array $snap): array
    {
        $fields = isset($snap['fields']) && is_array($snap['fields']) ? $snap['fields'] : [];
        $checks = isset($snap['checks']) && is_array($snap['checks']) ? $snap['checks'] : [];

        $moneyMap = [
            'f941-l2' => 'line2',
            'f941-l3' => 'line3',
            'f941-l5a1' => 'line5a_c1',
            'f941-l5a2' => 'line5a_c2',
            'f941-l5b1' => 'line5b_c1',
            'f941-l5b2' => 'line5b_c2',
            'f941-l5c1' => 'line5c_c1',
            'f941-l5c2' => 'line5c_c2',
            'f941-l5d1' => 'line5d_c1',
            'f941-l5d2' => 'line5d_c2',
            'f941-l5e' => 'line5e',
            'f941-l5f' => 'line5f',
            'f941-l6' => 'line6',
            'f941-l7' => 'line7',
            'f941-l8' => 'line8',
            'f941-l9' => 'line9',
            'f941-l10' => 'line10',
            'f941-l11' => 'line11',
            'f941-l12' => 'line12',
            'f941-l13' => 'line13',
            'f941-l14' => 'line14',
            'f941-l15a' => 'line15a',
        ];

        foreach ($moneyMap as $fieldId => $key) {
            if (array_key_exists($fieldId, $fields)) {
                $base[$key] = $this->parseMoneyInput($fields[$fieldId]);
            }
        }

        if (array_key_exists('f941-l1', $fields)) {
            $digits = preg_replace('/\D/', '', (string) $fields['f941-l1']);
            $base['line1'] = max(0, min(9_999_999, (int) ($digits === '' ? 0 : $digits)));
        }

        if (array_key_exists('f941-l4', $checks)) {
            $base['line4_no_ss_medicare'] = (bool) $checks['f941-l4'];
        }

        for ($q = 1; $q <= 4; $q++) {
            if (! empty($checks['f941-q'.$q])) {
                $base['current_quarter'] = $q;
                break;
            }
        }

        $base['line16_semiweekly'] = ! empty($checks['f941-l16c']);
        if ($base['line16_semiweekly']) {
            $base['line12_under_2500'] = false;
        } elseif (! empty($checks['f941-l16a'])) {
            $base['line12_under_2500'] = true;
        } elseif (! empty($checks['f941-l16b'])) {
            $base['line12_under_2500'] = false;
        }

        return $base;
    }

    public function form941ScheduleB(): View
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $ty = (int) now()->format('Y');
        $m = $this->form941PayrollSnapshot($company);
        $cq = max(1, min(4, (int) ($m['current_quarter'] ?? 1)));
        $days = $this->scheduleBQuarterDays($ty, $cq);

        return view('screens.admin.forms.form-941-schedule-b', [
            'taxYear' => $ty,
            'currentQuarter' => $cq,
            'employer941' => $this->employerPayloadFromCompany($company),
            'scheduleBDaysGrouped' => collect($days)->groupBy('m'),
        ]);
    }

    public function form941ScheduleR(): View
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $ty = (int) now()->format('Y');
        $m = $this->form941PayrollSnapshot($company);
        $cq = max(1, min(4, (int) ($m['current_quarter'] ?? 1)));

        return view('screens.admin.forms.form-941-schedule-r', [
            'taxYear' => $ty,
            'currentQuarter' => $cq,
            'employer941' => $this->employerPayloadFromCompany($company),
        ]);
    }

    public function form941ScheduleBPdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $ty = (int) now()->format('Y');
        $m = $this->form941PayrollSnapshot($company);
        $cq = max(1, min(4, (int) ($m['current_quarter'] ?? 1)));
        $days = $this->scheduleBQuarterDays($ty, $cq);
        $snap = $request->input('snapshot');
        $fields = is_array($snap) && isset($snap['fields']) && is_array($snap['fields']) ? $snap['fields'] : [];
        $checks = is_array($snap) && isset($snap['checks']) && is_array($snap['checks']) ? $snap['checks'] : [];

        return Pdf::loadView('pdf.form-941-schedule-b', [
            'taxYear' => $ty,
            'currentQuarter' => $cq,
            'emp' => $this->employerPayloadFromCompany($company),
            'scheduleBDaysGrouped' => collect($days)->groupBy('m'),
            'fields' => $fields,
            'checks' => $checks,
        ])
            ->setPaper('letter', 'portrait')
            ->download('Schedule-B-Form-941-'.$ty.'-Q'.$cq.'.pdf');
    }

    public function form941ScheduleRPdf(Request $request): Response
    {
        abort_if(userHasRole('employee'), 403);

        $company = $this->resolveEmployerCompany();
        $ty = (int) now()->format('Y');
        $m = $this->form941PayrollSnapshot($company);
        $cq = max(1, min(4, (int) ($m['current_quarter'] ?? 1)));
        $snap = $request->input('snapshot');
        $fields = is_array($snap) && isset($snap['fields']) && is_array($snap['fields']) ? $snap['fields'] : [];
        $checks = is_array($snap) && isset($snap['checks']) && is_array($snap['checks']) ? $snap['checks'] : [];

        return Pdf::loadView('pdf.form-941-schedule-r', [
            'taxYear' => $ty,
            'currentQuarter' => $cq,
            'emp' => $this->employerPayloadFromCompany($company),
            'fields' => $fields,
            'checks' => $checks,
        ])
            ->setPaper('letter', 'portrait')
            ->download('Schedule-R-Form-941-'.$ty.'-Q'.$cq.'.pdf');
    }

    /**
     * @return list<array{id: string, m: int, d: int, label: string}>
     */
    private function scheduleBQuarterDays(int $year, int $quarter): array
    {
        $quarter = max(1, min(4, $quarter));
        $start = match ($quarter) {
            1 => Carbon::create($year, 1, 1)->startOfDay(),
            2 => Carbon::create($year, 4, 1)->startOfDay(),
            3 => Carbon::create($year, 7, 1)->startOfDay(),
            default => Carbon::create($year, 10, 1)->startOfDay(),
        };
        $end = match ($quarter) {
            1 => Carbon::create($year, 3, 31)->endOfDay(),
            2 => Carbon::create($year, 6, 30)->endOfDay(),
            3 => Carbon::create($year, 9, 30)->endOfDay(),
            default => Carbon::create($year, 12, 31)->endOfDay(),
        };
        $out = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $out[] = [
                'id' => $d->format('Y-m-d'),
                'm' => (int) $d->format('n'),
                'd' => (int) $d->format('j'),
                'label' => $d->format('n/j/Y'),
            ];
        }

        return $out;
    }
}
