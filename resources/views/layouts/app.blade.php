<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- plugins:css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/chartist/chartist.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-light-layout/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <h4>ADMIN PORTAL</h4>
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          <h5 class="mb-0 font-weight-medium d-none d-lg-flex">Welcome {{ auth()->user()->getRoleNames()->first() }} Dashboard!</h5>
          <ul class="navbar-nav navbar-nav-right">
            <form class="search-form d-none d-md-block" action="#">
              <i class="icon-magnifier"></i>
              <input type="search" class="form-control" placeholder="Search Here" title="Search here">
            </form>
            <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user()->photo)
                        <?php $photo = Auth::user()->photo; ?>
                        <img class="img-md rounded-circle" src="{{ asset('storage/images/' . $photo) }}" alt="Profile image" style="width: 60px; height: 60px; object-fit: cover;">
                    @endif <span class="font-weight-normal">{{ Auth::user()->full_name }}</span></a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <p class="mb-1 mt-3">{{ Auth::user()->full_name }}</p>
                  <p class="font-weight-light text-muted mb-0">{{ Auth::user()->email }}</p>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a href="#" class="dropdown-item"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="dropdown-item-icon icon-power text-primary"></i> Sign Out
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item navbar-brand-mini-wrapper">
              <a class="nav-link navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
            </li>
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="profile-image">
                    @if(Auth::user()->photo)
                        <?php $photo = Auth::user()->photo; ?>
                        <img class="img-md rounded-circle" src="{{ asset('storage/images/' . $photo) }}" alt="Profile image" style="width: 60px; height: 60px; object-fit: cover;">
                    @endif
                  <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                  <p class="profile-name">{{ Auth::user()->name }}</p>
                  <p class="designation">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
              </a>
            </li>
            <li class="nav-item nav-category">
              <span class="nav-link">Dashboard</span>
            </li>
            @if(auth()->user()->hasRole('System Admin'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('users.index') }}">
                <span class="menu-title">User Accounts</span>
                <i class="fa fa-users menu-icon"></i>
              </a>
            </li>
            @endif
            @if(auth()->user()->hasRole('System Admin')||auth()->user()->hasRole('HR'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('hr.index') }}">
                <span class="menu-title">HR</span>
                <i class="fa fa-user-circle menu-icon"></i>
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{ route('profile.edit') }}">
                <span class="menu-title">Employee Profile</span>
                <i class="fa fa-user-o menu-icon"></i>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="menu-title">Quit</span>
                    <i class="fa fa-power-off menu-icon"></i>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
                @yield('content')
                {{ $slot ?? '' }}
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <!-- End custom js for this page -->
  </body>
</html>