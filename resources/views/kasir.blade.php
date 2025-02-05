<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row">
      <!-- Panel Kiri: Pilih Menu -->
      <div class="col-md-6">
        <h4 class="mb-3">Pilih Menu</h4>

        <!-- Dropdown Kategori -->
        <select id="kategoriMenu" class="form-control mb-3">
          <option value="">Semua Kategori</option>
          <option value="Makanan">Makanan</option>
          <option value="Minuman">Minuman</option>
          <option value="Dessert">Dessert</option>
          <option value="Paket">Paket</option>
        </select>

        <!-- List Menu dalam Bentuk Box -->
        <div class="row" id="menuList">
          @foreach ($menus as $menu)
            <div class="col-md-4 mb-3 menu-item" data-category="{{ $menu->kategori }}">
              <div class="card h-100 shadow-sm text-center">
                <img src="{{ asset('menu/' . $menu->foto) }}" class="card-img-top img-menu" alt="{{ $menu->nama_menu }}">
                <div class="card-body">
                  <h6 class="card-title">{{ $menu->nama_menu }}</h6>
                  <p class="card-text text-muted">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                  <button class="btn btn-primary btn-sm add-menu"
                    data-id="{{ $menu->id_menu }}"
                    data-name="{{ $menu->nama_menu }}"
                    data-price="{{ $menu->harga }}">
                    Tambah
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Panel Kanan: Daftar Pesanan -->
      <div class="col-md-6">
        <h4 class="mb-3">Daftar Pesanan</h4>
        <table class="table">
          <thead>
            <tr>
              <th>Menu</th>
              <th>Harga</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="cartBody">
            <!-- Item pesanan akan ditambahkan di sini -->
          </tbody>
        </table>

        <h5>Subtotal: Rp <span id="subtotal">0</span></h5>

        <!-- Input Voucher -->
        <div class="form-group">
          <label for="voucherCode">Kode Voucher:</label>
          <input type="text" id="voucherCode" class="form-control">
        </div>

        <!-- Input Membership -->
        <div class="form-group">
          <label for="membershipCode">Kode Membership:</label>
          <input type="text" id="membershipCode" class="form-control">
        </div>

        <!-- Tampilkan Diskon -->
        <h5>Diskon: Rp <span id="discount">0</span></h5>

        <!-- Total Setelah Diskon -->
        <h4>Total: Rp <span id="total">0</span></h4>

        <!-- Input Pembayaran -->
        <div class="form-group">
          <label for="bayar">Bayar:</label>
          <input type="number" id="bayar" class="form-control" min="0">
        </div>

        <!-- Kembalian -->
        <h5>Kembalian: Rp <span id="kembalian">0</span></h5>

        <!-- Form Transaksi -->
        <form id="formTransaksi" action="{{ route('prosesTransaksi') }}" method="POST">
  @csrf
  <input type="hidden" name="tanggal" id="tanggal">
  <input type="hidden" name="kode_transaksi" id="kodeTransaksi">
  <input type="hidden" name="kode_member" id="kodeMember">
  <input type="hidden" name="kode_voucher" id="kodeVoucher">
  <input type="hidden" name="menu" id="menu">
  <input type="hidden" name="qty" id="qtyField">
  <input type="hidden" name="subtotal" id="subtotalField">
  <input type="hidden" name="discount" id="discountField">
  <input type="hidden" name="total_akhir" id="totalField">
  <input type="hidden" name="bayar" id="bayarField">
  <input type="hidden" name="kembalian" id="kembalianField">

  <button type="submit" class="btn btn-success btn-block">Proses Pembayaran</button>
</form>

      </div>
    </div>
  </div>
</main>

<style>
  .img-menu {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 5px 5px 0 0;
  }

  .menu-item {
    display: block;
  }

  .card-title {
    font-size: 14px;
    font-weight: bold;
  }

  .card-text {
    font-size: 12px;
    color: #777;
  }
</style>

