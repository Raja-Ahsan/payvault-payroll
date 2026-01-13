# PayVault Payroll Web Application

A comprehensive payroll management system built with Laravel and MySQL, featuring multi-company support, employee management, payroll processing, and ACH payment integration.

## Features

- **Authentication & Authorization**: JWT-based authentication with role-based access control (Admin, Client, Employee)
- **Company Management**: Multi-company support with company profiles and settings
- **Employee Management**: Employee onboarding, tax information, and bank account details
- **Payroll Engine**: Automated payroll calculation with deductions and tax handling
- **Payroll Runs**: Complete payroll lifecycle (Draft → Preview → Approved → Finalized)
- **ACH Payment Processing**: Bank account verification and direct deposit distribution
- **Audit Logging**: Comprehensive activity tracking for compliance

## Technology Stack

- **Backend**: Laravel 12
- **Database**: MySQL
- **Authentication**: JWT (tymon/jwt-auth)
- **API**: RESTful APIs

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- XAMPP (or similar local development environment)

## Installation

1. **Clone the repository** (or navigate to the project directory)

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure environment**:
   - The `.env` file is already configured with:
     - Database: `payvault_payroll`
     - Database connection: MySQL
     - JWT secret is already generated

4. **Run migrations**:
   ```bash
   php artisan migrate
   ```

5. **Seed the database** (creates default roles):
   ```bash
   php artisan db:seed
   ```

6. **Start the development server**:
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000`

## Database Structure

### Core Tables
- `users` - User accounts with role-based access
- `roles` - User roles (admin, client, employee)
- `companies` - Company profiles and settings
- `employees` - Employee information and tax details
- `payroll_runs` - Payroll run records with status tracking
- `payroll_items` - Individual employee payroll calculations
- `payroll_deductions` - Deduction details (401k, insurance, etc.)
- `bank_accounts` - Bank account information (encrypted)
- `ach_transactions` - ACH transaction records
- `audit_logs` - Activity audit trail

## API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login and get JWT token
- `POST /api/logout` - Logout (invalidate token)
- `GET /api/me` - Get authenticated user
- `POST /api/refresh` - Refresh JWT token

### Companies
- `GET /api/companies` - List companies
- `POST /api/companies` - Create company
- `GET /api/companies/{id}` - Get company details
- `PUT /api/companies/{id}` - Update company
- `DELETE /api/companies/{id}` - Delete company

### Employees
- `GET /api/employees` - List employees
- `POST /api/employees` - Create employee
- `GET /api/employees/{id}` - Get employee details
- `PUT /api/employees/{id}` - Update employee
- `DELETE /api/employees/{id}` - Delete employee
- `GET /api/companies/{company}/employees` - Get employees by company

### Payroll
- `GET /api/payroll-runs` - List payroll runs
- `POST /api/payroll-runs` - Create payroll run
- `GET /api/payroll-runs/{id}` - Get payroll run details
- `PUT /api/payroll-runs/{id}` - Update payroll run
- `DELETE /api/payroll-runs/{id}` - Delete payroll run
- `POST /api/payroll-runs/{id}/calculate` - Calculate payroll
- `POST /api/payroll-runs/{id}/approve` - Approve payroll run
- `POST /api/payroll-runs/{id}/finalize` - Finalize payroll run

### ACH
- `GET /api/ach-transactions` - List ACH transactions
- `GET /api/ach-transactions/{id}` - Get transaction details
- `POST /api/payroll-runs/{id}/process-ach` - Process ACH for payroll run

## Usage Examples

### Register a User
```bash
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "admin"
}
```

### Login
```bash
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

### Create a Company (requires authentication)
```bash
POST /api/companies
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Acme Corporation",
  "legal_name": "Acme Corporation Inc.",
  "ein": "12-3456789",
  "address": "123 Main St",
  "city": "New York",
  "state": "NY",
  "zip_code": "10001",
  "phone": "555-1234",
  "email": "info@acme.com"
}
```

### Create Payroll Run
```bash
POST /api/payroll-runs
Authorization: Bearer {token}
Content-Type: application/json

{
  "company_id": 1,
  "pay_period_type": "biweekly",
  "pay_period_start": "2024-01-01",
  "pay_period_end": "2024-01-14",
  "pay_date": "2024-01-15"
}
```

### Calculate Payroll
```bash
POST /api/payroll-runs/1/calculate
Authorization: Bearer {token}
```

## Payroll Process Flow

1. **Create Payroll Run** - Admin/Client creates a new payroll run (status: `draft`)
2. **Calculate Payroll** - System calculates payroll for all active employees (status: `preview`)
3. **Review & Approve** - Admin/Client reviews and approves the payroll (status: `approved`)
4. **Finalize** - Payroll is finalized and locked (status: `finalized`)
5. **Process ACH** - ACH transactions are created and processed for direct deposits

## Role-Based Access Control

- **Admin**: Full system access, can manage all companies and payroll runs
- **Client**: Can manage their own companies, employees, and payroll runs
- **Employee**: Can view their own payroll history and pay stubs

## Security Features

- JWT token-based authentication
- Password hashing (bcrypt)
- Role-based access control
- Encrypted sensitive data (bank account numbers, SSN)
- Audit logging for compliance

## Development Notes

- The payroll calculation engine includes simplified tax calculations. In production, integrate with actual tax tables and services.
- ACH processing is simulated. In production, integrate with an actual ACH processor (e.g., Plaid, Stripe, or bank APIs).
- Bank account verification is simulated. In production, use micro-deposits or instant verification services.

## Future Enhancements

- Frontend application (React/Next.js)
- Real-time notifications
- PDF pay stub generation
- Advanced reporting and analytics
- Tax API integrations
- Mobile app support
- Multi-currency support

## License

This project is proprietary software.

## Support

For issues or questions, please contact the development team.
