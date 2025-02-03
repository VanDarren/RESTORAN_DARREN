<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="mb-2 page-title">Data Membership</h2>
        <p class="card-text">Berikut adalah daftar member beserta informasi kode membership dan masa berlaku.</p>
        
        <!-- Buttons -->
        <div class="mb-3">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#tambahMemberModal">Tambah Member</a>
          <a href="" class="btn btn-warning">Restore Expired Member</a>
        </div>

        <!-- DataTable -->
        <div class="card shadow mt-3">
          <div class="card-body">
            <table class="table datatables" id="memberTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kode Membership</th>
                  <th>Nama</th>
                  <th>No. HP</th>
                  <th>Email</th>
                  <th>Masa Berlaku</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 1; @endphp
                @foreach ($members as $member)
                  <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $member->kode }}</td>
                    <td>{{ $member->nama }}</td>
                    <td>{{ $member->nohp }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ date('d-m-Y', strtotime($member->exp)) }}</td>
                    <td>
                      @if (strtotime($member->exp) < strtotime(now()))
                        <span class="badge badge-danger">Expired</span>
                      @else
                        <span class="badge badge-success">Active</span>
                      @endif
                    </td>
                    <td>
                    <button class="btn btn-warning btn-sm editMemberBtn" 
        data-toggle="modal" 
        data-target="#editMemberModal"
        data-id="{{ $member->id_member }}" 
        data-kode="{{ $member->kode }}" 
        data-nama="{{ $member->nama }}" 
        data-nohp="{{ $member->nohp }}" 
        data-email="{{ $member->email }}" 
        data-exp="{{ $member->exp }}">
  Edit
</button>

  <form action="{{ route('hapusmember', $member ->id_member) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus memeber ini?')">Delete</button>
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

  <!-- Modal Tambah Member -->
<div class="modal fade" id="tambahMemberModal" tabindex="-1" role="dialog" aria-labelledby="tambahMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahMemberModalLabel">Tambah Member Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('tambahmember') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>

          <div class="form-group">
            <label for="nohp">No. HP</label>
            <input type="text" class="form-control" id="nohp" name="nohp" required>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <div class="form-group">
            <label for="exp">Masa Berlaku</label>
            <input type="date" class="form-control" id="exp" name="exp" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Tambah Member</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Member -->
<div class="modal fade" id="editMemberModal" tabindex="-1" role="dialog" aria-labelledby="editMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('editmember') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="editMemberId" name="id_member">
        <div class="modal-body">
          <div class="form-group">
            <label for="editNama">Nama</label>
            <input type="text" class="form-control" id="editNama" name="nama" required>
          </div>

          <div class="form-group">
            <label for="editNohp">No. HP</label>
            <input type="text" class="form-control" id="editNohp" name="nohp" required>
          </div>

          <div class="form-group">
            <label for="editEmail">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" required>
          </div>

          <div class="form-group">
            <label for="editExp">Masa Berlaku</label>
            <input type="date" class="form-control" id="editExp" name="exp" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Update Member</button>
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
    $('.editMemberBtn').click(function() {
      var id = $(this).data('id');
      var kode = $(this).data('kode');
      var nama = $(this).data('nama');
      var nohp = $(this).data('nohp');
      var email = $(this).data('email');
      var exp = $(this).data('exp');
      
      // Isi field-form dengan data member yang dipilih
      $('#editMemberId').val(id);
      $('#editNama').val(nama);
      $('#editNohp').val(nohp);
      $('#editEmail').val(email);
      $('#editExp').val(exp);
    });
  });

  $(document).ready(function() {
    $('#memberTable').DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
      }
    });
  });
</script>
