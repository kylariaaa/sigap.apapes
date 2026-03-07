<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan</title>

    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

<h2 style="text-align:center;">REKAPITULASI LAPORAN PENGADUAN</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Pelapor</th>
            <th>Isi Laporan</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($reports as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>

            <td>{{ $item->created_at->format('d/m/Y') }}</td>

            <td>{{ $item->user->name ?? 'Tidak diketahui' }}</td>

            <td>{{ $item->description }}</td>

            <td>{{ ucfirst($item->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>