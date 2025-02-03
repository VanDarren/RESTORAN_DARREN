<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <h2 class="mb-2 page-title">Data Menu & Paket Makanan</h2>
        <p class="card-text">Berikut adalah daftar menu dan paket makanan.</p>

        <!-- Buttons -->
        <div class="mb-3">
          <button class="btn btn-primary" data-toggle="modal" data-target="#tambahMenuModal">Tambah Menu</button>
          <a href="" class="btn btn-warning">Restore Deleted Menu</a>
          <a href="" class="btn btn-success">Restore Updated Menu</a>
        </div>

        <!-- Filter Kategori -->
        <div class="mb-3">
          <label for="filterKategori">Filter Kategori:</label>
          <select id="filterKategori" class="form-control" onchange="filterMenu()">
            <option value="">Semua</option>
            <option value="Makanan">Makanan</option>
            <option value="Minuman">Minuman</option>
            <option value="Dessert">Dessert</option>
            <option value="Paket">Paket</option>
          </select>
        </div>

        <!-- DataTable -->
        <div class="card shadow mt-3">
          <div class="card-body">
            <table class="table datatables" id="menuTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Foto</th>
                  <th>Nama Menu</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Harga</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @php $no = 1; @endphp
                @foreach ($menus as $menu)
                  <tr data-kategori="{{ $menu->kategori }}">
                    <td>{{ $no++ }}</td>
                    <td>
                      <img src="{{ asset('menu/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="img-thumbnail" width="80" height="80">
                    </td>
                    <td>{{ $menu->nama_menu }}</td>
                    <td>{{ $menu->kategori }}</td>
                    <td>{{ $menu->deskripsi }}</td>
                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                    <td>
  <button class="btn btn-warning btn-sm editMenuBtn" 
          data-id="{{ $menu->id_menu }}" 
          data-nama="{{ $menu->nama_menu }}" 
          data-kategori="{{ $menu->kategori }}" 
          data-deskripsi="{{ $menu->deskripsi }}" 
          data-harga="{{ $menu->harga }}" 
          data-foto="{{ $menu->foto }}">
    Edit
  </button>
  <form action="{{ route('hapusmenu', $menu ->id_menu) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Delete</button>
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

  <!-- Modal Tambah Menu -->
  <div class="modal fade" id="tambahMenuModal" tabindex="-1" role="dialog" aria-labelledby="tambahMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahMenuModalLabel">Tambah Menu Baru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('tambahmenu') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="namaMenu">Nama Menu</label>
              <input type="text" class="form-control" id="namaMenu" name="nama_menu" required>
            </div>

            <div class="form-group">
              <label for="kategori">Kategori</label>
              <select class="form-control" id="kategori" name="kategori" required>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Dessert">Dessert</option>
                <option value="Paket">Paket</option>
              </select>
            </div>

            <div class="form-group">
              <label for="deskripsi">Deskripsi</label>
              <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>

            <div class="form-group">
              <label for="harga">Harga</label>
              <input type="number" class="form-control" id="harga" name="harga" required>
            </div>

            <div class="form-group">
              <label for="foto">Foto Menu</label>
              <input type="file" class="form-control-file" id="foto" name="foto" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Tambah Menu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Menu -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('editmenu') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="editMenuId" name="id_menu">
        <div class="modal-body">
          <div class="form-group">
            <label for="editNamaMenu">Nama Menu</label>
            <input type="text" class="form-control" id="editNamaMenu" name="nama_menu" required>
          </div>

          <div class="form-group">
            <label for="editKategori">Kategori</label>
            <select class="form-control" id="editKategori" name="kategori" required>
              <option value="Makanan">Makanan</option>
              <option value="Minuman">Minuman</option>
              <option value="Dessert">Dessert</option>
              <option value="Paket">Paket</option>
            </select>
          </div>

          <div class="form-group">
            <label for="editDeskripsi">Deskripsi</label>
            <textarea class="form-control" id="editDeskripsi" name="deskripsi" rows="3" required></textarea>
          </div>

          <div class="form-group">
            <label for="editHarga">Harga</label>
            <input type="number" class="form-control" id="editHarga" name="harga" required>
          </div>

          <div class="form-group">
            <label for="editFoto">Foto Menu (Opsional)</label>
            <input type="file" class="form-control-file" id="editFoto" name="foto">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

</main>

<script>
  $(document).on('click', '.editMenuBtn', function() {
  var menuId = $(this).data('id');
  var menuNama = $(this).data('nama');
  var menuKategori = $(this).data('kategori');
  var menuDeskripsi = $(this).data('deskripsi');
  var menuHarga = $(this).data('harga');
  var menuFoto = $(this).data('foto');
  
  // Isi modal dengan data yang ada
  $('#editMenuId').val(menuId);
  $('#editNamaMenu').val(menuNama);
  $('#editKategori').val(menuKategori);
  $('#editDeskripsi').val(menuDeskripsi);
  $('#editHarga').val(menuHarga);
  // Foto tidak diisi jika tidak ada foto baru, tetap menampilkan foto lama
  if (menuFoto) {
    $('#editFoto').attr('data-foto-lama', menuFoto);
  }
  
  // Tampilkan modal
  $('#editMenuModal').modal('show');
});


  $(document).ready(function() {
    $('#menuTable').DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
      }
    });
  });

  function filterMenu() {
    var kategori = document.getElementById("filterKategori").value;
    $("#menuTable tbody tr").each(function() {
      var rowKategori = $(this).data("kategori");
      if (kategori === "" || rowKategori === kategori) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  }
</script>
