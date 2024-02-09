<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
    <li class="nav-item menu-open">
      <a href="{{ url('/dashboard') }}" class="nav-link active">
        <i class="nav-icon fas fa-tachometer-alt"></i>
          <p>
              Iklan
              <i class="right fas fa-angle-left"></i>
          </p>
      </a>
      <ul class="nav nav-treeview">
          <li class="nav-item">
              <a href="{{ route('iklan.index') }}" class="nav-link {{ request()->routeIs('iklan.index') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Iklan</p>
              </a>
          </li>
          <li class="nav-item">
              <a href="{{ route('iklan.create') }}" class="nav-link {{ request()->routeIs('iklan.create') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Iklan</p>
              </a>
          </li>
      </ul>
    </li>
  </ul>
</nav>