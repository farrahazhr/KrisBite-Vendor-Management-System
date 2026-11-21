
@extends('layouts.app')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="kb-auth-page kb-register-page">

    <!-- HEADER -->
    <header class="kb-header">

        <div class="kb-brand">
            <i class="bi bi-building"></i>
            <span>KrisBite</span>
        </div>

        <nav class="kb-navigation" aria-label="Authentication navigation">

            <a href="{{ route('login') }}" class="kb-nav-link">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="kb-nav-link kb-nav-active"
               aria-current="page">
                Register
            </a>

        </nav>

    </header>

    <!-- INTRODUCTION -->
    <section class="kb-introduction">

        <span class="kb-eyebrow">
            KRISBITE DIGITAL PLATFORM
        </span>

        <h1>Making Food Experiences Simpler..</h1>

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

        <!-- RIGHT: REGISTER FORM -->
        <section class="kb-form-panel">

            <div class="kb-form-content">

                <div class="kb-form-heading">
                    <h2>Create an account</h2>
                    <p>Enter your details to get started with KrisBite.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NAME -->
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
                                autocomplete="email"
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

                    <!-- REGISTER BUTTON -->
                    <button type="submit" class="kb-submit">

                        Create Account
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