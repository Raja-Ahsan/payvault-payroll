<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $payrollItems = PayrollItem::where('employee_id', $employee->id)
            ->with(['payrollRun.company'])
            ->whereHas('payrollRun', function($query) {
                $query->where('status', 'finalized');
            })
            ->latest()
            ->paginate(15);

        return view('employee.payroll.index', compact('payrollItems', 'employee'));
    }

    public function show(PayrollItem $payrollItem)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        // Ensure payroll item belongs to this employee
        if ($payrollItem->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $payrollItem->load([
            'payrollRun.company',
            'deductions'
        ]);

        return view('employee.payroll.show', compact('payrollItem', 'employee'));
    }
}
