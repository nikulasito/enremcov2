<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .print-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registration Preview</h1>
        <button class="print-btn" onclick="window.print()">Print</button>

        <table>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $user->address }}</td>
            </tr>
            <tr>
                <th>Contact No.</th>
                <td>{{ $user->contact_no }}</td>
            </tr>
            <tr>
                <th>Office</th>
                <td>{{ $user->office }}</td>
            </tr>
            <tr>
                <th>Section</th>
                <td>{{ $user->section }}</td>
            </tr>
            <tr>
                <th>Position</th>
                <td>{{ $user->position }}</td>
            </tr>
            <tr>
                <th>SG Level</th>
                <td>{{ $user->sg_level }}</td>
            </tr>
            <tr>
                <th>Shares</th>
                <td>{{ $user->shares }}</td>
            </tr>
            <tr>
                <th>Savings</th>
                <td>{{ $user->savings }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
