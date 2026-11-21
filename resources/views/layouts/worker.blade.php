
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KrisBite Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Existing KrisBite Styles -->
    <link href="{{ asset('css/krisbite-style.css') }}" rel="stylesheet">

    <!-- Worker Layout Styles -->
    <link href="{{ asset('css/worker/worker.css') }}" rel="stylesheet">

    <!-- Page Specific Styles -->
    @stack('styles')

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="worker-layout">

<div class="worker-app">

    <!-- SIDEBAR -->
    <aside class="worker-sidebar">

        <!-- BRAND -->
        <div class="worker-sidebar-brand">
            <h4>KRISBITE</h4>
            <span>WORKER PANEL</span>
        </div>

        <div class="worker-sidebar-divider"></div>

        <!-- NAVIGATION -->
        <nav class="worker-sidebar-navigation">

            <ul class="nav flex-column">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="{{ route('worker.dashboard') }}"
                       class="nav-link {{ request()->routeIs('worker.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- MANAGE MENU -->
                <li class="nav-item">
                    <a href="{{ route('worker.menu.index') }}"
                       class="nav-link {{ request()->routeIs('worker.menu.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Manage Menu</span>
                    </a>
                </li>

                <!-- STOCK TALLY -->
                <li class="nav-item">
                    <a href="{{ route('worker.stock.tally') }}"
                       class="nav-link {{ request()->routeIs('worker.stock.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Stock Tally</span>
                    </a>
                </li>

                <!-- SETTLEMENTS -->
                <li class="nav-item">
                    <a href="{{ route('worker.settlements.index') }}"
                       class="nav-link {{ request()->routeIs('worker.settlements.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-coin"></i>
                        <span>Settlements</span>
                    </a>
                </li>

                <!-- LOGOUT -->
                <li class="nav-item worker-logout-item">

                    <form action="{{ route('logout') }}" method="post">
                        @csrf

                        <button type="submit" class="nav-link worker-logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>

                </li>

            </ul>

        </nav>

    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="worker-main-wrapper">

        <!-- TOPBAR -->
        <header class="worker-topbar">

            <div class="worker-topbar-title">
                Worker Panel
            </div>

            <div class="worker-topbar-user">
                <span class="worker-welcome-text">Welcome,</span>
                <strong>{{ Auth::user()->name }}</strong>
            </div>

        </header>

        <!-- PAGE CONTENT -->
        <main class="worker-main-content">
            @yield('content')
        </main>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>