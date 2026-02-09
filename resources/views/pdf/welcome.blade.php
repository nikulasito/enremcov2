<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Welcome Certificate</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
    h1 { text-align:center; margin-bottom: 24px; }
    table { width:100%; border-collapse: collapse; }
    td { padding: 8px; border: 1px solid #ccc; }
    .label { width: 35%; background:#f7f7f7; font-weight:bold; }
  </style>
</head>
<body>
  <h1>Welcome to ENREMCO</h1>
  <table>
    <tr><td class="label">Name</td><td>{{ $user->name }}</td></tr>
    <tr><td class="label">Email</td><td>{{ $user->email }}</td></tr>
    <tr><td class="label">Registered</td><td>{{ now()->format('Y-m-d H:i') }}</td></tr>
  </table>
  <p style="margin-top:16px;">We’re glad you’re here!</p>
</body>
</html>
