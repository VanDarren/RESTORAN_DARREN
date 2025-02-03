<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="mb-2 page-title">Data Voucher</h2>
        <p class="card-text">Berikut adalah daftar voucher beserta informasi diskon dan masa berlakunya.</p>
        
        <!-- Buttons -->
        <div class="mb-3">
          <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahVoucherModal">Tambah Voucher</a>
          <a href="" class="btn btn-warning">Restore Expired Voucher</a>
        </div>

        <!-- DataTable -->
        <div class="card shadow mt-3">
          <div class="card-body">
            <table class="table datatables" id="voucherTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kode Voucher</th>
                  <th>Diskon</th>
                  <th>Masa Berlaku</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 1; @endphp
                @foreach ($vouchers as $voucher)
                  <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $voucher->kode_voucher }}</td>
                    <td>{{ $voucher->discount }}%</td>
                    <td>{{ date('d-m-Y', strtotime($voucher->exp_voucher)) }}</td>
                    <td>
                      @if (strtotime($voucher->exp_voucher) < strtotime(now()))
                        <span class="badge badge-danger">Expired</span>
                      @else
                        <span class="badge badge-success">Active</span>
                      @endif
                    </td>
                    <td>
                      <!-- Edit Button -->
                      <button class="btn btn-warning btn-sm editVoucherBtn"
                              data-toggle="modal" 
                              data-target="#editVoucherModal"
                              data-id="{{ $voucher->id_voucher }}"
                              data-kode="{{ $voucher->kode_voucher }}"
                              data-diskon="{{ $voucher->discount }}"
                              data-exp="{{ $voucher->exp_voucher }}">
                        Edit
                      </button>
                      <!-- Delete Form -->
                      <form action="{{ route('hapusvoucher', $voucher->id_voucher) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">Delete</button>
                      </form>
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
  <!-- Modal Tambah Voucher -->
<div class="modal fade" id="tambahVoucherModal" tabindex="-1" role="dialog" aria-labelledby="tambahVoucherModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahVoucherModalLabel">Tambah Voucher Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('tambahvoucher') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="kodeVoucher">Kode Voucher</label>
            <input type="text" class="form-control" id="kodeVoucher" name="kode_voucher" required>
          </div>

          <div class="form-group">
            <label for="diskon">Diskon (%)</label>
            <input type="number" class="form-control" id="diskon" name="discount" required>
          </div>

          <div class="form-group">
            <label for="expVoucher">Masa Berlaku</label>
            <input type="date" class="form-control" id="expVoucher" name="exp_voucher" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah Voucher</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Edit Voucher -->
<div class="modal fade" id="editVoucherModal" tabindex="-1" role="dialog" aria-labelledby="editVoucherModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVoucherModalLabel">Edit Voucher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('editvoucher') }}" method="POST">
        @csrf
        <input type="hidden" id="editVoucherId" name="id_voucher">
        <div class="modal-body">
          <div class="form-group">
            <label for="editKodeVoucher">Kode Voucher</label>
            <input type="text" class="form-control" id="editKodeVoucher" name="kode_voucher" required>
          </div>

          <div class="form-group">
            <label for="editDiskon">Diskon (%)</label>
            <input type="number" class="form-control" id="editDiskon" name="discount" required>
          </div>

          <div class="form-group">
            <label for="editExpVoucher">Masa Berlaku</label>
            <input type="date" class="form-control" id="editExpVoucher" name="exp_voucher" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Update Voucher</button>
        </div>
      </form>
    </div>
  </div>
</div>

</main>


<!-- Tambahkan Script DataTables -->
<script>
  $(document).ready(function() {
    // Fungsi untuk mengisi data ke dalam modal edit
    $('.editVoucherBtn').click(function() {
      var id = $(this).data('id');
      var kode = $(this).data('kode');
      var diskon = $(this).data('diskon');
      var exp = $(this).data('exp');
      
      // Isi field-form dengan data voucher yang dipilih
      $('#editVoucherId').val(id);
      $('#editKodeVoucher').val(kode);
      $('#editDiskon').val(diskon);
      $('#editExpVoucher').val(exp);
    });
  });

  $(document).ready(function() {
    $('#voucherTable').DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
      }
    });
  });
</script>
