<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayrollCheckCalculationRequest;
use App\Models\Employee;
use App\Models\PayrollCheck;
use App\Services\Payroll\EmployeeCheckScaffoldService;
use App\Services\Payroll\PayrollCheckCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CheckController extends Controller
{
    public function index(): View
    {
        $checksQuery = PayrollCheck::query()->with(['employee']);

        if (! userHasRole('admin')) {
            if (userHasRole('employee')) {
                $checksQuery->whereHas('employee', fn ($q) => $q->where('user_id', auth()->id()));
            } else {
                $checksQuery->whereHas('employee.company', fn ($q) => $q->where('user_id', auth()->id()));
            }
        }

        $checks = $checksQuery
            ->orderByDesc('pay_date')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('screens.admin.checks.index', compact('checks'));
    }

    public function create(): View
    {
        $employees = $this->employeesForCheckContext();
        $checkScaffoldUrl = route('checks.scaffold');
        $checkRecalculateUrl = route('checks.recalculate');
        $checkStoreUrl = route('checks.store');
        $employeesListUrl = route('checks.employees-for-select');

        return view('screens.admin.checks.create', compact(
            'employees',
            'checkScaffoldUrl',
            'checkRecalculateUrl',
            'checkStoreUrl',
            'employeesListUrl'
        ));
    }

    public function employeesForSelect(): JsonResponse
    {
        $employees = $this->employeesForCheckContext()->map(fn (Employee $emp): array => [
            'id' => $emp->id,
            'label' => $this->employeeSelectLabel($emp),
        ])->values();

        return response()->json(['employees' => $employees]);
    }

    public function employeeScaffold(Request $request, EmployeeCheckScaffoldService $service): JsonResponse
    {
        $data = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        $employee = Employee::query()->findOrFail($data['employee_id']);
        $this->ensureEmployeeAccessibleForChecks($employee);

        return response()->json($service->forEmployee($employee));
    }

    public function recalculate(PayrollCheckCalculationRequest $request, PayrollCheckCalculator $calculator): JsonResponse
    {
        $employee = Employee::query()->findOrFail((int) $request->validated('employee_id'));
        $this->ensureEmployeeAccessibleForChecks($employee);

        $result = $calculator->calculate($request->validated(), null);

        return response()->json(Arr::except($result, ['persistable']));
    }

    public function store(PayrollCheckCalculationRequest $request, PayrollCheckCalculator $calculator): JsonResponse
    {
        $employee = Employee::query()->findOrFail((int) $request->validated('employee_id'));
        $this->ensureEmployeeAccessibleForChecks($employee);

        $result = $calculator->calculate($request->validated(), null);
        $payload = $result['persistable'];
        $payload['check_preview'] = Arr::except($result, ['persistable']);
        $payload['created_by'] = auth()->id();

        $check = PayrollCheck::query()->create($payload);

        return response()->json([
            'message' => 'Check saved.',
            'redirect' => route('checks.show', $check),
        ], 201);
    }

    public function show(PayrollCheck $payrollCheck): View
    {
        $payrollCheck->load(['employee', 'company']);
        $this->ensurePayrollCheckAccessible($payrollCheck);

        return view('screens.admin.checks.show', ['check' => $payrollCheck]);
    }

    public function downloadPdf(PayrollCheck $payrollCheck): Response
    {
        $payrollCheck->load(['employee', 'company']);
        $this->ensurePayrollCheckAccessible($payrollCheck);

        return Pdf::loadView('pdf.payroll-check', ['check' => $payrollCheck])
            ->setPaper('letter', 'portrait')
            ->download('Payroll-Check-'.$payrollCheck->check_number.'.pdf');
    }

    public function edit(PayrollCheck $payrollCheck): View
    {
        $this->ensurePayrollCheckAccessible($payrollCheck);

        return view('screens.admin.checks.edit', ['checkId' => $payrollCheck->id]);
    }

    /**
     * @return Collection<int, Employee>
     */
    private function employeesForCheckContext(): Collection
    {
        return Employee::query()
            ->where('inactive', false)
            ->when(! userHasRole('admin'), function ($query) {
                $query->whereHas('company', fn ($q) => $q->where('user_id', auth()->id()));
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
    }

    private function employeeSelectLabel(Employee $emp): string
    {
        $ln = trim((string) $emp->last_name);
        $fn = trim((string) $emp->first_name);
        $mn = trim((string) ($emp->middle_name ?? ''));
        $label = $ln !== '' && ($fn !== '' || $mn !== '')
            ? $ln.', '.trim($fn.' '.$mn)
            : ($ln !== '' ? $ln : trim($fn.' '.$mn));

        return $label !== '' ? $label : 'Employee #'.$emp->id;
    }

    private function ensureEmployeeAccessibleForChecks(Employee $employee): void
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

    private function ensurePayrollCheckAccessible(PayrollCheck $payrollCheck): void
    {
        $payrollCheck->loadMissing('employee.company');

        if (userHasRole('admin')) {
            return;
        }

        if (userHasRole('employee')) {
            abort_unless(
                $payrollCheck->employee && (int) $payrollCheck->employee->user_id === (int) auth()->id(),
                403
            );

            return;
        }

        abort_unless(
            $payrollCheck->employee
                && $payrollCheck->employee->company
                && (int) $payrollCheck->employee->company->user_id === (int) auth()->id(),
            403
        );
    }
}
