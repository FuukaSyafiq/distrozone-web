<!DOCTYPE html>
<html>
<head>
    <title>Transaction List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Selected Transactions</h1>

    <table>
        <thead>
            <tr>
                <th>Tanggal Dibayar</th>
                <th>No Invoice</th>
                <th>Pengirim</th>
                <th>Kamar</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td>{{ $record['tanggal_dibayar'] }}</td>
                    <td>{{ $record['no_invoice'] }}</td>
                    <td>{{ $record['pengirim'] }}</td>
                    <td>{{ $record['kamar'] }}</td>
                    <td>Rp. {{ number_format($record['amount'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
