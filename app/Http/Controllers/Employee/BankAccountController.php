<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Employee;
use App\Services\AchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $bankAccounts = $employee->bankAccounts()
            ->latest()
            ->get();

        return view('employee.bank-accounts.index', compact('bankAccounts', 'employee'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

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

        return redirect()->route('employee.bank-accounts.index')
            ->with('success', 'Bank account added successfully!');
    }

    public function verify(BankAccount $bankAccount, AchService $achService)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        // Ensure bank account belongs to employee
        if ($bankAccount->accountable_type !== Employee::class || $bankAccount->accountable_id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        $achService->verifyBankAccount($bankAccount);

        return redirect()->route('employee.bank-accounts.index')
            ->with('success', 'Bank account verified successfully!');
    }
}
