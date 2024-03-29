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
                <a href="{{ route('antrian.index') }}" class="nav-link {{ request()->routeIs('antrian.index') || request()->routeIs('antrian.edit') || request()->routeIs('antrian.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>List Pekerjaan</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('laporan.workshop') }}" class="nav-link {{ request()->routeIs('laporan.workshop') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Workshop</p>
                </a>
            </li>
        </ul>
        </li>
    </ul>
</nav>