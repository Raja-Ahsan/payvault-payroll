<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\State;
use Illuminate\View\View;

class FormController extends Controller
{
    public function index(): View
    {
        return view('screens.admin.forms.index');
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
        ]);
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

    /**
     * @return array{ein: string, legal_name: string, trade_name: string, address_line1: string, city: string, state_code: string, zip_code: string}
     */
    private function employerFormPayload(): array
    {
        $scoped = fn () => Company::query()
            ->with(['address', 'federalTaxInformation'])
            ->when(! userHasRole('admin'), fn ($q) => $q->where('user_id', auth()->id()));

        $company = $scoped()
            ->whereHas('federalTaxInformation')
            ->orderBy('company_name')
            ->first()
            ?? $scoped()->orderBy('company_name')->first();

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
}
