<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Rating</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center;">Laporan Kepuasan Pelanggan</h3>
    <p>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Area</th>
                <th>Very Bad</th>
                <th>Bad</th>
                <th>Neutral</th>
                <th>Good</th>
                <th>Very Good</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->area }}</td>
                    <td>{{ $row->very_bad }}</td>
                    <td>{{ $row->bad }}</td>
                    <td>{{ $row->neutral }}</td>
                    <td>{{ $row->good }}</td>
                    <td>{{ $row->very_good }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
