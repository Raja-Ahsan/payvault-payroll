<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Employee;
use App\Services\AchService;
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

    public function store(Request $request)
    {
        // Ensure company belongs to user
        $company = Company::where('id', $request->company_id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string',
            'pay_type' => 'required|in:salary,hourly',
            'salary' => 'required_if:pay_type,salary|numeric|min:0',
            'hourly_rate' => 'required_if:pay_type,hourly|numeric|min:0',
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

        $employee->load(['company', 'user', 'bankAccounts', 'payrollItems']);
        return view('client.employees.show', compact('employee'));
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'pay_type' => 'required|in:salary,hourly',
            'salary' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->update($request->all());

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
}
