<body class="vertical light">
  <div class="wrapper">
    <nav class="topnav navbar navbar-light">
      <!-- Top Navigation Bar (Tetap sama seperti sebelumnya) -->
    </nav>
    <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
      <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
        <i class="fe fe-x"><span class="sr-only"></span></i>
      </a>
      <nav class="vertnav navbar navbar-light">
        <!-- Logo -->
        <div class="w-100 mb-4 d-flex">
          <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('img/' . $darren2->iconmenu) }}" alt="IconMenu" class="logo-dashboard img-fit-menu">
          </a>
        </div>

        <!-- Main Menu -->
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" aria-expanded="false" class="nav-link">
              <i class="fe fe-home fe-16"></i>
              <span class="ml-3 item-text">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('kasir') }}" aria-expanded="false" class="nav-link">
              <i class="fe fe-shopping-cart fe-16"></i>
              <span class="ml-3 item-text">Kasir</span>
            </a>
          </li>
        </ul>

        <!-- Menu Data -->
        <p class="text-muted nav-heading mt-4 mb-1"><span>Data</span></p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item">
            <a href="{{ route('menus') }}" class="nav-link">
              <i class="fe fe-list fe-16"></i>
              <span class="ml-3 item-text">Menu</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('member') }}" class="nav-link">
              <i class="fe fe-users fe-16"></i>
              <span class="ml-3 item-text">Member</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('voucher') }}" class="nav-link">
              <i class="fe fe-tag fe-16"></i>
              <span class="ml-3 item-text">Voucher</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('transaksi') }}" class="nav-link">
              <i class="fe fe-dollar-sign fe-16"></i>
              <span class="ml-3 item-text">Transaksi</span>
            </a>
          </li>
        </ul>

        <!-- Menu Pengaturan -->
        <p class="text-muted nav-heading mt-4 mb-1"><span>Pengaturan</span></p>
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item">
            <a href="{{ route('setting') }}" aria-expanded="false" class="nav-link">
              <i class="fe fe-settings fe-16"></i>
              <span class="ml-3 item-text">Setting</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="" aria-expanded="false" class="nav-link">
              <i class="fe fe-activity fe-16"></i>
              <span class="ml-3 item-text">Log Activity</span>
            </a>
          </li>
        </ul>

        <!-- Logout -->
        <ul class="navbar-nav flex-fill w-100 mb-2">
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="fe fe-log-out fe-16"></i>
              <span class="ml-3 item-text">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>

    <style>
      .logo-dashboard {
        max-width: 100%; /* Membuat gambar tidak lebih besar dari kontainer */
        height: auto; /* Mempertahankan rasio gambar */
        display: block;
      }

      .img-fit-menu {
        width: 200px; /* Sesuaikan ukuran yang diinginkan untuk menu */
        height: 100px; /* Sesuaikan tinggi yang diinginkan untuk menu */
        object-fit: contain; /* Memastikan gambar pas tanpa terpotong */
        margin: 0 auto; /* Center image jika diperlukan */
      }

      /* Tambahkan style ini untuk membuat tulisan menu menjadi bold */
      .item-text {
        font-weight: bold; /* Membuat teks menjadi bold */
      }
    </style>
  </div>
</body>