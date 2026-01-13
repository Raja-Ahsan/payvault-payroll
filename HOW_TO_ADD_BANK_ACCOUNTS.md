# How to Fix "No ACH transactions created" - Bank Account Setup Guide

## Problem
When processing ACH, you get the message: **"No ACH transactions created. Employees may not have verified bank accounts."**

This happens because employees need bank accounts with specific requirements before ACH processing can work.

## Solution: Add and Verify Bank Accounts for Employees

### Requirements for ACH Processing
Each employee needs a bank account that meets ALL of these criteria:
1. ✅ **is_active = true** (Account is active)
2. ✅ **is_primary = true** (Account is marked as primary)
3. ✅ **verification_status = 'verified'** (Account is verified)

### Step-by-Step Instructions

#### For Clients:
1. **Go to Employee Details:**
   - Navigate to: `/client/employees`
   - Click on an employee to view details

2. **Add Bank Account:**
   - Scroll to the "Bank Accounts" section
   - Click "Add Bank Account" button
   - Fill in the form:
     - Bank Name (e.g., "Chase Bank")
     - Account Holder Name (e.g., "John Doe")
     - Account Type (Checking or Savings)
     - Routing Number (9 digits)
     - Account Number
     - Check "Set as Primary Account" if this is the employee's main account
   - Click "Add Bank Account"

3. **Verify Bank Account:**
   - After adding, you'll see the bank account with a "Pending" status
   - Click the "Verify" button next to the bank account
   - The status will change to "Verified" (green badge)

4. **Process ACH:**
   - Once the bank account is verified, go back to the payroll run
   - Click "Process ACH"
   - ACH transactions will now be created successfully!

#### For Admins:
Same process, but navigate to `/admin/employees` instead.

### Quick Test Setup (Using Database)

If you want to quickly test without using the UI, you can insert a bank account directly:

```sql
-- Replace {employee_id} with an actual employee ID
INSERT INTO bank_accounts (
    accountable_type, 
    accountable_id, 
    account_type, 
    bank_name, 
    account_holder_name, 
    routing_number, 
    account_number, 
    verification_status, 
    is_primary, 
    is_active,
    created_at,
    updated_at
) VALUES (
    'App\\Models\\Employee', 
    {employee_id}, 
    'checking', 
    'Test Bank', 
    'John Doe', 
    '123456789', 
    '987654321', 
    'verified', 
    true, 
    true,
    NOW(),
    NOW()
);
```

### What Happens During ACH Processing

1. System checks each employee in the payroll run
2. For each employee, it looks for a bank account that is:
   - Active (`is_active = true`)
   - Primary (`is_primary = true`)
   - Verified (`verification_status = 'verified'`)
3. If found → Creates an ACH transaction
4. If not found → Skips that employee (logs a warning)

### Troubleshooting

**Issue:** "No ACH transactions created"
- **Check:** Does the employee have a bank account?
- **Check:** Is the bank account marked as primary?
- **Check:** Is the bank account verified?
- **Check:** Is the bank account active?

**Issue:** Bank account shows "Pending" status
- **Solution:** Click the "Verify" button to verify the account

**Issue:** Multiple bank accounts, which one is used?
- **Solution:** Only the account marked as `is_primary = true` will be used for ACH

### Notes

- **Verification:** Currently, verification is simulated (instant). In production, this would integrate with a bank verification service.
- **Security:** Bank account numbers and routing numbers should be encrypted in production.
- **Primary Account:** Only one account per employee should be marked as primary. When you add a new primary account, the old one is automatically unset.
