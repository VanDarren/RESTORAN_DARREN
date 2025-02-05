<!DOCTYPE html>
<html>
<head>
    <title>Nota Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; max-width: 400px; margin: auto; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #ddd; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Nota Transaksi</h3>
        <p>Kode Transaksi: {{ $transaksi->kode_transaksi }}</p>
        <p>Tanggal: {{ $transaksi->tanggal }}</p>
        <p>Kode Member: {{ $transaksi->kode_member ?? 'Tidak ada' }}</p>
        <p>Kode Voucher: {{ $transaksi->kode_voucher ?? 'Tidak ada' }}</p>

        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksi->menu as $menuItem)
                    <tr>
                        <td>{{ $menuItem->nama_menu }}</td>
                        <td>{{ $menuItem->qty }}</td>
                        <td>Rp {{ number_format($menuItem->price * $menuItem->qty, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Tidak ada menu yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p>Subtotal: Rp {{ number_format($transaksi->total, 0, ',', '.') }}</p>
        <p>Diskon: Rp {{ number_format($transaksi->discount, 0, ',', '.') }}</p>
        <h4>Total: Rp {{ number_format($transaksi->total_akhir, 0, ',', '.') }}</h4>
        <p>Bayar: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
        <p>Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>

        <p>Terima kasih telah berbelanja!</p>
    </div>
</body>
</html>
