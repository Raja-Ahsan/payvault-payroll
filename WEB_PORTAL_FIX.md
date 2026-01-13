# Web Portal Fix Guide

## Quick Diagnosis Steps

### Step 1: Verify Server is Running
```bash
php artisan serve
```
You should see: `Laravel development server started: http://127.0.0.1:8000`

### Step 2: Test Routes
Open your browser and try these URLs:

1. **Homepage (should redirect to login):**
   ```
   http://localhost:8000/
   ```

2. **Login Page:**
   ```
   http://localhost:8000/login
   ```

3. **Register Page:**
   ```
   http://localhost:8000/register
   ```

### Step 3: Check for Errors

**If you see a blank page or error:**
1. Check the browser console (F12 → Console tab)
2. Check Laravel logs: `storage/logs/laravel.log`
3. Make sure MySQL is running in XAMPP

**If you see "Route not found":**
```bash
php artisan route:clear
php artisan optimize:clear
```

**If you see "View not found":**
- Verify these files exist:
  - `resources/views/auth/login.blade.php`
  - `resources/views/auth/register.blade.php`
  - `resources/views/dashboard.blade.php`

**If you see database errors:**
1. Start MySQL in XAMPP Control Panel
2. Verify database exists
3. Run migrations: `php artisan migrate`
4. Seed roles: `php artisan db:seed`

### Step 4: First Time Setup

If this is your first time using the web portal:

1. **Make sure database is set up:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

2. **Register your first user:**
   - Go to: `http://localhost:8000/register`
   - Fill in the form
   - Select a role (admin, client, or employee)
   - Submit

3. **Login:**
   - Go to: `http://localhost:8000/login`
   - Use the credentials you just created

## Common Error Messages

### "SQLSTATE[HY000] [2002] Connection refused"
- **Fix:** Start MySQL in XAMPP Control Panel

### "Class 'App\Http\Controllers\WebAuthController' not found"
- **Fix:** 
  ```bash
  composer dump-autoload
  php artisan optimize:clear
  ```

### "View [auth.login] not found"
- **Fix:** Verify file exists at `resources/views/auth/login.blade.php`

### "The GET method is not supported for route logout"
- **Fix:** Already fixed - logout route accepts both GET and POST

## Still Having Issues?

1. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   php artisan route:clear
   php artisan config:clear
   php artisan view:clear
   php artisan cache:clear
   ```

2. **Check Laravel version compatibility:**
   ```bash
   php artisan --version
   ```
   Should be Laravel 12.x

3. **Verify file permissions:**
   - `storage/` and `bootstrap/cache/` should be writable

4. **Check .env file:**
   - Make sure `APP_DEBUG=true` for development
   - Verify database settings match your XAMPP setup

## Test the Portal

After fixing issues, test the complete flow:

1. ✅ Visit `http://localhost:8000/` → Should redirect to login
2. ✅ Click "Register here" → Should show registration form
3. ✅ Fill form and submit → Should create user and redirect to dashboard
4. ✅ Dashboard should show your user info
5. ✅ Click "Logout" → Should log out and redirect to login

If all steps work, your web portal is functioning correctly!
