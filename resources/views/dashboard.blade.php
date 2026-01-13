<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PayVault Payroll</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            font-size: 24px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-info span {
            font-weight: 500;
        }
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .welcome-card p {
            color: #666;
            line-height: 1.6;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .info-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .info-card h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .info-card p {
            color: #666;
            font-size: 14px;
        }
        .api-info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .api-info h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .api-info code {
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PayVault Payroll</h1>
        <div class="user-info">
            <span>{{ Auth::user()->name }} ({{ Auth::user()->role ? Auth::user()->role->name : 'No Role' }})</span>
            <form method="POST" action="{{ route('web.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout" style="background: rgba(255, 255, 255, 0.2); color: white; border: 2px solid white; padding: 8px 20px; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 500; transition: background 0.3s;">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome-card">
            <h2>Welcome to PayVault Payroll!</h2>
            <p>You have successfully logged in. This is a basic dashboard. The full application functionality is available through the REST API endpoints.</p>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <h3>Your Role</h3>
                <p><strong>{{ ucfirst(Auth::user()->role ? Auth::user()->role->name : 'No Role') }}</strong></p>
                <p style="margin-top: 10px; font-size: 12px;">
                    @if(Auth::user()->role && Auth::user()->hasRole('admin'))
                        You have full system access.
                    @elseif(Auth::user()->role && Auth::user()->hasRole('client'))
                        You can manage your companies and payroll.
                    @else
                        You can view your payroll information.
                    @endif
                </p>
            </div>
            
            <div class="info-card">
                <h3>Email</h3>
                <p>{{ Auth::user()->email }}</p>
            </div>
            
            <div class="info-card">
                <h3>Account Created</h3>
                <p>{{ Auth::user()->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        
        <div class="api-info">
            <h3>API Access</h3>
            <p>To use the full API functionality, you'll need to:</p>
            <ol style="margin-left: 20px; margin-top: 10px; color: #856404;">
                <li>Login via API: <code>POST /api/login</code></li>
                <li>Get your JWT token from the response</li>
                <li>Use the token in API requests: <code>Authorization: Bearer YOUR_TOKEN</code></li>
            </ol>
            <p style="margin-top: 15px; color: #856404;">
                <strong>API Base URL:</strong> <code>{{ url('/api') }}</code>
            </p>
        </div>
    </div>
</body>
</html>
