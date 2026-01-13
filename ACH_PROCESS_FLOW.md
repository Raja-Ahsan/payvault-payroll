# ACH Processing Flow - Complete Guide

## 🔄 How ACH Processing Works

### Overview
ACH (Automated Clearing House) processing handles direct deposit payments to employees. Here's the complete flow:

## 📋 Step-by-Step Process Flow

### Step 1: Prerequisites
Before processing ACH, you need:
1. ✅ **Company** created
2. ✅ **Employees** added to the company
3. ✅ **Bank Accounts** added for employees (with verified status)
4. ✅ **Payroll Run** created and finalized

### Step 2: Employee Bank Account Setup
Each employee needs a verified bank account:
- Go to: `/admin/employees/{id}/edit` (or create bank account via API)
- Bank account must have:
  - `is_active = true`
  - `is_primary = true`
  - `verification_status = 'verified'`

### Step 3: Payroll Run Lifecycle
The payroll must go through these stages:

```
Draft → Calculate → Preview → Approve → Finalized → Process ACH
```

1. **Create Payroll Run** (Status: `draft`)
   - Select company
   - Set pay period dates
   - Set pay date

2. **Calculate Payroll** (Status: `preview`)
   - System calculates payroll for all active employees
   - Creates payroll items with gross pay, taxes, deductions, net pay

3. **Approve Payroll** (Status: `approved`)
   - Review the calculated payroll
   - Approve to lock it in

4. **Finalize Payroll** (Status: `finalized`)
   - Finalizes the payroll run
   - Makes it ready for ACH processing

5. **Process ACH** (Creates transactions)
   - Only works on `finalized` payroll runs
   - Creates ACH transactions for each employee with verified bank account

### Step 4: ACH Transaction Creation
When you click "Process ACH":

1. **System checks each payroll item:**
   - Gets the employee
   - Finds their verified primary bank account
   - If found, creates an ACH transaction

2. **Transaction Details:**
   - Type: `credit` (employee deposit)
   - Amount: Employee's net pay
   - Status: Starts as `pending`, then `processing`, then `completed`
   - Links to: Payroll Run and Bank Account

3. **Simulation:**
   - Currently simulates processing (instant completion)
   - In production, this would integrate with actual ACH processor
   - Transaction ID is generated: `SIM-{unique_id}`

### Step 5: View ACH Transactions
After processing:
- Go to: `/admin/ach` or click "ACH Transactions" in sidebar
- You'll see all created transactions
- Filter by status: pending, processing, completed, failed

## 🔍 Why You Might Not See Transactions

### Common Issues:

1. **No Bank Accounts for Employees**
   - Employees don't have bank accounts added
   - **Solution:** Add bank accounts to employees first

2. **Bank Accounts Not Verified**
   - Bank accounts exist but `verification_status != 'verified'`
   - **Solution:** Verify bank accounts (currently simulated)

3. **No Primary Bank Account**
   - Employee has bank accounts but none marked as `is_primary = true`
   - **Solution:** Mark one bank account as primary

4. **No Payroll Items**
   - Payroll run has no calculated items
   - **Solution:** Make sure to calculate payroll before finalizing

5. **Payroll Not Finalized**
   - Can only process ACH on finalized payrolls
   - **Solution:** Complete the payroll lifecycle first

## 🛠️ How to Test the Full Flow

### Quick Test Setup:

1. **Create a Company:**
   ```
   POST /admin/companies
   ```

2. **Create an Employee:**
   ```
   POST /admin/employees
   - company_id: [company id]
   - first_name: "John"
   - last_name: "Doe"
   - email: "john@example.com"
   - pay_type: "hourly"
   - hourly_rate: 25.00
   ```

3. **Add Bank Account (via API or manually in database):**
   ```sql
   INSERT INTO bank_accounts (
       accountable_type, accountable_id, account_type, bank_name,
       account_holder_name, routing_number, account_number,
       verification_status, is_primary, is_active
   ) VALUES (
       'App\\Models\\Employee', [employee_id], 'checking',
       'Test Bank', 'John Doe', '123456789', '987654321',
       'verified', true, true
   );
   ```

4. **Create Payroll Run:**
   - Go to `/admin/payroll/create`
   - Fill in details
   - Submit

5. **Calculate Payroll:**
   - Click "Calculate Payroll" button
   - Status changes to `preview`

6. **Approve Payroll:**
   - Click "Approve" button
   - Status changes to `approved`

7. **Finalize Payroll:**
   - Click "Finalize" button
   - Status changes to `finalized`

8. **Process ACH:**
   - Click "Process ACH" button
   - You'll be redirected to `/admin/ach`
   - See the created transactions!

## 📊 ACH Transaction Statuses

- **pending**: Transaction created, waiting to be sent
- **processing**: Transaction sent to processor, awaiting response
- **completed**: Transaction successfully processed
- **failed**: Transaction failed (e.g., insufficient funds, invalid account)
- **reversed**: Transaction was reversed

## 🔗 Navigation

After processing ACH, you can:
- View all transactions: `/admin/ach`
- View specific payroll run: `/admin/payroll/{id}`
- See transaction details in the ACH list

## 💡 Current Implementation Notes

- **Simulated Processing:** Transactions are immediately marked as "completed"
- **No Real Bank Integration:** This is a simulation for development
- **Production Ready:** Structure is in place for real ACH processor integration

## 🚀 Next Steps for Production

1. Integrate with real ACH processor (e.g., Plaid, Stripe, or bank API)
2. Implement webhook handlers for transaction status updates
3. Add retry logic for failed transactions
4. Implement bank account verification via micro-deposits
5. Add transaction reversal capabilities