<script>
  let cart = [];
  let subtotal = 0;
  let totalDiscount = 0;  // Total diskon (dari membership + voucher)

  // Tambah item ke daftar pesanan
  $('.add-menu').click(function() {
    let id = $(this).data('id');
    let name = $(this).data('name');
    let price = parseFloat($(this).data('price'));

    let item = cart.find(i => i.id === id);
    if (item) {
      item.qty += 1;
      item.total = item.qty * item.price;
    } else {
      cart.push({ id, name, price, qty: 1, total: price });
    }

    updateCart();
  });

  function updateCart() {
    let cartBody = $('#cartBody');
    cartBody.empty();
    subtotal = 0;
    let menuData = [];  // Array untuk menyimpan menu dan qty
    let qtyArray = [];  // Array untuk qty

    cart.forEach((item, index) => {
        subtotal += item.total;
        cartBody.append(`
            <tr>
                <td>${item.name}</td>
                <td>Rp ${item.price.toLocaleString()}</td>
                <td>${item.qty}</td>
                <td>Rp ${item.total.toLocaleString()}</td>
                <td><button class="btn btn-danger btn-sm remove-item" data-index="${index}">X</button></td>
            </tr>
        `);

        // Simpan item menu dan qty dalam array
        menuData.push({ id: item.id, name: item.name, qty: item.qty, price: item.price });
        qtyArray.push(item.qty);  // Simpan qty dalam array
    });

    applyDiscount();

    // Update hidden input field untuk menu dan qty
    $('#menu').val(JSON.stringify(menuData)); // Pastikan menu dalam format JSON
    $('#qtyField').val(qtyArray.join(','));  // Set qty ke hidden input dengan implode
    $('#subtotalField').val(subtotal);  // Set subtotal ke hidden input
}


  // Hapus item dari daftar pesanan
  $(document).on('click', '.remove-item', function() {
    let index = $(this).data('index');
    cart.splice(index, 1);
    updateCart();
  });

  // Terapkan diskon berdasarkan membership dan voucher
  function applyDiscount() {
    let membershipCode = $('#membershipCode').val();
    let voucherCode = $('#voucherCode').val();
    totalDiscount = 0;  // Reset total diskon

    // Cek Membership
    if (membershipCode) {
      $.ajax({
        url: "{{ route('checkMembership') }}",
        type: "POST",
        data: { kode: membershipCode, _token: "{{ csrf_token() }}" },
        success: function(response) {
          if (response.valid) {
            totalDiscount += subtotal * 0.10; // Diskon 10% untuk membership
          }
          checkVoucher();
        }
      });
    } else {
      checkVoucher(); 
    }
  }

  // Cek voucher dari database
  function checkVoucher() {
    let voucherCode = $('#voucherCode').val();

    if (voucherCode) {
      $.ajax({
        url: "{{ route('checkVoucher') }}",
        type: "POST",
        data: { kode_voucher: voucherCode, _token: "{{ csrf_token() }}" },
        success: function(response) {
          if (response.valid) {
            totalDiscount += subtotal * (response.discount / 100); // Diskon dari voucher
          }
          updateTotal();
        }
      });
    } else {
      updateTotal();
    }
  }

  // Update total pembayaran
  function updateTotal() {
    let total = subtotal - totalDiscount;
    $('#subtotal').text(subtotal.toLocaleString());
    $('#discount').text(totalDiscount.toLocaleString());
    $('#total').text(total.toLocaleString());
  }

  // Cek diskon ketika input berubah
  $('#membershipCode, #voucherCode').on('input', function() {
    applyDiscount();
  });

  // Filter menu berdasarkan kategori
  $('#kategoriMenu').change(function() {
    let selectedCategory = $(this).val();

    $('.menu-item').each(function() {
      if (selectedCategory === "" || $(this).data('category') === selectedCategory) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });

  $('#formTransaksi').submit(function(event) {
    event.preventDefault(); 

    let bayar = parseFloat($('#bayar').val()) || 0;
    let totalAkhir = parseFloat($('#total').text().replace(/[^\d.-]/g, '')) || 0;

    if (bayar < totalAkhir) {
        alert("Jumlah bayar kurang dari total!");
        return;
    }

    let kembalian = bayar - totalAkhir;
    $('#kembalian').text(kembalian.toLocaleString());

    let formData = {
        tanggal: new Date().toISOString().split('T')[0],
        kode_member: $('#membershipCode').val(),
        kode_voucher: $('#voucherCode').val(),
        menu: $('#menu').val(), // Pastikan JSON terkirim
        subtotal: parseFloat($('#subtotal').text().replace(/[^\d.-]/g, '')) || 0,
        discount: parseFloat($('#discount').text().replace(/[^\d.-]/g, '')) || 0,
        total_akhir: totalAkhir,
        bayar: bayar,
        kembalian: kembalian,
        _token: "{{ csrf_token() }}"
    };

    $.ajax({
        url: "{{ route('prosesTransaksi') }}",
        type: "POST",
        data: formData,
        success: function(response) {
            if (response.success) {
                alert("Transaksi berhasil!");
                // Redirect ke halaman nota setelah transaksi berhasil
                window.location.href = "{{ url('nota') }}/" + response.kode_transaksi;
            } else {
                alert("Terjadi kesalahan: " + response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert("Gagal memproses transaksi.");
        }
    });
});




</script>
