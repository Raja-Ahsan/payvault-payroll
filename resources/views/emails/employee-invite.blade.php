<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Invite</title>
</head>
<body style="margin:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="620" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;">
                    <tr>
                        <td align="center" style="padding:22px 20px 10px;">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="max-width:180px;height:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 4px;">
                            <h1 style="margin:0;font-size:24px;line-height:1.3;color:#111827;">You have been invited</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 0;font-size:15px;line-height:1.7;color:#374151;">
                            <p style="margin:0 0 14px;">
                                <strong>{{ $company->company_name }}</strong> has created an employee account for you on
                                <strong>{{ config('app.name') }}</strong>.
                            </p>
                            <p style="margin:0 0 16px;">
                                Use the credentials below to sign in. Please change your password after logging in.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 14px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;">
                                <tr>
                                    <td style="padding:14px 16px;font-size:14px;line-height:1.8;color:#111827;">
                                        <div><strong>Login:</strong> <a href="{{ $loginUrl }}" style="color:#2563eb;">{{ $loginUrl }}</a></div>
                                        <div><strong>Email:</strong> {{ $user->email }}</div>
                                        <div><strong>Temporary password:</strong> <code style="background:#eef2ff;padding:2px 6px;border-radius:4px;">{{ $plainPassword }}</code></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:6px 28px 18px;">
                            <a href="{{ $loginUrl }}" style="display:inline-block;background:#2563eb;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:6px;font-size:14px;font-weight:600;">
                                Sign in
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 20px;font-size:13px;line-height:1.7;color:#6b7280;">
                            If you were not expecting this message, you can ignore it or contact your employer.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 28px;border-top:1px solid #e5e7eb;font-size:13px;color:#6b7280;">
                            Thanks, DIY Payroll Solutions
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
