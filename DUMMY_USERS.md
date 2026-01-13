# Dummy Users for Testing

## Login Credentials

### 1. Admin User
- **Email:** `admin@payvault.com`
- **Password:** `password123`
- **Role:** Admin
- **Access:** Full system access, can manage all companies and payroll runs

### 2. Client User
- **Email:** `client@payvault.com`
- **Password:** `password123`
- **Role:** Client
- **Access:** Can manage their own companies, employees, and payroll runs

### 3. Employee User
- **Email:** `employee@payvault.com`
- **Password:** `password123`
- **Role:** Employee
- **Access:** Can view their own payroll history and pay stubs

## How to Login

### Via Web Portal:
1. Start Laravel server: `php artisan serve`
2. Go to: http://127.0.0.1:8000/login
3. Enter any of the credentials above

### Via API:
```bash
POST http://127.0.0.1:8000/api/login
Content-Type: application/json

{
  "email": "admin@payvault.com",
  "password": "password123"
}
```

## Re-seed Users

If you need to recreate the users:

```bash
php artisan db:seed --class=UserSeeder
```

Or to seed everything (roles + users):

```bash
php artisan db:seed
```

## Security Note

⚠️ **These are dummy users for development only!**
- Change passwords in production
- Use strong, unique passwords
- Never commit real credentials to version control
