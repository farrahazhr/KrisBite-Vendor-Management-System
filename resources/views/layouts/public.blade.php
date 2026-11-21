
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KrisBite Public</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Existing KrisBite CSS -->
    <link href="{{ asset('css/krisbite-style.css') }}" rel="stylesheet">

    <!-- Public Layout CSS -->
    <link href="{{ asset('css/public/public.css') }}" rel="stylesheet">

    <!-- Page Specific CSS -->
    @stack('styles')

</head>

<body class="krisbite-public-layout">

    <!-- =========================================
         TOP NAVBAR - FULL WIDTH
    ========================================== -->

    <nav class="navbar navbar-expand-lg public-navbar">

        <div class="container-fluid public-navbar-container">

            <!-- BRAND -->
            <a class="navbar-brand public-navbar-brand"
               href="{{ route('public.menu') }}">
                KRISBITE
            </a>

            <!-- USER & LOGOUT -->
            <div class="d-flex align-items-center public-navbar-actions">

                <span class="navbar-text public-navbar-welcome">
                    Welcome,
                    <b>{{ Auth::user()->name }}</b>
                </span>

                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="post">
                    @csrf

                    <button type="submit"
                            class="btn btn-danger btn-sm public-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </nav>

    <!-- =========================================
         MAIN CONTENT - FULL WIDTH
    ========================================== -->

    <main class="public-main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>