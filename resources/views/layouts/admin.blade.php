<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KrisBite | Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- KrisBite Font: Inter (Consistent with Auth) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Admin Layout CSS -->
    <link href="{{ asset('css/admin/admin.css') }}" rel="stylesheet">

    <!-- Page-Specific CSS -->
    @stack('styles')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="admin-layout">

<div class="admin-wrapper d-flex">

    <!-- Sidebar -->
    <aside class="admin-sidebar">

        <div class="sidebar-brand">
            <h4>KRISBITE</h4>
            <span>ADMINISTRATION</span>
        </div>

        <div class="sidebar-divider"></div>

        <nav class="sidebar-navigation">
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.vendors.index') }}"
                       class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Manage Vendors</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.workers.index') }}"
                       class="nav-link {{ request()->routeIs('admin.workers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-check"></i>
                        <span>Manage Workers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settlements.index') }}"
                       class="nav-link {{ request()->routeIs('admin.settlements.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Settlements</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}"
                       class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i>
                        <span>Reports</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- Logout -->
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="post">
                @csrf

                <button type="submit" class="sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="admin-main">

        <!-- Top Navigation -->
        <nav class="admin-topbar">
            <span class="admin-topbar-title">Admin Panel</span>

            <div class="admin-user">
                <span>Welcome,</span>
                <strong>{{ Auth::user()->name }}</strong>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="admin-content">
            @yield('content')
        </main>

    </div>

</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>