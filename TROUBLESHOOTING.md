# Troubleshooting Web Portal Issues

## Common Issues and Solutions

### 1. "Route not found" or 404 errors

**Solution:**
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

### 2. "View not found" errors

**Check:**
- Views exist in `resources/views/auth/` and `resources/views/`
- Files are named correctly: `login.blade.php`, `register.blade.php`, `dashboard.blade.php`

**Solution:**
```bash
php artisan view:clear
```

### 3. Database connection errors

**Check:**
- MySQL is running in XAMPP
- Database `payvault_payroll` exists
- `.env` file has correct database credentials:
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3307
  DB_DATABASE=payvault_payroll
  DB_USERNAME=root
  DB_PASSWORD=
  ```

**Solution:**
- Start MySQL in XAMPP Control Panel
- Verify database exists: `php artisan migrate:status`

### 4. "Class not found" or "Controller not found" errors

**Solution:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### 5. Session errors

**Check:**
- `storage/framework/sessions` directory exists and is writable
- Session driver in `.env` is set to `database` or `file`

**Solution:**
```bash
php artisan session:table  # If using database sessions
php artisan migrate
```

### 6. Authentication not working

**Check:**
- Roles are seeded: `php artisan db:seed`
- User has a role assigned

**Solution:**
- Register a new user through `/register`
- Or manually assign role to existing user

### 7. Server not responding

**Check:**
- Server is running: `php artisan serve`
- Port 8000 is not in use
- Firewall is not blocking

**Solution:**
```bash
php artisan serve --port=8000
# Or use a different port
php artisan serve --port=8080
```

## Testing the Web Portal

1. **Start the server:**
   ```bash
   php artisan serve
   ```

2. **Access the login page:**
   ```
   http://localhost:8000/login
   ```

3. **Register a new user:**
   ```
   http://localhost:8000/register
   ```

4. **Check routes:**
   ```bash
   php artisan route:list
   ```

## Quick Fix Commands

Run these commands in sequence to clear all caches:

```bash
php artisan optimize:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear
composer dump-autoload
```

## Still Not Working?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Check browser console for JavaScript errors
4. Verify PHP version: `php -v` (should be 8.2+)
5. Check if all migrations are run: `php artisan migrate:status`
