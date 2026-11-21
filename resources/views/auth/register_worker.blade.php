
@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="kb-auth-page kb-register-page kb-worker-page">

    <!-- HEADER -->
    <header class="kb-header">

        <div class="kb-brand">
            <i class="bi bi-building"></i>
            <span>KrisBite</span>
        </div>

        <nav class="kb-navigation" aria-label="Authentication navigation">

            <a href="{{ route('register') }}" class="kb-nav-link">
                Register as Public
            </a>

            <a href="{{ route('login') }}" class="kb-nav-link">
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

        <!-- RIGHT: WORKER REGISTRATION FORM -->
        <section class="kb-form-panel">

            <div class="kb-form-content">

                <div class="kb-form-heading">

                    <h2>Register as Worker</h2>

                    <p>
                        Fill in your details to apply as a KrisBite worker.
                    </p>

                </div>

                <!-- SESSION ERROR -->
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- REGISTRATION FORM -->
                <form method="POST" action="{{ route('register.worker.store') }}">

                    @csrf

                    <!-- FULL NAME -->
                    <div class="kb-field">

                        <label for="name" class="form-label">
                            Full name
                        </label>

                        <div class="kb-input-wrapper">

                            <i class="bi bi-person"></i>

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
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

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
                                placeholder="Create a password"
                                required
                                autocomplete="new-password"
                            >

                        </div>

                        @error('password')
                            <div class="kb-error" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="kb-field">

                        <label for="password-confirm" class="form-label">
                            Confirm password
                        </label>

                        <div class="kb-input-wrapper">

                            <i class="bi bi-shield-lock"></i>

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
                            All worker registrations are subject to
                            review and approval by the KrisBite Admin
                            team. The admin will notify you through
                            WhatsApp once your account is approved.
                            Until then, you will not have access
                            to the system.
                        </p>

                        <p>
                            <strong>2. Data & Privacy:</strong>
                            Your personal information will be used
                            solely for account management and
                            operational purposes. KrisBite is
                            committed to protecting your data and
                            privacy in accordance with our policies.
                        </p>

                    </div>

                    <!-- AGREEMENT -->
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
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <!-- SUBMIT -->
                    <button type="submit" class="kb-submit">

                        Register Worker
                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>

                <!-- LOGIN LINK -->
                <div class="kb-register">

                    <span>Already have an account?</span>

                    <a href="{{ route('login') }}">
                        Sign In
                    </a>

                </div>

            </div>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="kb-footer">
        © {{ date('Y') }} KrisBite. All rights reserved.
    </footer>

</div>

@endsection