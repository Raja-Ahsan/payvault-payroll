<?php

namespace App\Services\Payroll;

use App\Models\Employee;
use App\Models\PayrollCheck;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class PayrollCheckCalculator
{
    public const INCOME_SLOTS = ['regular_hourly', 'overtime_hourly'];

    /** @var list<string> */
    public const LEAVE_KEYS = ['vacation_hours_earned', 'vacation_hours_used', 'sick_hours_earned', 'sick_hours_used'];

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function calculate(array $payload, ?int $excludeCheckId = null): array
    {
        $employee = Employee::query()->with('detail')->findOrFail((int) $payload['employee_id']);
        $payDate = Carbon::parse((string) $payload['pay_date']);
        $year = (int) $payDate->year;
        $payFrequency = (string) ($payload['pay_frequency'] ?? 'weekly_52');

        $incomeBreakdown = $this->computeIncomeLines((array) ($payload['income'] ?? []), $payFrequency);
        $grossTotal = round(array_sum(array_map(static fn (array $r) => (float) ($r['amount'] ?? 0), $incomeBreakdown)), 2);

        $prior = $this->priorTotals((int) $employee->id, $year, $excludeCheckId, $payDate);

        $detail = $employee->detail;
        $ssBase = (float) config('payroll.social_security_wage_base');
        $ssRemaining = max(0.0, $ssBase - $prior['ss_taxable_cumulative']);
        $ssTaxableThis = round(min($grossTotal, $ssRemaining), 2);
        $ssRate = (float) config('payroll.social_security_employee_rate');
        $medicareRate = (float) config('payroll.medicare_employee_rate');

        $employeeSsComputed = round($ssTaxableThis * $ssRate, 2);
        $employeeMedComputed = round($grossTotal * $medicareRate, 2);
        $employeeSs = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'social_security', $employeeSsComputed);
        $employeeMed = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'medicare', $employeeMedComputed);
        $employerSs = $this->taxAmountFromPayloadOrComputed($payload, 'employer', 'social_security', $employeeSs);
        $employerMed = $this->taxAmountFromPayloadOrComputed($payload, 'employer', 'medicare', $employeeMed);

        $futaBase = (float) config('payroll.federal_unemployment_wage_base');
        $futaRemaining = max(0.0, $futaBase - $prior['futa_taxable_cumulative']);
        $futaTaxableThis = round(min($grossTotal, $futaRemaining), 2);
        $employerFutaComputed = round($futaTaxableThis * (float) config('payroll.employer_federal_unemployment_rate'), 2);

        $sutaTaxableThis = $grossTotal;
        $employerSutaComputed = round($sutaTaxableThis * (float) config('payroll.employer_state_unemployment_rate'), 2);
        $employerFuta = $this->taxAmountFromPayloadOrComputed($payload, 'employer', 'federal_unemployment', $employerFutaComputed);
        $employerSuta = $this->taxAmountFromPayloadOrComputed($payload, 'employer', 'state_unemployment', $employerSutaComputed);

        $warnings = [];
        $fed = 0.0;
        if ($detail && ! $detail->tax_zero_federal_income) {
            $fed = (float) ($detail->additional_fed_withholding ?? 0);
            if ($fed <= 0.00001 && $grossTotal > 0) {
                $warnings[] = 'federal_income_tax_not_computed';
            }
        }
        $fed = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'federal_income', $fed);

        $stateIt = 0.0;
        if ($detail && ! $detail->tax_zero_state_income) {
            $stateIt = (float) ($detail->additional_state_withholding ?? 0);
            if ($stateIt <= 0.00001 && $grossTotal > 0) {
                $warnings[] = 'state_income_tax_not_computed';
            }
        }
        $stateIt = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'state_income', $stateIt);

        $localIt = 0.0;
        $employeeSdi = 0.0;
        $employerSdi = 0.0;
        $localIt = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'local_income', $localIt);
        $employeeSdi = $this->taxAmountFromPayloadOrComputed($payload, 'employee', 'state_disability', $employeeSdi);
        $employerSdi = $this->taxAmountFromPayloadOrComputed($payload, 'employer', 'state_disability', $employerSdi);

        $deduction401k = round((float) data_get($payload, 'deductions.401k_employee.amount', 0), 2);
        $totalDeductions = round($deduction401k, 2);
        $employeeTaxesTotal = round($employeeSs + $employeeMed + $fed + $stateIt + $localIt + $employeeSdi, 2);
        $netPay = round($grossTotal - $employeeTaxesTotal - $totalDeductions, 2);

        $incomeYtdAfter = $this->mergeIncomeYtd($prior['income_ytd'], $incomeBreakdown);

        $persistable = [
            'employee_id' => $employee->id,
            'company_id' => $employee->company_id,
            'check_number' => (int) ($payload['check_number'] ?? 1),
            'pay_frequency' => $payFrequency,
            'pay_date' => $payDate->toDateString(),
            'period_begin_date' => $this->nullableDateString($payload['period_begin_date'] ?? null),
            'period_end_date' => $this->nullableDateString($payload['period_end_date'] ?? null),
            'gross_total' => $grossTotal,
            'ss_taxable_wages' => $ssTaxableThis,
            'futa_taxable_wages' => $futaTaxableThis,
            'suta_taxable_wages' => $sutaTaxableThis,
            'income_breakdown' => $incomeBreakdown,
            'employee_federal_income_tax' => $fed,
            'employee_social_security' => $employeeSs,
            'employee_medicare' => $employeeMed,
            'employee_state_income_tax' => $stateIt,
            'employee_local_income_tax' => $localIt,
            'employee_state_disability' => $employeeSdi,
            'employer_social_security' => $employerSs,
            'employer_medicare' => $employerMed,
            'employer_federal_unemployment' => $employerFuta,
            'employer_state_unemployment' => $employerSuta,
            'employer_state_disability' => $employerSdi,
            'deduction_401k' => $deduction401k,
            'total_deductions' => $totalDeductions,
            'employee_taxes_total' => $employeeTaxesTotal,
            'net_pay' => $netPay,
            'memo' => (string) ($payload['check_memo'] ?? ''),
            'calculation_warnings' => $warnings,
        ];

        return [
            'income' => $this->mapIncomeForResponse($incomeBreakdown, $incomeYtdAfter),
            'taxes' => [
                'employee' => [
                    'federal_income' => [
                        'amount' => $this->fmt($fed),
                        'ytd' => $this->fmt($prior['employee_federal_ytd'] + $fed),
                    ],
                    'social_security' => [
                        'amount' => $this->fmt($employeeSs),
                        'ytd' => $this->fmt($prior['employee_ss_ytd'] + $employeeSs),
                    ],
                    'medicare' => [
                        'amount' => $this->fmt($employeeMed),
                        'ytd' => $this->fmt($prior['employee_medicare_ytd'] + $employeeMed),
                    ],
                    'state_income' => [
                        'amount' => $this->fmt($stateIt),
                        'ytd' => $this->fmt($prior['employee_state_ytd'] + $stateIt),
                    ],
                    'local_income' => [
                        'amount' => $this->fmt($localIt),
                        'ytd' => $this->fmt($prior['employee_local_ytd'] + $localIt),
                    ],
                    'state_disability' => [
                        'amount' => $this->fmt($employeeSdi),
                        'ytd' => $this->fmt($prior['employee_sdi_ytd'] + $employeeSdi),
                    ],
                ],
                'employer' => [
                    'social_security' => [
                        'amount' => $this->fmt($employerSs),
                        'ytd' => $this->fmt($prior['employer_ss_ytd'] + $employerSs),
                    ],
                    'medicare' => [
                        'amount' => $this->fmt($employerMed),
                        'ytd' => $this->fmt($prior['employer_medicare_ytd'] + $employerMed),
                    ],
                    'federal_unemployment' => [
                        'amount' => $this->fmt($employerFuta),
                        'ytd' => $this->fmt($prior['employer_futa_ytd'] + $employerFuta),
                    ],
                    'state_unemployment' => [
                        'amount' => $this->fmt($employerSuta),
                        'ytd' => $this->fmt($prior['employer_suta_ytd'] + $employerSuta),
                    ],
                    'state_disability' => [
                        'amount' => $this->fmt($employerSdi),
                        'ytd' => $this->fmt($prior['employer_sdi_ytd'] + $employerSdi),
                    ],
                ],
            ],
            'deductions' => [
                '401k_employee' => [
                    'amount' => $this->fmt($deduction401k),
                    'ytd' => $this->fmt($prior['deduction_401k_ytd'] + $deduction401k),
                ],
            ],
            'summary' => [
                'this_check' => [
                    'total_incomes' => $this->fmt($grossTotal),
                    'total_taxes' => $this->fmt($employeeTaxesTotal),
                    'total_deductions' => $this->fmt($totalDeductions),
                    'net_pay' => $this->fmt($netPay),
                ],
                'ytd' => [
                    'total_incomes' => $this->fmt($prior['gross_ytd'] + $grossTotal),
                    'total_taxes' => $this->fmt($prior['employee_all_taxes_ytd'] + $employeeTaxesTotal),
                    'total_deductions' => $this->fmt($prior['deduction_all_ytd'] + $totalDeductions),
                    'net_pay' => $this->fmt($prior['net_ytd'] + $netPay),
                ],
            ],
            'leave' => $this->mapLeaveForResponse($payload, $prior['leave_ytd_before'] ?? array_fill_keys(self::LEAVE_KEYS, 0.0)),
            'warnings' => $warnings,
            'persistable' => $persistable,
        ];
    }

    /**
     * @param  array<string, float>  $leaveYtdBefore
     * @return array<string, array{amount: string, ytd: string}>
     */
    private function mapLeaveForResponse(array $payload, array $leaveYtdBefore): array
    {
        $out = [];
        $leave = (array) ($payload['leave'] ?? []);
        foreach (self::LEAVE_KEYS as $key) {
            $row = (array) ($leave[$key] ?? []);
            $rawAmount = $row['amount'] ?? null;
            $amount = ($rawAmount === null || $rawAmount === '') ? 0.0 : (float) $rawAmount;
            $amount = round(max(0.0, $amount), 2);
            $before = (float) ($leaveYtdBefore[$key] ?? 0.0);
            $out[$key] = [
                'amount' => $this->fmt($amount),
                'ytd' => $this->fmt($before + $amount),
            ];
        }

        return $out;
    }

    /**
     * When the check form posts a tax amount, use it (Payroll Mate–style overrides); otherwise use computed.
     */
    private function taxAmountFromPayloadOrComputed(array $payload, string $party, string $line, float $computed): float
    {
        $raw = data_get($payload, "taxes.{$party}.{$line}.amount");
        if ($raw === null || $raw === '') {
            return round(max(0.0, $computed), 2);
        }

        return round(max(0.0, (float) $raw), 2);
    }

    /**
     * @return array<string, array<string, float|string>>
     */
    private function computeIncomeLines(array $income, string $payFrequency): array
    {
        $periods = $this->periodsPerYear($payFrequency);
        $out = [];
        foreach (self::INCOME_SLOTS as $slot) {
            $row = (array) ($income[$slot] ?? []);
            $rate = (float) ($row['rate'] ?? 0);
            $qty = (float) ($row['quantity'] ?? 0);
            $payType = (string) ($row['pay_type'] ?? 'per_hour');

            if ($payType === 'per_year') {
                $annual = $rate;
                $amount = ($periods > 0) ? round($annual / $periods, 2) : 0.0;
                $manualRaw = $row['amount'] ?? null;
                $manualAmount = ($manualRaw !== null && $manualRaw !== '') ? (float) $manualRaw : null;
                if ($manualAmount !== null && $manualAmount > 0) {
                    $amount = round($manualAmount, 2);
                }
                $out[$slot] = [
                    'rate' => $rate,
                    'quantity' => '',
                    'amount' => $amount,
                    'pay_type' => 'per_year',
                ];
            } else {
                $computed = round($qty * $rate, 2);
                $manualRaw = $row['amount'] ?? null;
                $manualAmount = ($manualRaw !== null && $manualRaw !== '') ? (float) $manualRaw : null;
                $amount = $computed;
                if ($manualAmount !== null && abs($manualAmount - $computed) > 0.000001) {
                    $stalePostedZero = $computed > 0.000001 && abs($manualAmount) < 0.000001;
                    $stalePostedGrossWithZeroQty = $computed < 0.000001 && $qty < 0.000001 && $manualAmount > 0.000001;
                    if (! $stalePostedZero && ! $stalePostedGrossWithZeroQty) {
                        $amount = round($manualAmount, 2);
                    }
                }
                $out[$slot] = [
                    'rate' => $rate,
                    'quantity' => $qty,
                    'amount' => $amount,
                    'pay_type' => 'per_hour',
                ];
            }
        }

        return $out;
    }

    private function periodsPerYear(string $code): int
    {
        return match ($code) {
            'daily_260' => 260,
            'weekly_52' => 52,
            'biweekly_26' => 26,
            'semimonthly_24' => 24,
            'monthly_12' => 12,
            'quarterly_4' => 4,
            'annual_1' => 1,
            default => 52,
        };
    }

    /**
     * @param  array<string, float>  $priorYtd
     * @param  array<string, array<string, float|string>>  $breakdown
     * @return array<string, float>
     */
    private function mergeIncomeYtd(array $priorYtd, array $breakdown): array
    {
        $out = $priorYtd;
        foreach (self::INCOME_SLOTS as $slot) {
            $amt = (float) ($breakdown[$slot]['amount'] ?? 0);
            $out[$slot] = ($out[$slot] ?? 0.0) + $amt;
        }

        return $out;
    }

    /**
     * @param  array<string, array<string, float|string>>  $breakdown
     * @param  array<string, float>  $ytdAfter
     * @return array<string, array<string, string>>
     */
    private function mapIncomeForResponse(array $breakdown, array $ytdAfter): array
    {
        $resp = [];
        foreach (self::INCOME_SLOTS as $slot) {
            $b = (array) ($breakdown[$slot] ?? []);
            $resp[$slot] = [
                'rate' => $this->fmt((float) ($b['rate'] ?? 0)),
                'pay_type' => (string) ($b['pay_type'] ?? 'per_hour'),
                'quantity' => $b['quantity'] === '' ? '' : $this->fmt((float) $b['quantity']),
                'amount' => $this->fmt((float) ($b['amount'] ?? 0)),
                'ytd' => $this->fmt((float) ($ytdAfter[$slot] ?? 0.0)),
            ];
        }

        return $resp;
    }

    /**
     * @return array<string, float>
     */
    private function priorTotals(int $employeeId, int $year, ?int $excludeCheckId, CarbonInterface $payDate): array
    {
        $checks = PayrollCheck::query()
            ->where('employee_id', $employeeId)
            ->whereYear('pay_date', $year)
            ->orderBy('pay_date')
            ->orderBy('id')
            ->get();

        $payDateStr = $payDate->toDateString();
        $filtered = $checks->filter(function (PayrollCheck $c) use ($payDateStr, $excludeCheckId) {
            $d = $c->pay_date->toDateString();
            if ($d < $payDateStr) {
                return true;
            }
            if ($d > $payDateStr) {
                return false;
            }
            if ($excludeCheckId !== null) {
                return $c->id < $excludeCheckId;
            }

            // New check (not yet saved): include all checks already dated this pay day.
            return true;
        });

        $grossYtd = (float) $filtered->sum('gross_total');
        $ssTaxableCumulative = (float) $filtered->sum('ss_taxable_wages');
        $futaTaxableCumulative = (float) $filtered->sum('futa_taxable_wages');

        $incomeYtd = array_fill_keys(self::INCOME_SLOTS, 0.0);
        $leaveYtdBefore = array_fill_keys(self::LEAVE_KEYS, 0.0);
        foreach ($filtered as $c) {
            $br = $c->income_breakdown ?? [];
            foreach (self::INCOME_SLOTS as $slot) {
                $incomeYtd[$slot] += (float) data_get($br, "{$slot}.amount", 0);
            }
            $preview = $c->check_preview;
            if (is_array($preview) && ! empty($preview['leave']) && is_array($preview['leave'])) {
                foreach (self::LEAVE_KEYS as $leaveKey) {
                    $leaveYtdBefore[$leaveKey] += (float) data_get(
                        $preview['leave'],
                        $leaveKey.'.amount',
                        0
                    );
                }
            }
        }

        return [
            'gross_ytd' => $grossYtd,
            'ss_taxable_cumulative' => $ssTaxableCumulative,
            'futa_taxable_cumulative' => $futaTaxableCumulative,
            'income_ytd' => $incomeYtd,
            'employee_federal_ytd' => (float) $filtered->sum('employee_federal_income_tax'),
            'employee_ss_ytd' => (float) $filtered->sum('employee_social_security'),
            'employee_medicare_ytd' => (float) $filtered->sum('employee_medicare'),
            'employee_state_ytd' => (float) $filtered->sum('employee_state_income_tax'),
            'employee_local_ytd' => (float) $filtered->sum('employee_local_income_tax'),
            'employee_sdi_ytd' => (float) $filtered->sum('employee_state_disability'),
            'employer_ss_ytd' => (float) $filtered->sum('employer_social_security'),
            'employer_medicare_ytd' => (float) $filtered->sum('employer_medicare'),
            'employer_futa_ytd' => (float) $filtered->sum('employer_federal_unemployment'),
            'employer_suta_ytd' => (float) $filtered->sum('employer_state_unemployment'),
            'employer_sdi_ytd' => (float) $filtered->sum('employer_state_disability'),
            'deduction_401k_ytd' => (float) $filtered->sum('deduction_401k'),
            'deduction_all_ytd' => (float) $filtered->sum('total_deductions'),
            'employee_all_taxes_ytd' => (float) $filtered->sum('employee_taxes_total'),
            'net_ytd' => (float) $filtered->sum('net_pay'),
            'leave_ytd_before' => $leaveYtdBefore,
        ];
    }

    private function nullableDateString(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Carbon::parse((string) $value)->toDateString();
    }

    private function fmt(float $n): string
    {
        return number_format($n, 2, '.', '');
    }
}
