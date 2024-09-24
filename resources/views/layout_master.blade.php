<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-TMSC Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    @yield('css')
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="{{ route('dashboard.index') }}"><img
                        src="{{ asset('assets/images/logo.svg') }}" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini" href="{{ route('dashboard.index') }}"><img
                        src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
            </div>
            <ul class="nav">
                <li class="nav-item nav-category">
                    <span class="nav-link">Trang chủ</span>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('employee.index') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-account"></i>
                        </span>
                        <span class="menu-title">Quản lý nhân viên</span>
                    </a>
                </li>
                @php
                    $roleIt = session()->has('user') && session()->get('departmentId') == 3;
                    $roleHr = session()->has('user') && session()->get('departmentId') == 6;
                    $roleDirector = session()->has('user') && session()->get('departmentId') == 11;
                @endphp
                @if ($roleIt || $roleHr || $roleDirector)
                    <li class="nav-item menu-items">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-icon">
                                <i class="mdi mdi-qrcode-scan"></i>
                            </span>
                            <span class="menu-title">Quản lý chấm công</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link"
                                        href="{{ route('checkin.index') }}">Checkin</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link"
                                        href="{{ route('checkout.index') }}">Checkout</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if ($roleIt || $roleDirector || $roleHr)
                    <li class="nav-item menu-items">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="menu-icon">
                                <i class="mdi mdi-file-document"></i>
                            </span>
                            <span class="menu-title">Quản lý đơn từ</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ route('dayoff.index') }}">Xin nghỉ
                                        phép</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link" href="{{ route('overtime.index') }}">Xin tăng
                                        ca</a>
                                </li>
                                <li class="nav-item"> <a class="nav-link" href="{{ route('early-late.index') }}">Xin
                                        đến muộn/về sớm</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($roleIt || $roleDirector || $roleHr)
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="{{ route('notification.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-bell-ring"></i>
                            </span>
                            <span class="menu-title">Thông báo</span>
                        </a>
                    </li>
                @endif

                @if ($roleIt || $roleDirector || $roleHr)
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="{{ route('blog.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-blogger"></i>
                            </span>
                            <span class="menu-title">Quản lý bài viết</span>
                        </a>
                    </li>
                @endif

                @if ($roleIt || $roleDirector || $roleHr)
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="{{ route('test.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-animation"></i>
                            </span>
                            <span class="menu-title">Kiểm tra kiến thức</span>
                        </a>
                    </li>
                @endif

                @if ($roleIt || $roleDirector || $roleHr)
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="{{ route('salary.index') }}">
                            <span class="menu-icon">
                                <i class="mdi mdi-file-excel"></i>
                            </span>
                            <span class="menu-title">Nhập xuất lương</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item menu-items">
                    <a class="nav-link" href="{{ route('orders.index') }}">
                        <span class="menu-icon">
                            <i class="mdi mdi-speedometer"></i>
                        </span>
                        <span class="menu-title">Quản lý đơn hàng</span>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_navbar.html -->
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard.index') }}"><img
                            src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                                <div class="navbar-profile">
                                    @if (Session::has('image'))
                                        <img class="img-xs rounded-circle" src="{{ Session::get('image') }}"
                                            alt="tmsc">
                                    @endif
                                    @if (Session::has('fullname'))
                                        <p class="mb-0 d-none d-sm-block navbar-profile-name">
                                            {{ Session::get('fullname') }}</p>
                                    @endif
                                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="profileDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="{{ route('logout') }}">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Log out</p>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © IT
                            TMSC</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    @yield('scripts')
</body>

</html>
