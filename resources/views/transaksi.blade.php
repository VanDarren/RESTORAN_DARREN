<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="mb-2 page-title">Data Transaksi</h2>
        <p class="card-text">Berikut adalah daftar transaksi yang tercatat di sistem.</p>

        <!-- DataTable Transaksi -->
        <div class="card shadow mt-3">
          <div class="card-body">
            <table class="table datatables" id="transactionTable">
              <thead>
                <tr>
                  <th>Tanggal</th>
                  <th>Kode Transaksi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($transaksis as $transaksi)
                  <tr>
                    <td>{{ $transaksi->tanggal }}</td>
                    <td>{{ $transaksi->kode_transaksi }}</td>
                    <td>
                      <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#transactionModal"
                              data-tanggal="{{ $transaksi->tanggal }}"
                              data-kode_transaksi="{{ $transaksi->kode_transaksi }}"
                              data-kode_member="{{ $transaksi->kode_member ?? '-' }}"
                              data-kode_voucher="{{ $transaksi->kode_voucher ?? '-' }}"
                              data-menu="{{ $transaksi->menu }}"
                              data-total="{{ $transaksi->total }}"
                              data-discount="{{ $transaksi->discount }}"
                              data-total_akhir="{{ $transaksi->total_akhir }}"
                              data-bayar="{{ $transaksi->bayar }}"
                              data-kembalian="{{ $transaksi->kembalian }}">
                        Detail
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Detail Transaksi -->
  <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transactionModalLabel">Detail Transaksi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <p><strong>Tanggal:</strong> <span id="modalTanggal"></span></p>
              <p><strong>Kode Transaksi:</strong> <span id="modalKodeTransaksi"></span></p>
              <p><strong>Kode Member:</strong> <span id="modalKodeMember"></span></p>
              <p><strong>Kode Voucher:</strong> <span id="modalKodeVoucher"></span></p>
              <p><strong>Menu:</strong></p>
              <textarea id="modalMenu" class="form-control" rows="5" readonly></textarea>
              <p><strong>Total:</strong> Rp <span id="modalTotal"></span></p>
              <p><strong>Diskon:</strong> Rp <span id="modalDiscount"></span></p>
              <p><strong>Total Akhir:</strong> Rp <span id="modalTotalAkhir"></span></p>
              <p><strong>Bayar:</strong> Rp <span id="modalBayar"></span></p>
              <p><strong>Kembalian:</strong> Rp <span id="modalKembalian"></span></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  // Fungsi untuk mengambil data transaksi yang disimpan dalam data-* atribut dan menampilkan di modal
  $('#transactionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Tombol Detail
    var modal = $(this);

    // Ambil data transaksi dari atribut data-* pada tombol
    modal.find('#modalTanggal').text(button.data('tanggal'));
    modal.find('#modalKodeTransaksi').text(button.data('kode_transaksi'));
    modal.find('#modalKodeMember').text(button.data('kode_member'));
    modal.find('#modalKodeVoucher').text(button.data('kode_voucher'));
    modal.find('#modalMenu').val(button.data('menu'));
    modal.find('#modalTotal').text(button.data('total').toLocaleString());
    modal.find('#modalDiscount').text(button.data('discount').toLocaleString());
    modal.find('#modalTotalAkhir').text(button.data('total_akhir').toLocaleString());
    modal.find('#modalBayar').text(button.data('bayar').toLocaleString());
    modal.find('#modalKembalian').text(button.data('kembalian').toLocaleString());
  });
</script>

