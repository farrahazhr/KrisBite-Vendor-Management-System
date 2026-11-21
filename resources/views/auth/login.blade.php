
@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="kb-auth-page kb-login-page">

    <!-- HEADER -->
    <header class="kb-header">

        <div class="kb-brand">
            <i class="bi bi-building"></i>
            <span>KrisBite</span>
        </div>

        <nav class="kb-navigation" aria-label="Authentication navigation">

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="kb-nav-link">
                    Register
                </a>
            @endif

            <a href="{{ route('login') }}"
               class="kb-nav-link kb-nav-active"
               aria-current="page">
                Login
            </a>

        </nav>

    </header>

    <!-- INTRODUCTION -->
    <section class="kb-introduction">

        <span class="kb-eyebrow">
            KRISBITE DIGITAL PLATFORM
        </span>

        <h1>Making Food Experiences Simpler.</h1>

        <p>
            From discovering daily menus to managing vendor operations,
            KrisBite brings everything together in one place.
        </p>

    </section>

    <!-- MAIN AUTHENTICATION CARD -->
    <main class="kb-auth-card">

        <!-- LEFT: IMAGE -->
        <div class="kb-image-panel">

            <img
                src="{{ asset('images/loginNregister_image.png') }}"
                alt="KrisBite promotional banner"
                class="kb-banner-image"
            >

        </div>

        <!-- RIGHT: LOGIN FORM -->
        <section class="kb-form-panel">

            <div class="kb-form-content">

                <div class="kb-form-heading">
                    <h2>Welcome back</h2>
                    <p>Sign in to your account to continue.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="kb-field">

                        <label for="email" class="form-label">
                            Email address
                        </label>

                        <div class="kb-input-wrapper">

                            <i class="bi bi-envelope"></i>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control kb-input @error('email') is-invalid @enderror"
                                placeholder="Enter your email"
                                required
                                autocomplete="username"
                                autofocus
                            >

                        </div>

                        @error('email')
                            <div class="kb-error" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <!-- PASSWORD -->
                    <div class="kb-field">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="kb-input-wrapper">

                            <i class="bi bi-lock"></i>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control kb-input @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                            >

                        </div>

                        @error('password')
                            <div class="kb-error" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <!-- OPTIONS -->
                    <div class="kb-form-options">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>

                        </div>

                    </div>

                    <!-- SUBMIT -->
                    <button type="submit" class="kb-submit">

                        Sign In
                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>

                @if (Route::has('register'))

                    <div class="kb-register">

                        <span>Don't have an account?</span>

                        <a href="{{ route('register') }}">
                            Register as Public
                        </a>

                        <div>
                            <span>Looking for a job?</span>

                            <a href="{{ route('register.worker') }}">
                                Register as Worker
                            </a>
                        </div>

                    </div>

                @endif

            </div>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="kb-footer">
        © {{ date('Y') }} KrisBite. All rights reserved.
    </footer>

</div>

@endsection