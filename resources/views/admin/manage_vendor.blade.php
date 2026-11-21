
@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/manage_vendor.css') }}">
@endpush

@section('content')

<div class="container-fluid manage-vendor-page">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <h2 class="page-title">Vendor Applications</h2>
        <p class="page-description">
            Review and manage vendor registration applications.
        </p>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- VENDOR APPLICATION SLOTS CONTROL -->
    <div class="vendor-slots-card">

        <div class="vendor-slots-header">
            <div class="vendor-slots-heading">
                <div class="vendor-slots-icon">
                    <i class="bi bi-shop-window"></i>
                </div>

                <div>
                    <h5 class="vendor-slots-title">
                        Vendor Application Slots
                    </h5>
                    <p class="vendor-slots-description">
                        Control whether public users can submit new applications.
                    </p>
                </div>
            </div>
        </div>

        <div class="vendor-slots-body">

            <div class="vendor-slots-info">
                <span class="vendor-slots-label">
                    Current Application Status
                </span>

                @if($slotsStatus == 'open')
                    <span class="vendor-slots-status slots-open">
                        <i class="bi bi-check-circle-fill"></i>
                        Currently OPEN
                    </span>
                @else
                    <span class="vendor-slots-status slots-closed">
                        <i class="bi bi-x-circle-fill"></i>
                        Currently CLOSED
                    </span>
                @endif
            </div>

            <div class="vendor-slots-action">

                <form action="{{ route('admin.vendors.toggle-slots') }}"
                      method="POST">

                    @csrf

                    @if($slotsStatus == 'open')

                        <button type="submit"
                                class="vendor-slots-btn slots-close-btn">
                            <i class="bi bi-lock-fill"></i>
                            Close Slots
                        </button>

                    @else

                        <button type="submit"
                                class="vendor-slots-btn slots-open-btn">
                            <i class="bi bi-unlock-fill"></i>
                            Open Slots
                        </button>

                    @endif

                </form>

            </div>

        </div>

    </div>

    <!-- VENDOR APPLICATIONS TABLE -->
    <div class="vendor-table-card">

        <div class="vendor-table-header d-flex justify-content-between align-items-center">

            <h5 class="vendor-table-title">
                Application List
            </h5>

            <span class="vendor-table-count">
                Total Applications: {{ $applications->count() }}
            </span>

        </div>

        <div class="table-responsive">

            <table class="table vendor-table">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Email Address</th>
                        <th>Business Name</th>
                        <th>Typhoid Vaccination</th>
                        <th>Application Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($applications as $key => $app)

                        <tr>

                            <!-- NUMBER -->
                            <td>
                                {{ $key + 1 }}
                            </td>

                            <!-- EMAIL -->
                            <td class="email-text">
                                {{ $app->user->email ?? 'N/A' }}
                            </td>

                            <!-- BUSINESS NAME -->
                            <td class="business-name">
                                {{ $app->business_name }}
                            </td>

                            <!-- TYPHOID VACCINATION -->
                            <td>

                                <div class="mb-2">
                                    {{ $app->typhoid_status }}
                                </div>

                                @if($app->typhoid_image_path)

                                    <a href="{{ asset($app->typhoid_image_path) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="vendor-btn btn-certificate">

                                        <i class="bi bi-file-earmark-text"></i>
                                        View Certificate

                                    </a>

                                @endif

                            </td>

                            <!-- APPLICATION STATUS -->
                            <td>

                                @if($app->status == 'pending')

                                    <span class="vendor-status status-pending">
                                        Pending
                                    </span>

                                @elseif($app->status == 'approved')

                                    <span class="vendor-status status-approved">
                                        Approved
                                    </span>

                                @else

                                    <span class="vendor-status status-rejected">
                                        Rejected
                                    </span>

                                @endif

                            </td>

                            <!-- ACTIONS -->
                            <td>

                                @if($app->status == 'pending')

                                    <div class="action-buttons">

                                        <a href="{{ route('admin.vendors.approve', $app->id) }}"
                                           class="vendor-btn btn-approve">
                                            Approve
                                        </a>

                                        <a href="{{ route('admin.vendors.reject', $app->id) }}"
                                           class="vendor-btn btn-reject">
                                            Reject
                                        </a>

                                    </div>

                                @else

                                    <span class="no-action">
                                        No Action Required
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-state">
                                No vendor applications found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection