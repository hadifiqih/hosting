<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item menu-open">
        <a href="{{ url('/dashboard') }}" class="nav-link active">
          <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                Antrian
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('antrian.index') }}" class="nav-link {{ request()->routeIs('laporan.workshop') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Antrian Workshop</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('ringkasan.salesIndex') }}" class="nav-link {{ request()->routeIs('ringkasan.salesIndex') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ringkasan Penjualan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('omset.globalSales') }}" class="nav-link {{ request()->routeIs('omset.globalSales') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Omset Global Sales</p>
                </a>
            </li>
            <li>
                <a href="{{ route('omset.perCabang') }}" class="nav-link {{ request()->routeIs('omset.perCabang') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Omset Cabang</p>
                </a>
            </li>
            <li>
                <a href="{{ route('omset.perProduk') }}" class="nav-link {{ request()->routeIs('omset.perProduk') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Omset Produk</p>
                </a>
            </li>
            <li>
                <a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.index') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Data Pelanggan</p>
                </a>
            </li>
        </ul>
        </li>
    </ul>
</nav>