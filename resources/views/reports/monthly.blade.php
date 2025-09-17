<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan - {{ $month }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .note {
            margin-top: 20px;
            font-size: 11px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h2>Laporan Bulanan</h2>
    <p><strong>Bulan:</strong> {{ $month }}</p>

    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Kode Kredit</th>
                <th>Tanggal Bayar</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($payments as $pay)
            @php
            $total += $pay->amount;
            $status = $pay->amount > 0 ? 'LUNAS' : 'BELUM LUNAS';
            @endphp
            <tr>
                <td>{{ $pay->customer->name }}</td>
                <td>{{ $pay->credit->code }}</td>
                <td>{{ $pay->paid_at ? \Illuminate\Support\Carbon::parse($pay->paid_at)->format('d-m-Y') : '-' }}</td>
                <td>Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                <td>{{ $status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada pembayaran bulan ini</td>
            </tr>
            @endforelse
            <tr class="total">
                <td colspan="4">Total</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="note">
        Catatan: Status “LUNAS” menandakan pembayaran untuk bulan ini sudah dipenuhi. Jika “BELUM LUNAS”, masih ada tunggakan pada bulan ini.
    </p>
</body>

</html>