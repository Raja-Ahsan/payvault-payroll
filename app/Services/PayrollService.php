<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Calculate payroll for a payroll run.
     */
    public function calculatePayroll(PayrollRun $payrollRun): array
    {
        $employees = Employee::where('company_id', $payrollRun->company_id)
            ->where('is_active', true)
            ->get();

        $totalGross = 0;
        $totalDeductions = 0;
        $totalNet = 0;

        foreach ($employees as $employee) {
            $payrollItem = $this->calculateEmployeePayroll($payrollRun, $employee);
            $totalGross += $payrollItem->gross_pay;
            $totalDeductions += $payrollItem->total_deductions;
            $totalNet += $payrollItem->net_pay;
        }

        $payrollRun->update([
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
            'status' => 'preview',
        ]);

        return [
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
        ];
    }

    /**
     * Calculate payroll for a single employee.
     */
    public function calculateEmployeePayroll(PayrollRun $payrollRun, Employee $employee): PayrollItem
    {
        // Calculate hours worked (for hourly employees)
        $hoursWorked = $this->calculateHoursWorked($employee, $payrollRun);
        $regularHours = min($hoursWorked, $employee->standard_hours_per_week);
        $overtimeHours = max(0, $hoursWorked - $employee->standard_hours_per_week);

        // Calculate gross pay
        $grossPay = 0;
        if ($employee->pay_type === 'hourly') {
            $regularRate = $employee->hourly_rate ?? 0;
            $overtimeRate = $regularRate * 1.5; // Standard overtime rate
            $grossPay = ($regularHours * $regularRate) + ($overtimeHours * $overtimeRate);
        } else {
            // Salary employees - calculate based on pay period
            $grossPay = $this->calculateSalaryPay($employee, $payrollRun);
            $regularHours = $employee->standard_hours_per_week;
            $overtimeHours = 0;
        }

        // Calculate taxes
        $taxes = $this->calculateTaxes($employee, $grossPay);

        // Calculate deductions
        $deductions = $this->calculateDeductions($employee, $grossPay);

        // Calculate net pay
        $netPay = $grossPay - $taxes['total'] - $deductions['total'];

        // Create or update payroll item
        $payrollItem = PayrollItem::updateOrCreate(
            [
                'payroll_run_id' => $payrollRun->id,
                'employee_id' => $employee->id,
            ],
            [
                'hours_worked' => $hoursWorked,
                'regular_hours' => $regularHours,
                'overtime_hours' => $overtimeHours,
                'regular_rate' => $employee->hourly_rate ?? 0,
                'overtime_rate' => ($employee->hourly_rate ?? 0) * 1.5,
                'gross_pay' => $grossPay,
                'federal_tax' => $taxes['federal'],
                'state_tax' => $taxes['state'],
                'local_tax' => $taxes['local'],
                'social_security' => $taxes['social_security'],
                'medicare' => $taxes['medicare'],
                'total_taxes' => $taxes['total'],
                'total_deductions' => $deductions['total'],
                'net_pay' => $netPay,
            ]
        );

        return $payrollItem;
    }

    /**
     * Calculate hours worked for an employee.
     */
    protected function calculateHoursWorked(Employee $employee, PayrollRun $payrollRun): float
    {
        // For now, return standard hours. In production, this would integrate with time tracking
        return $employee->standard_hours_per_week;
    }

    /**
     * Calculate salary pay based on pay period.
     */
    protected function calculateSalaryPay(Employee $employee, PayrollRun $payrollRun): float
    {
        $salary = $employee->salary ?? 0;
        $daysInPeriod = $payrollRun->pay_period_start->diffInDays($payrollRun->pay_period_end) + 1;
        
        switch ($payrollRun->pay_period_type) {
            case 'weekly':
                return $salary / 52;
            case 'biweekly':
                return $salary / 26;
            case 'semimonthly':
                return $salary / 24;
            case 'monthly':
                return $salary / 12;
            default:
                return $salary / 52; // Default to weekly
        }
    }

    /**
     * Calculate taxes for an employee.
     */
    protected function calculateTaxes(Employee $employee, float $grossPay): array
    {
        // Federal tax calculation (simplified - in production, use tax tables)
        $federalTax = $this->calculateFederalTax($employee, $grossPay);
        
        // Social Security (6.2% up to wage base)
        $socialSecurityRate = 0.062;
        $socialSecurityWageBase = 160200; // 2024 wage base
        $socialSecurity = min($grossPay * $socialSecurityRate, $socialSecurityWageBase * $socialSecurityRate);
        
        // Medicare (1.45% + 0.9% for high earners)
        $medicareRate = 0.0145;
        $additionalMedicareRate = $grossPay > 200000 ? 0.009 : 0;
        $medicare = $grossPay * ($medicareRate + $additionalMedicareRate);
        
        // State tax (simplified - in production, use state-specific calculations)
        $stateTax = $this->calculateStateTax($employee, $grossPay);
        
        // Local tax (simplified)
        $localTax = 0;
        
        $total = $federalTax + $stateTax + $localTax + $socialSecurity + $medicare;

        return [
            'federal' => round($federalTax, 2),
            'state' => round($stateTax, 2),
            'local' => round($localTax, 2),
            'social_security' => round($socialSecurity, 2),
            'medicare' => round($medicare, 2),
            'total' => round($total, 2),
        ];
    }

    /**
     * Calculate federal tax (simplified).
     */
    protected function calculateFederalTax(Employee $employee, float $grossPay): float
    {
        // Simplified tax calculation - in production, use actual tax tables
        $allowances = $employee->federal_allowances ?? 0;
        $standardDeduction = 14600; // 2024 standard deduction (single)
        $taxableIncome = max(0, $grossPay - ($allowances * 4850) - $standardDeduction);
        
        // Simplified progressive tax brackets (2024)
        $tax = 0;
        if ($taxableIncome > 0) {
            if ($taxableIncome <= 11600) {
                $tax = $taxableIncome * 0.10;
            } elseif ($taxableIncome <= 47150) {
                $tax = 1160 + (($taxableIncome - 11600) * 0.12);
            } elseif ($taxableIncome <= 100525) {
                $tax = 5426 + (($taxableIncome - 47150) * 0.22);
            } else {
                $tax = 17198.50 + (($taxableIncome - 100525) * 0.24);
            }
        }
        
        return max(0, $tax);
    }

    /**
     * Calculate state tax (simplified).
     */
    protected function calculateStateTax(Employee $employee, float $grossPay): float
    {
        // Simplified - in production, use state-specific tax calculations
        // For now, return 0 or a flat rate based on state
        return 0;
    }

    /**
     * Calculate deductions for an employee.
     */
    protected function calculateDeductions(Employee $employee, float $grossPay): array
    {
        // In production, this would fetch deduction rules from database
        // For now, return empty deductions
        return [
            'total' => 0,
        ];
    }
}
