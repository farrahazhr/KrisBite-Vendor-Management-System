@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="kb-auth-page kb-login-page" style="background-image: url('{{ asset('images/background_auth.png') }}');">

    <div class="kb-auth-card">

        <!-- PANEL KIRI (VISUAL & PENGENALAN) -->
        <div class="kb-visual-panel">
            <div class="kb-visual-top">
                <span class="kb-eyebrow">KRISBITE DIGITAL PLATFORM</span>
                <h1>Connecting Food, Vendors and Operations</h1>
            </div>

            <div class="kb-visual-bottom">
                <p>
                    From discovering daily menus to managing vendor operations,
                    KrisBite brings everything together in one place.
                </p>
            </div>
        </div>

        <!-- PANEL KANAN (BORANG LOG MASUK) -->
        <div class="kb-form-panel">

            <!-- LOGO HURUF K -->
            <div class="kb-logo-wrapper">
                <img src="{{ asset('images/krisbite_logo.png') }}" alt="KrisBite" class="kb-logo-img">
            </div>

            <div class="kb-form-heading">
                <h2>Welcome back</h2>
                <p>Sign in to your account to continue.</p>
            </div>

            @if (session('status'))
                <div class="kb-alert kb-alert-success" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- EMAIL -->
                <div class="kb-field">
                    <label for="email" class="form-label">
                        Email address
                    </label>

                    <div class="kb-input-wrapper">
                        <i class="bi bi-envelope kb-field-icon"></i>

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
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="kb-field">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="kb-input-wrapper">
                        <i class="bi bi-lock kb-field-icon"></i>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control kb-input @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >

                        <button type="button" class="kb-password-toggle" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="kb-error" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- REMEMBER ME -->
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

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="kb-submit">
                    Sign In
                </button>
            </form>

            @if (Route::has('register'))
                <div class="kb-register">
                    <div class="kb-register-item">
                        <span>Don't have an account?</span>
                        <a href="{{ route('register') }}" class="kb-link-accent">
                            Register as Public
                        </a>
                    </div>

                    <div class="kb-register-item">
                        <span>Looking for a job?</span>
                        <a href="{{ route('register.worker') }}" class="kb-link-secondary">
                            Register as Worker
                        </a>
                    </div>
                </div>
            @endif

            <!-- FOOTER -->
            <footer class="kb-footer">
                &copy; {{ date('Y') }} KrisBite. All rights reserved.
            </footer>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }
    });
</script>

@endsection