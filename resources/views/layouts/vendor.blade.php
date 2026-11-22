<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KrisBite Vendor Panel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts - Inter Only (Consistent with Auth & Admin) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor Layout CSS -->
    <link href="{{ asset('css/vendor/vendor.css') }}" rel="stylesheet">

    <!-- Page Specific CSS -->
    @stack('styles')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="vendor-layout">

    <!-- =====================================
         SIDEBAR
    ====================================== -->

    <aside class="vendor-sidebar">

        <!-- Brand -->
        <div class="vendor-sidebar-brand">
            <h4>KRISBITE</h4>
            <span>Vendor Management System</span>
        </div>

        <!-- Navigation -->
        <nav class="vendor-sidebar-nav">

            <a href="{{ route('vendor.dashboard') }}"
               class="vendor-nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">

                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>

            </a>

            <a href="{{ route('vendor.foods.index') }}"
               class="vendor-nav-link {{ request()->routeIs('vendor.foods.*') ? 'active' : '' }}">

                <i class="bi bi-card-list"></i>
                <span>Manage Foods</span>

            </a>

            <a href="{{ route('vendor.invoice.create') }}"
               class="vendor-nav-link {{ request()->routeIs('vendor.invoice.*') ? 'active' : '' }}">

                <i class="bi bi-receipt"></i>
                <span>Submit Invoice</span>

            </a>

            <a href="{{ route('vendor.settlements.index') }}"
               class="vendor-nav-link {{ request()->routeIs('vendor.settlements.*') ? 'active' : '' }}">

                <i class="bi bi-wallet2"></i>
                <span>Payment Receipts</span>

            </a>

        </nav>

        <!-- Logout -->
        <div class="vendor-sidebar-footer">

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="vendor-logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>

            </form>

        </div>

    </aside>

    <!-- =====================================
         MAIN AREA
    ====================================== -->

    <div class="vendor-main-wrapper">

        <!-- Topbar -->
        <header class="vendor-topbar">

            <span class="vendor-panel-title">
                Vendor Panel
            </span>

            <div class="vendor-topbar-user">
                Welcome, <strong>{{ Auth::user()->name }}</strong>
            </div>

        </header>

        <!-- Main Content -->
        <main class="vendor-content">
            @yield('content')
        </main>

    </div>

</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>