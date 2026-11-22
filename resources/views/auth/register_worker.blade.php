@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="kb-auth-page kb-register-page kb-worker-page" style="background-image: url('{{ asset('images/background_auth.png') }}');">

    <div class="kb-auth-card kb-worker-card">

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

        <!-- PANEL KANAN (BORANG DAFTAR WORKER) -->
        <div class="kb-form-panel">

            <!-- LOGO HURUF K -->
            <div class="kb-logo-wrapper">
                <img src="{{ asset('images/krisbite_logo.png') }}" alt="KrisBite" class="kb-logo-img">
            </div>

            <div class="kb-form-heading">
                <h2>Register as Worker</h2>
                <p>Fill in your details to apply as a KrisBite worker.</p>
            </div>

            <!-- SESSION ERROR -->
            @if (session('error'))
                <div class="kb-alert kb-alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.worker.store') }}" novalidate>
                @csrf

                <!-- FULL NAME -->
                <div class="kb-field">
                    <label for="name" class="form-label">
                        Full name
                    </label>

                    <div class="kb-input-wrapper">
                        <i class="bi bi-person kb-field-icon"></i>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control kb-input @error('name') is-invalid @enderror"
                            placeholder="Enter your full name"
                            required
                            autocomplete="name"
                            autofocus
                        >
                    </div>

                    @error('name')
                        <div class="kb-error" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

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
                            placeholder="Create a password"
                            required
                            autocomplete="new-password"
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

                <!-- CONFIRM PASSWORD -->
                <div class="kb-field">
                    <label for="password-confirm" class="form-label">
                        Confirm password
                    </label>

                    <div class="kb-input-wrapper">
                        <i class="bi bi-shield-lock kb-field-icon"></i>

                        <input
                            id="password-confirm"
                            type="password"
                            name="password_confirmation"
                            class="form-control kb-input"
                            placeholder="Confirm your password"
                            required
                            autocomplete="new-password"
                        >
                    </div>
                </div>

                <!-- TERMS & CONDITIONS -->
                <div class="kb-worker-terms">
                    <h3>Terms & Conditions</h3>

                    <p>
                        <strong>1. Account Approval:</strong>
                        All worker registrations are subject to review and approval by the KrisBite Admin team. The admin will notify you through WhatsApp once your account is approved. Until then, you will not have access to the system.
                    </p>

                    <p>
                        <strong>2. Data & Privacy:</strong>
                        Your personal information will be used solely for account management and operational purposes. KrisBite is committed to protecting your data and privacy in accordance with our policies.
                    </p>
                </div>

                <!-- AGREEMENT CHECKBOX -->
                <div class="kb-worker-agreement">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="terms"
                            id="terms"
                            required
                            {{ old('terms') ? 'checked' : '' }}
                        >

                        <label class="form-check-label" for="terms">
                            I agree to the Terms & Conditions.
                        </label>
                    </div>

                    @error('terms')
                        <div class="kb-error" role="alert">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="kb-submit">
                    Register Worker
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="kb-register">
                <div class="kb-register-item">
                    <span>Already have an account?</span>
                    <a href="{{ route('login') }}" class="kb-link-accent">
                        Sign In
                    </a>
                </div>
            </div>

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