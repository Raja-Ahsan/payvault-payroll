# How to Access PayVault Payroll Web Portal

## Problem
If you're seeing a 404 error or a different website, you're likely accessing XAMPP's Apache server instead of Laravel's development server.

## Solution: Use Laravel's Built-in Server

### Option 1: Use Laravel's Development Server (Recommended)

1. **Open Command Prompt or Terminal**
2. **Navigate to your project:**
   ```bash
   cd d:\xampp\htdocs\payvault-payroll
   ```

3. **Start Laravel's server:**
   ```bash
   php artisan serve
   ```
   
   You should see:
   ```
   INFO  Server running on [http://127.0.0.1:8000]
   ```

4. **Access the web portal:**
   - Login: http://127.0.0.1:8000/login
   - Register: http://127.0.0.1:8000/register
   - Homepage: http://127.0.0.1:8000/

### Option 2: Use a Different Port

If port 8000 is already in use:

```bash
php artisan serve --port=8001
```

Then access: http://127.0.0.1:8001/login

### Option 3: Configure XAMPP Virtual Host (Advanced)

If you want to use XAMPP's Apache:

1. **Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`**
   
   Add this configuration:
   ```apache
   <VirtualHost *:80>
       ServerName payvault-payroll.test
       DocumentRoot "D:/xampp/htdocs/payvault-payroll/public"
       <Directory "D:/xampp/htdocs/payvault-payroll/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **Edit `C:\Windows\System32\drivers\etc\hosts`** (Run as Administrator)
   
   Add this line:
   ```
   127.0.0.1    payvault-payroll.test
   ```

3. **Restart Apache in XAMPP**

4. **Access:** http://payvault-payroll.test/login

## Quick Test

1. Make sure you're in the project directory:
   ```bash
   cd d:\xampp\htdocs\payvault-payroll
   ```

2. Start the server:
   ```bash
   php artisan serve
   ```

3. Open browser and go to:
   ```
   http://127.0.0.1:8000/login
   ```

## Important Notes

- **DO NOT** use `http://localhost:8000` if XAMPP Apache is running on port 8000
- **USE** `http://127.0.0.1:8000` instead, or use Laravel's server on a different port
- Laravel's server must be running for the routes to work
- The server must be started from the project root directory

## Troubleshooting

### "Address already in use"
- Another server is using port 8000
- Solution: Use a different port: `php artisan serve --port=8001`

### "Command not found: php artisan"
- You're not in the project directory
- Solution: `cd d:\xampp\htdocs\payvault-payroll` first

### Still seeing 404
- Make sure Laravel server is running
- Check the terminal for the server URL
- Try: `php artisan route:list` to verify routes exist
