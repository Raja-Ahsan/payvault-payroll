<?php

namespace App\Services;

use App\Models\AchTransaction;
use App\Models\BankAccount;
use App\Models\PayrollRun;
use Illuminate\Support\Facades\Log;

class AchService
{
    /**
     * Process ACH transactions for a payroll run.
     */
    public function processPayrollAch(PayrollRun $payrollRun): array
    {
        $payrollItems = $payrollRun->payrollItems;
        $transactions = [];

        foreach ($payrollItems as $item) {
            $employee = $item->employee;
            $bankAccount = $employee->bankAccounts()
                ->where('is_active', true)
                ->where('is_primary', true)
                ->where('verification_status', 'verified')
                ->first();

            if (!$bankAccount) {
                Log::warning("No verified bank account found for employee {$employee->id}");
                continue;
            }

            $transaction = $this->createAchTransaction(
                $payrollRun,
                $bankAccount,
                'credit',
                $item->net_pay
            );

            $transactions[] = $transaction;
        }

        return $transactions;
    }

    /**
     * Create an ACH transaction.
     */
    public function createAchTransaction(
        PayrollRun $payrollRun,
        BankAccount $bankAccount,
        string $transactionType,
        float $amount
    ): AchTransaction {
        $transaction = AchTransaction::create([
            'payroll_run_id' => $payrollRun->id,
            'bank_account_id' => $bankAccount->id,
            'transaction_type' => $transactionType,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        // In production, this would send to ACH processor
        // For now, simulate processing
        $this->simulateAchProcessing($transaction);

        return $transaction;
    }

    /**
     * Simulate ACH processing (replace with actual ACH processor integration).
     */
    protected function simulateAchProcessing(AchTransaction $transaction): void
    {
        // Simulate processing delay
        $transaction->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);

        // Simulate completion (in production, this would be a webhook from processor)
        // For now, mark as completed after a delay
        $transaction->update([
            'status' => 'completed',
            'completed_at' => now(),
            'transaction_id' => 'SIM-' . uniqid(),
            'processor_response' => [
                'status' => 'completed',
                'message' => 'Transaction processed successfully',
            ],
        ]);
    }

    /**
     * Verify a bank account.
     */
    public function verifyBankAccount(BankAccount $bankAccount): bool
    {
        // In production, this would integrate with bank verification service
        // For now, simulate verification
        $bankAccount->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return true;
    }

    /**
     * Get transaction status.
     */
    public function getTransactionStatus(AchTransaction $transaction): array
    {
        return [
            'id' => $transaction->id,
            'status' => $transaction->status,
            'amount' => $transaction->amount,
            'transaction_id' => $transaction->transaction_id,
            'processed_at' => $transaction->processed_at,
            'completed_at' => $transaction->completed_at,
        ];
    }
}
