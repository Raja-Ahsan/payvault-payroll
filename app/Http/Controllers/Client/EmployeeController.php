<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Services\AchService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        $employees = Employee::whereIn('company_id', $userCompanyIds)
            ->with(['company', 'user'])
            ->latest()
            ->paginate(15);
        return view('client.employees.index', compact('employees'));
    }


    public function create()
    {
        $companies = Company::where('created_by', Auth::id())->get();
        return view('client.employees.create', compact('companies'));
    }

    public function createPayroll(Employee $employee)
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (! in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        return view('client.employees.create_payroll', compact('employee'));
    }

    public function showPayroll(Employee $employee, PayrollItem $payrollItem)
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (! in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ((int) $payrollItem->employee_id !== (int) $employee->id) {
            abort(404);
        }

        $employee->load('company');

        return view('client.employees.show.payroll', compact('employee', 'payrollItem'));
    }

    public function downloadPayrollPaystub(Employee $employee, PayrollItem $payrollItem)
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (! in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ((int) $payrollItem->employee_id !== (int) $employee->id) {
            abort(404);
        }

        $employee->load('company');

        $pdf = Pdf::loadView('client.employees.show.payroll_pdf', compact('employee', 'payrollItem'))
            ->setPaper('letter', 'portrait');

        $filename = 'paystub-'.preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($employee->employee_id ?? $employee->id)).'-'.$payrollItem->id.'.pdf';

        return $pdf->download($filename);
    }

    public function storePayroll(Request $request, Employee $employee)
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (! in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), $this->payrollItemValidationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->payrollItems()->create($this->buildPayrollItemAttributes($request, $employee));

        return redirect()->route('client.employees.show', $employee)
            ->with('success', 'Payroll registered successfully.');
    }

    public function editPayroll(Employee $employee, PayrollItem $payrollItem)
    {
        $this->ensureClientCanAccessEmployee($employee);
        $this->ensurePayrollBelongsToEmployee($employee, $payrollItem);

        return view('client.employees.edit_payroll', compact('employee', 'payrollItem'));
    }

    public function updatePayroll(Request $request, Employee $employee, PayrollItem $payrollItem)
    {
        $this->ensureClientCanAccessEmployee($employee);
        $this->ensurePayrollBelongsToEmployee($employee, $payrollItem);

        $validator = Validator::make($request->all(), $this->payrollItemValidationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payrollItem->update($this->buildPayrollItemAttributes($request, $employee));

        return redirect()->route('client.employees.show', $employee)
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroyPayroll(Employee $employee, PayrollItem $payrollItem)
    {
        $this->ensureClientCanAccessEmployee($employee);
        $this->ensurePayrollBelongsToEmployee($employee, $payrollItem);

        $payrollItem->delete();

        return redirect()->route('client.employees.show', $employee)
            ->with('success', 'Payroll record deleted.');
    }

    public function store(Request $request)
    {
        // Ensure company belongs to user
        $company = Company::where('id', $request->company_id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'occupation' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'annual_salary' => 'required|numeric|min:0',
            'regular_hourly_rate' => 'required|numeric|min:0',
            'overtime_hourly_rate' => 'required|numeric|min:0',
            'federal_allowances' => 'required|integer|min:0',
            '401_k_contrib_percent' => 'required|numeric|min:0|max:100',
            'insurance_deduction' => 'required|numeric|min:0',
            'other_deductions' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Employee::create($request->all());

        return redirect()->route('client.employees.index')
            ->with('success', 'Employee created successfully!');
    }

    public function show(Employee $employee)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $employee->load(['company', 'user']);

        $payrollItems = $employee->payrollItems()
            ->orderByDesc('pay_date')
            ->orderByDesc('id')
            ->paginate(10, ['*'], 'payroll_page');

        $bankAccounts = $employee->bankAccounts()
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->paginate(10, ['*'], 'bank_page');

        return view('client.employees.show', compact('employee', 'payrollItems', 'bankAccounts'));
    }

    public function edit(Employee $employee)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $companies = Company::where('created_by', Auth::id())->get();
        return view('client.employees.edit', compact('employee', 'companies'));
    }

    public function update(Request $request, Employee $employee)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure new company also belongs to user
        if ($request->company_id != $employee->company_id) {
            $company = Company::where('id', $request->company_id)
                ->where('created_by', Auth::id())
                ->firstOrFail();
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'occupation' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'annual_salary' => 'required|numeric|min:0',
            'regular_hourly_rate' => 'required|numeric|min:0',
            'overtime_hourly_rate' => 'required|numeric|min:0',
            'federal_allowances' => 'required|integer|min:0',
            '401_k_contrib_percent' => 'required|numeric|min:0|max:100',
            'insurance_deduction' => 'required|numeric|min:0',
            'other_deductions' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->update($request->only([
            'company_id',
            'name',
            'employee_id',
            'address',
            'gender',
            'occupation',
            'hire_date',
            'annual_salary',
            'regular_hourly_rate',
            'overtime_hourly_rate',
            'federal_allowances',
            '401_k_contrib_percent',
            'insurance_deduction',
            'other_deductions',
            'net_pay',
        ]));

        return redirect()->route('client.employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $employee->delete();
        return redirect()->route('client.employees.index')
            ->with('success', 'Employee deleted successfully!');
    }

    public function storeBankAccount(Request $request, Employee $employee)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_type' => 'required|in:checking,savings',
            'routing_number' => 'required|string|size:9',
            'account_number' => 'required|string|min:4',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If this is set as primary, unset other primary accounts
        if ($request->is_primary) {
            $employee->bankAccounts()->update(['is_primary' => false]);
        }

        $bankAccount = BankAccount::create([
            'accountable_type' => Employee::class,
            'accountable_id' => $employee->id,
            'bank_name' => $request->bank_name,
            'account_holder_name' => $request->account_holder_name,
            'account_type' => $request->account_type,
            'routing_number' => $request->routing_number,
            'account_number' => $request->account_number,
            'is_primary' => $request->is_primary ?? false,
            'is_active' => true,
            'verification_status' => 'pending',
        ]);

        return redirect()->route('client.employees.show', $employee)
            ->with('success', 'Bank account added successfully! Please verify it to enable ACH processing.');
    }

    public function verifyBankAccount(Employee $employee, BankAccount $bankAccount, AchService $achService)
    {
        // Ensure employee belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure bank account belongs to employee
        if ($bankAccount->accountable_type !== Employee::class || $bankAccount->accountable_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $achService->verifyBankAccount($bankAccount);

        return redirect()->route('client.employees.show', $employee)
            ->with('success', 'Bank account verified successfully!');
    }

    protected function ensureClientCanAccessEmployee(Employee $employee): void
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (! in_array($employee->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }
    }

    protected function ensurePayrollBelongsToEmployee(Employee $employee, PayrollItem $payrollItem): void
    {
        if ((int) $payrollItem->employee_id !== (int) $employee->id) {
            abort(404);
        }
    }

    /**
     * @return array<string, string>
     */
    protected function payrollItemValidationRules(): array
    {
        return [
            'pay_date' => 'required|date',
            'pay_period' => 'required|string|max:255',
            'regular_hours' => 'required|numeric|min:0',
            'vacation_hours' => 'required|numeric|min:0',
            'sick_hours' => 'required|numeric|min:0',
            'holidays_hours' => 'required|numeric|min:0',
            'personal_hours' => 'required|numeric|min:0',
            'overtime_hours' => 'required|numeric|min:0',
            'fed_tax' => 'required|numeric|min:0',
            'state_tax' => 'required|numeric|min:0',
            'local_tax' => 'required|numeric|min:0',
            'social_security' => 'required|numeric|min:0',
            'medi_care' => 'required|numeric|min:0',
            'insurance_deduction' => 'required|numeric|min:0',
            'other_deductions' => 'required|numeric|min:0',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildPayrollItemAttributes(Request $request, Employee $employee): array
    {
        $regularRate = (float) ($employee->regular_hourly_rate ?? 0);
        $overtimeRate = (float) ($employee->overtime_hourly_rate ?? 0);

        $hoursAtRegularRate =
            (float) $request->input('regular_hours', 0)
            + (float) $request->input('vacation_hours', 0)
            + (float) $request->input('sick_hours', 0)
            + (float) $request->input('holidays_hours', 0)
            + (float) $request->input('personal_hours', 0);

        $grossPay = round(
            ($hoursAtRegularRate * $regularRate) + ((float) $request->input('overtime_hours', 0) * $overtimeRate),
            2
        );

        $k401Amount = round($grossPay * ($employee->effective401kContributionPercent() / 100), 2);

        $totalDeductions =
            $k401Amount
            + (float) $request->fed_tax
            + (float) $request->state_tax
            + (float) $request->local_tax
            + (float) $request->social_security
            + (float) $request->medi_care
            + (float) $request->insurance_deduction
            + (float) $request->other_deductions;

        $netPay = round($grossPay - $totalDeductions, 2);

        return array_merge(
            $request->only([
                'pay_date',
                'pay_period',
                'regular_hours',
                'vacation_hours',
                'sick_hours',
                'holidays_hours',
                'personal_hours',
                'overtime_hours',
                'fed_tax',
                'state_tax',
                'local_tax',
                'social_security',
                'medi_care',
                'insurance_deduction',
                'other_deductions',
            ]),
            [
                'gross_pay' => $grossPay,
                'k401_amount' => $k401Amount,
                'net_pay' => $netPay,
            ]
        );
    }
}
