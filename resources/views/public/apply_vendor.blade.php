@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/public/apply_vendor.css') }}">
@endpush

@section('content')

<div class="public-apply-page">

    <div class="container public-apply-container">

        <div class="row justify-content-center">

            <div class="col-lg-9 col-xl-8">

                <!-- BACK BUTTON -->
                <div class="public-apply-back-wrapper">

                    <a href="{{ route('public.menu') }}"
                       class="btn public-apply-back-btn">

                        <i class="bi bi-arrow-left"></i>
                        Back to Menu

                    </a>

                </div>

                <!-- APPLICATION CARD -->
                <div class="public-application-card">

                    <!-- CARD HEADER -->
                    <div class="public-application-header">

                        <div class="public-application-heading">

                            <div class="public-application-icon">
                                <i class="bi bi-shop"></i>
                            </div>

                            <div>
                                <h4 class="public-application-title">
                                    Vendor Application Form
                                </h4>

                                <p class="public-application-subtitle">
                                    Please complete your business information to apply as a KrisBite vendor.
                                </p>
                            </div>

                        </div>

                    </div>

                    <!-- CARD BODY -->
                    <div class="public-application-body">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- CHECK STATUS SLOT DI SINI -->
                        @if($slotsStatus == 'closed')
                            
                            <!-- BILA SLOT TUTUP: PAPAR MESEJ SAHAJA -->
                            <div class="alert alert-warning text-center p-5">
                                <i class="bi bi-exclamation-triangle-fill fs-1 text-warning"></i>
                                <h4 class="mt-3">Sorry, currently the vendor slots is not open and full.</h4>
                                <p class="text-muted">Please check back later for available slots.</p>
                                <a href="{{ route('public.menu') }}" class="btn btn-secondary mt-3">Back to Menu</a>
                            </div>

                        @else

                            <!-- BILA SLOT BUKA: PAPAR BORANG ASAL -->
                            <form action="{{ route('public.apply.vendor.store') }}"
                                  method="POST"
                                  enctype="multipart/form-data">

                                @csrf

                                <div class="row g-3">

                                    <!-- BUSINESS NAME -->
                                    <div class="col-md-6">

                                        <label class="form-label public-form-label">
                                            Business Name
                                        </label>

                                        <input type="text"
                                               name="business_name"
                                               class="form-control public-form-control"
                                               required>

                                    </div>

                                    <!-- OWNER NAME -->
                                    <div class="col-md-6">

                                        <label class="form-label public-form-label">
                                            Owner Name
                                        </label>

                                        <input type="text"
                                               name="owner_name"
                                               class="form-control public-form-control"
                                               required>

                                    </div>

                                    <!-- CONTACT INFORMATION -->
                                    <div class="col-12">

                                        <label class="form-label public-form-label">
                                            Phone Number / Email
                                        </label>

                                        <input type="text"
                                               name="contact_info"
                                               class="form-control public-form-control"
                                               required>

                                    </div>

                                    <!-- BUSINESS ADDRESS -->
                                    <div class="col-12">

                                        <label class="form-label public-form-label">
                                            Business Address
                                        </label>

                                        <textarea name="business_address"
                                                  class="form-control public-form-control public-form-textarea"
                                                  rows="3"
                                                  required></textarea>

                                    </div>

                                    <!-- TYPHOID STATUS -->
                                    <div class="col-md-6">

                                        <label class="form-label public-form-label">
                                            Typhoid Injection Status
                                        </label>

                                        <select name="typhoid_status"
                                                class="form-control public-form-control public-form-select"
                                                required>

                                            <option value="">
                                                -- Select Status --
                                            </option>

                                            <option value="Yes, Valid">
                                                Yes, Still Valid
                                            </option>

                                            <option value="No">
                                                No
                                            </option>

                                        </select>

                                    </div>

                                    <!-- TYPHOID CERTIFICATE UPLOAD -->
                                    <div class="col-md-6">

                                        <label class="form-label public-form-label">
                                            Upload Typhoid Certificate (Image)
                                        </label>

                                        <input type="file"
                                               name="typhoid_image"
                                               class="form-control public-form-control public-file-input"
                                               required
                                               accept="image/*">

                                        <small class="public-form-hint">
                                            Max: 2MB (JPG/PNG)
                                        </small>

                                    </div>

                                    <!-- SUBMIT BUTTON -->
                                    <div class="col-12 mt-4">

                                        <button type="submit"
                                                class="btn public-application-submit-btn">

                                            <i class="bi bi-send"></i>
                                            Submit Application

                                        </button>

                                    </div>

                                </div>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection