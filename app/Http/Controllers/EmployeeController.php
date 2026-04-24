<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Mail\EmployeeInviteMail;
use App\Models\Company;
use App\Models\DeductionCategory;
use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Models\EmployeeIncomeCategory;
use App\Models\IncomeType;
use App\Models\State;
use App\Models\TaxCategory;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        abort_if(userHasRole('employee'), 403);

        $employees = Employee::query()
            ->with(['company', 'detail'])
            ->when(! userHasRole('admin'), function ($query) {
                $query->whereHas('company', fn ($q) => $q->where('user_id', auth()->id()));
            })
            ->orderByDesc('id')
            ->get();

        return view('screens.admin.employees.index', compact('employees'));
    }

    public function show(Employee $employee): View
    {
        $this->ensureEmployeeAccessible($employee);

        $employee->load([
            'company',
            'state',
            'detail.withholdingState',
            'incomeCategories.incomeCategory.incomeType',
            'taxCategories',
        ]);

        return view('screens.admin.employees.show', compact('employee'));
    }

    public function create(): View
    {
        abort_if(userHasRole('employee'), 403);

        $employee = null;
        $incomeCategoriesTypes = IncomeType::with('categories')->get();
        $taxCategories = TaxCategory::all();
        $deductionCategories = DeductionCategory::with('incomeType')->get();
        $states = State::all();
        $companies = userHasRole('admin')
            ? Company::query()->orderBy('company_name')->get()
            : Company::query()->where('user_id', auth()->id())->orderBy('company_name')->get();

        return view('screens.admin.employees.create', get_defined_vars());
    }

    public function edit(Employee $employee): View
    {
        $this->ensureEmployeeAccessible($employee);

        $employee->load(['detail', 'incomeCategories.incomeCategory', 'state', 'company', 'taxCategories']);
        $incomeCategoriesTypes = IncomeType::with('categories')->get();
        $taxCategories = TaxCategory::all();
        $deductionCategories = DeductionCategory::with('incomeType')->get();
        $states = State::all();
        if (userHasRole('employee')) {
            $companies = Company::query()->whereKey($employee->company_id)->orderBy('company_name')->get();
        } elseif (userHasRole('admin')) {
            $companies = Company::query()->orderBy('company_name')->get();
        } else {
            $companies = Company::query()->where('user_id', auth()->id())->orderBy('company_name')->get();
        }

        return view('screens.admin.employees.edit', get_defined_vars());
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        abort_if(userHasRole('employee'), 403);

        $userId = (int) auth()->id();
        $companyId = (int) $request->validated('company_id');

        $employeeFields = $request->safe()->only([
            'first_name',
            'middle_name',
            'last_name',
            'address_1',
            'address_2',
            'city',
            'state_id',
            'zip_code',
            'ssn',
            'dob',
            'phone',
            'fax',
            'email',
            'employee_id',
            'message',
            'inactive',
        ]);

        $employeeFields['company_id'] = $companyId;
        $employeeFields['created_by'] = $userId;
        $this->normalizeEmployeeIdColumn($employeeFields);

        $detailFields = $request->safe()->only([
            'fed_filing_status',
            'fed_allowances',
            'pay_frequency',
            'additional_fed_withholding',
            'use_new_w4_2020',
            'w4_step2_two_jobs',
            'w4_step3_dependents',
            'w4_step4a_other_income',
            'w4_step4b_deductions',
            'w2_statutory_employee',
            'w2_retirement_plan',
            'w2_advance_eic',
            'tax_zero_federal_income',
            'tax_zero_state_income',
            'tax_zero_ss_med_employee',
            'tax_zero_ss_med_employer',
            'withholding_state_id',
            'additional_state_withholding',
            'state_filing_status',
            'state_personal_allowances',
            'state_dependent_allowances',
            'include_in_direct_deposit',
            'account_type',
            'bank_routing_number',
            'account_number',
            'vacation_sick_calculation_method',
            'vacation_hours_earned_per_unit',
            'max_vacation_hours_per_year',
            'sick_hours_earned_per_unit',
            'max_sick_hours_per_year',
        ]);

        $detailFields['company_id'] = $companyId;
        $detailFields['created_by'] = $userId;

        $incomeRows = [];
        foreach ($request->input('income_category_id', []) as $id) {
            $id = (int) $id;
            $incomeRows[] = [
                'income_category_id' => $id,
                'amount' => $request->input("income_amounts.$id"),
            ];
        }

        $company = Company::query()->findOrFail($companyId);
        $plainInvitePassword = null;
        $inviteUserForMail = null;

        DB::transaction(function () use (
            $request,
            $employeeFields,
            $detailFields,
            $incomeRows,
            &$plainInvitePassword,
            &$inviteUserForMail
        ): void {
            $email = (string) $employeeFields['email'];
            $existingUser = User::query()->where('email', $email)->first();

            if ($existingUser !== null) {
                $employeeFields['user_id'] = $existingUser->id;
            } else {
                $plainInvitePassword = Str::random(10);
                $newUser = User::query()->create([
                    'name' => (string) $employeeFields['first_name'],
                    'email' => $email,
                    'password' => Hash::make($plainInvitePassword),
                ]);
                $newUser->assignRole(config('roles.employee'));
                $employeeFields['user_id'] = $newUser->id;
                $inviteUserForMail = $newUser;
            }

            $employee = Employee::query()->create($employeeFields);

            $detailFields['employee_id'] = $employee->id;
            EmployeeDetail::query()->create($detailFields);

            foreach ($incomeRows as $row) {
                EmployeeIncomeCategory::query()->create([
                    'employee_id' => $employee->id,
                    'income_category_id' => $row['income_category_id'],
                    'amount' => $row['amount'],
                ]);
            }

            $employee->taxCategories()->sync($this->taxCategoryIdsFromRequest($request));
        });

        if ($plainInvitePassword !== null && $inviteUserForMail !== null) {
            try {
                Mail::to($inviteUserForMail->email)->send(new EmployeeInviteMail($company, $inviteUserForMail, $plainInvitePassword));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json([
            'message' => 'Employee created successfully.',
            'redirect' => route('employees.index'),
        ], 201);
    }

    public function update(StoreEmployeeRequest $request, Employee $employee): JsonResponse
    {
        $this->ensureEmployeeAccessible($employee);

        $companyId = userHasRole('employee')
            ? (int) $employee->company_id
            : (int) $request->validated('company_id');

        $employeeFields = $request->safe()->only([
            'first_name',
            'middle_name',
            'last_name',
            'address_1',
            'address_2',
            'city',
            'state_id',
            'zip_code',
            'ssn',
            'dob',
            'phone',
            'fax',
            'email',
            'employee_id',
            'message',
            'inactive',
        ]);

        $employeeFields['company_id'] = $companyId;
        $this->normalizeEmployeeIdColumn($employeeFields);

        $detailFields = $request->safe()->only([
            'fed_filing_status',
            'fed_allowances',
            'pay_frequency',
            'additional_fed_withholding',
            'use_new_w4_2020',
            'w4_step2_two_jobs',
            'w4_step3_dependents',
            'w4_step4a_other_income',
            'w4_step4b_deductions',
            'w2_statutory_employee',
            'w2_retirement_plan',
            'w2_advance_eic',
            'tax_zero_federal_income',
            'tax_zero_state_income',
            'tax_zero_ss_med_employee',
            'tax_zero_ss_med_employer',
            'withholding_state_id',
            'additional_state_withholding',
            'state_filing_status',
            'state_personal_allowances',
            'state_dependent_allowances',
            'include_in_direct_deposit',
            'account_type',
            'bank_routing_number',
            'account_number',
            'vacation_sick_calculation_method',
            'vacation_hours_earned_per_unit',
            'max_vacation_hours_per_year',
            'sick_hours_earned_per_unit',
            'max_sick_hours_per_year',
        ]);

        $detailFields['company_id'] = $companyId;

        $incomeRows = [];
        foreach ($request->input('income_category_id', []) as $id) {
            $id = (int) $id;
            $incomeRows[] = [
                'income_category_id' => $id,
                'amount' => $request->input("income_amounts.$id"),
            ];
        }

        DB::transaction(function () use ($request, $employee, $employeeFields, $detailFields, $incomeRows): void {
            $employee->update($employeeFields);

            $detailFields['employee_id'] = $employee->id;
            if ($employee->detail) {
                $employee->detail->update($detailFields);
            } else {
                $detailFields['created_by'] = (int) auth()->id();
                EmployeeDetail::query()->create($detailFields);
            }

            $employee->incomeCategories()->delete();
            foreach ($incomeRows as $row) {
                EmployeeIncomeCategory::query()->create([
                    'employee_id' => $employee->id,
                    'income_category_id' => $row['income_category_id'],
                    'amount' => $row['amount'],
                ]);
            }

            $employee->taxCategories()->sync($this->taxCategoryIdsFromRequest($request));
        });

        return response()->json([
            'message' => 'Employee updated successfully.',
            'redirect' => userHasRole('employee')
                ? route('admin.dashboard')
                : route('employees.index'),
        ]);
    }

    public function delete(Employee $employee): JsonResponse
    {
        abort_if(userHasRole('employee'), 403);

        $this->ensureEmployeeAccessible($employee);

        DB::transaction(function () use ($employee): void {
            $employee->loadMissing('detail');
            $employee->detail?->delete();
            $employee->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }

    private function ensureEmployeeAccessible(Employee $employee): void
    {
        if (userHasRole('admin')) {
            return;
        }

        if (userHasRole('employee')) {
            abort_unless(
                (int) $employee->user_id === (int) auth()->id(),
                403
            );

            return;
        }

        $employee->loadMissing('company');

        abort_unless(
            $employee->company && (int) $employee->company->user_id === (int) auth()->id(),
            403
        );
    }

    /**
     * @return array<int, int>
     */
    private function taxCategoryIdsFromRequest(StoreEmployeeRequest $request): array
    {
        return array_values(array_unique(array_filter(array_map('intval', (array) $request->input('tax_category_id', [])))));
    }

    /**
     * employees.employee_id is NOT NULL; empty form submissions must persist as '' not null.
     *
     * @param  array<string, mixed>  $employeeFields
     */
    private function normalizeEmployeeIdColumn(array &$employeeFields): void
    {
        $raw = $employeeFields['employee_id'] ?? null;
        $employeeFields['employee_id'] = ($raw !== null && trim((string) $raw) !== '')
            ? trim((string) $raw)
            : '';
    }
}
