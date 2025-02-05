<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .container { width: 100%; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>Nota Transaksi</h2>
    <h3>Kode: {{ $transaksi->kode_transaksi }}</h3>
    <p>Tanggal: {{ $transaksi->tanggal }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $menuList = $transaksi->menu; // jika menu sudah array
$qtyList = $transaksi->qty;   // jika qty sudah array

            @endphp
            
            @foreach ($menuList as $index => $menu)
                <tr>
                    <td>{{ $menu }}</td>
                    <td>{{ $qtyList[$index] }}</td>
                    <td>Rp {{ number_format($hargaList[$index], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($qtyList[$index] * $hargaList[$index], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Subtotal: Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</p>
    <p class="total">Diskon: Rp {{ number_format($transaksi->discount, 0, ',', '.') }}</p>
    <p class="total">Total Akhir: Rp {{ number_format($transaksi->total_akhir, 0, ',', '.') }}</p>
    <p class="total">Bayar: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
    <p class="total">Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>

    <p style="text-align: center; margin-top: 20px;">Terima kasih telah berbelanja!</p>
</div>

</body>
</html>
