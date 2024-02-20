<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
      <li class="nav-item menu-open">
        <a href="" class="nav-link active">
          <i class="nav-icon fas fa-camera"></i>
            <p>
                Dokumentasi
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('documentation.index') }}" class="nav-link {{ request()->routeIs('documentation.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Unggah Dokumentasi</p>
                </a>
            </li>
        </ul>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('documentation.gallery') }}" class="nav-link {{ request()->routeIs('documentation.gallery') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Galeri Dokumentasi</p>
                </a>
            </li>
        </ul>
        </li>
    </ul>
</nav>