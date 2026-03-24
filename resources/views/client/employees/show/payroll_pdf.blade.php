<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Paystub — {{ $employee->employee_id ?? $employee->id }}</title>
    <style>
        body {
            margin: 24px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
        }
    </style>
</head>
<body>
    @include('client.employees.partials.paystub_pdf')
</body>
</html>
