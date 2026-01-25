<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('judul','Absensi')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link
      rel="icon"
      href="{{asset('assets/img/kaiadmin/Logo-bpom.png')}}"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
      <script>
        WebFont.load({
          google: { families: ["Public Sans:300,400,500,600,700"] },
          custom: {
            families: [
              "Font Awesome 5 Solid",
              "Font Awesome 5 Regular",
              "Font Awesome 5 Brands",
              "simple-line-icons",
            ],
            urls: ["{{asset('assets/css/fonts.min.css')}}"],
          },
          active: function () {
            sessionStorage.fonts = true;
          },
        });
      </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/kaiadmin.min.css')}}" />
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-p8f7Yk2ZQo3hP5EowP0M61xwBvKm0rR1j+dxGV+n2iyt3K6oAwNHK4l5Z18ldD/1p1M1K1fPZ9CtZ0E3d1gU0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> --}}


    <!-- CSS Just for demo purpose, don't include it in your project -->
    {{-- <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}" /> --}}
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      @include('admin.sidebar.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="#" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/Lambang_kota_kendari.png') }}" alt="navbar brand" class="navbar-brand" height="100" style="width: 50px; "/>
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
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" >
            <div class="container-fluid">
              <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <h3 class="text-dark"><b>SISTEM ABSENSI PEGAWAI</b></h3>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
               

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="assets/img/profile.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::user()->name ?? 'Guest' }}</h4>
                            <p class="text-muted">hello@example.com</p>
                            <a
                              href="profile.html"
                              class="btn btn-xs btn-success btn-sm"
                              >{{ Auth::user()->role_id }}</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                          <div class="dropdown-divider"></div>

                          <a class="dropdown-item" href="#">
                              My Profile
                          </a>

                          <div class="dropdown-divider"></div>

                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" class="dropdown-item">
                                  Logout
                              </button>
                          </form>
                      </li>

                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            @yield('content')
          </div>
        </div>

        {{-- Footer --}}
        @include('layouts.footer')
      </div>

  
{{-- TOAST CONTAINER --}}
<div class="toast-container position-fixed top-0 end-0 p-3 mt-3" style="z-index:1080"></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      @if(session('success'))
          showToast('{{ session('success') }}', 'success');
      @endif

      @if(session('comment'))
          showToast('{{ session('comment') }}', 'info');
      @endif
  });

function showToast(message, type = 'info') {
    const toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center border-0 shadow';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.setAttribute('data-bs-autohide', 'true');

    // SVG icon sesuai type
    let headerBg = 'bg-secondary text-white';
    let iconHtml = `
        <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Info:">
            <use xlink:href="#info-fill"/>
        </svg>
    `;

    if(type === 'success'){
        headerBg = 'bg-success text-white';
        iconHtml = `
            <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Success:">
                <use xlink:href="#check-circle-fill"/>
            </svg>
        `;
    } 
    else if(type === 'error'){
        headerBg = 'bg-danger text-white';
        iconHtml = `
            <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Error:">
                <use xlink:href="#exclamation-triangle-fill"/>
            </svg>
        `;
    }
    else if(type === 'warning'){
        headerBg = 'bg-warning text-dark';
        iconHtml = `
            <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Warning:">
                <use xlink:href="#exclamation-triangle-fill"/>
            </svg>
        `;
    }
    else if(type === 'info'){
        headerBg = 'bg-info text-white';
        iconHtml = `
            <svg class="bi flex-shrink-0 me-2" width="20" height="20" role="img" aria-label="Info:">
                <use xlink:href="#info-fill"/>
            </svg>
        `;
    }

    // HTML toast
    toastEl.innerHTML = `
        <div class="toast-header ${headerBg}">
            ${iconHtml}
            <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
            <button type="button" class="btn-close ${type==='warning'?'':'btn-close-white'}" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ${message}
        </div>
    `;

    document.querySelector('.toast-container').appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}
</script>

<!-- Include SVG icon Bootstrap -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08 0l3.992-3.992a.75.75 0 1 0-1.08-1.08L7.5 9.439 5.53 7.47a.75.75 0 1 0-1.06 1.06l2.5 2.5z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 0a8 8 0 1 0 8 8A8 8 0 0 0 8 0zM8 13a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm.93-9.412-.862.47a.5.5 0 0 0-.236.66l.356.852a.5.5 0 0 0 .66.236l.862-.47a.5.5 0 0 0 .236-.66l-.356-.852a.5.5 0 0 0-.66-.236z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.964 0L.165 13.233c-.457.778.091 1.767.982 1.767h13.706c.89 0 1.438-.99.982-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1-2.002 0 1 1 0 0 1 2.002 0z"/>
    </symbol>
</svg>






    <!--   Core JS Files   -->
    <script src="{{asset('assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{asset('assets/js/plugin/chart.js/chart.min.js')}}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle -->
    <script src="{{asset('assets/js/plugin/chart-circle/circles.min.js')}}"></script>

    <!-- Datatables -->
    <script src="{{asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{asset('assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{asset('assets/js/plugin/jsvectormap/world.js')}}"></script>

    <!-- Sweet Alert -->
    <script src="{{asset('assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{asset('assets/js/kaiadmin.min.js')}}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    {{-- <script src="assets/js/setting-demo.js"></script> --}}
    {{-- <script src="assets/js/demo.js"></script> --}}
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
  </body>
</html>
