# Setup Instructions

## Quick Start

1. **Start MySQL** in XAMPP Control Panel
2. **Verify database exists**: The database `payvault_payroll` should already be created
3. **Run migrations**:
   ```bash
   php artisan migrate
   ```
4. **Seed roles**:
   ```bash
   php artisan db:seed
   ```
5. **Start the server**:
   ```bash
   php artisan serve
   ```

## Database Configuration

The `.env` file is already configured with:
- Database: `payvault_payroll`
- Host: `127.0.0.1`
- Port: `3306`
- Username: `root`
- Password: (empty by default in XAMPP)

If your MySQL configuration is different, update the `.env` file accordingly.

## Testing the API

Once the server is running, you can test the API endpoints using:

- **Postman**
- **cURL**
- **Thunder Client** (VS Code extension)
- Any HTTP client

### Example: Register an Admin User

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Admin User",
    "email": "admin@payvault.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "admin"
  }'
```

### Example: Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@payvault.com",
    "password": "password123"
  }'
```

Save the token from the response and use it in subsequent requests:

```bash
curl -X GET http://localhost:8000/api/companies \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Project Structure

```
payvault-payroll/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── CompanyController.php
│   │   │   ├── EmployeeController.php
│   │   │   ├── PayrollController.php
│   │   │   └── AchController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Company.php
│   │   ├── Employee.php
│   │   ├── PayrollRun.php
│   │   ├── PayrollItem.php
│   │   ├── PayrollDeduction.php
│   │   ├── BankAccount.php
│   │   ├── AchTransaction.php
│   │   └── AuditLog.php
│   └── Services/
│       ├── PayrollService.php
│       └── AchService.php
├── database/
│   ├── migrations/
│   │   └── (all migration files)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── RoleSeeder.php
└── routes/
    └── api.php
```

## Next Steps

1. Start MySQL in XAMPP
2. Run migrations and seeders
3. Test the API endpoints
4. Begin frontend development (React/Next.js recommended)
5. Integrate with actual ACH processor for production
6. Add comprehensive tax calculation services

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP
- Verify database `payvault_payroll` exists
- Check `.env` file database credentials

### JWT Token Issues
- JWT secret is already generated
- If needed, regenerate with: `php artisan jwt:secret`

### Migration Errors
- Ensure all previous migrations are run
- Check database permissions
- Verify table doesn't already exist
