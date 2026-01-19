<div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
              <img
                src="{{ asset('assets/img/kaiadmin/Lambang_kota_kendari.png') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="50"
                
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
               <li class="nav-item {{Request::is('dashboard') ? 'active' : ''}}">
                <a href="{{route('dashboard')}}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  {{-- <span class="badge badge-secondary">1</span> --}}
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Master Data</h4>
              </li>
              <li class="nav-item {{Request::is('employees') ? 'active' : ''}}">
                <a href="{{route('employees.index')}}">
                  <i class="fas fa-users"></i>
                  <p>Data Pegawai</p>
                  {{-- <span class="badge badge-secondary">1</span> --}}
                </a>
              </li>
              <li class="nav-item {{ Request::is('dataabsensi') ? 'active' : '' }}">
                <a href="{{ route('absensi.dataabsensi') }}">
                  <i class="fas fa-calendar-check"></i>
                  <p>Data Absensi</p>
                  {{-- <span class="badge badge-secondary">1</span> --}}
                </a>
              </li>
              <li class="nav-item {{ Request::is('absensi.izin') ? 'active' : '' }}">
                <a href="{{ route('izin.index') }}">
                  <i class="fas fa-envelope"></i>
                  <p>Data Izin</p>
                  {{-- <span class="badge badge-secondary">1</span> --}}
                </a>
              </li>
              <li class="nav-item {{Request::is('datalokasi') ? 'active' : ''}}">
                <a href="{{route('datalokasi')}}">
                  <i class="fas fa-map"></i>
                  <p>Data Lokasi</p>
                  {{-- <span class="badge badge-secondary">1</span> --}}
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Absensi</h4>
              </li>
                <li class="nav-item {{Request::is('absensi') ? 'active' : ''}}">
                    <a href="{{route('absensi.index')}}">
                    <i class="fas fa-user"></i>
                    <p>Absensi</p>
                    {{-- <span class="badge badge-secondary">1</span> --}}
                    </a>
                </li>
            <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Manajemen User</h4>
            </li>
             <li class="nav-item {{ Request::is(['kategori.employee.index', 'roles.index', 'users.index']) ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#base">
                  <i class="fas fa-bars"></i>
                  <p>Manajemen User</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="{{ route('users.index') }}">
                        <span class="sub-item">User</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('roles.index') }}">
                        <span class="sub-item">Role</span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('kategori.employee.index') }}">
                        <span class="sub-item">Kategori</span>
                      </a>
                    </li>
                   
                  </ul>
                </div>
              </li>
              <li class="nav-item {{Request::is('activity-logs') ? 'active' : ''}}">
                    <a href="{{ route('activity-logs.index') }}">
                    <i class="fas fa-history"></i>
                    <p>Log Aktifitas</p>
                    {{-- <span class="badge badge-secondary">1</span> --}}
                    </a>
                </li>
            </ul>
          </div>
        </div>
      </div>