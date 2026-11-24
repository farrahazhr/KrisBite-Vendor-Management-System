@extends('layouts.worker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/worker/dashboard.css') }}">
@endpush

@section('content')

<div class="container-fluid worker-dashboard">

    <!-- PAGE HEADER -->
    <div class="worker-dashboard-header">

        <h2 class="worker-page-title">
            Worker Dashboard
        </h2>

        <p class="worker-page-description">
            Welcome back, {{ Auth::user()->name }}! Here is your daily summary.
        </p>

    </div>

    <!-- =====================================
         STATISTIC CARDS
    ====================================== -->

    <div class="row g-4 worker-stats-row">

        <!-- TODAY INVOICE -->
        <div class="col-xl-3 col-md-6">

            <div class="worker-stat-card stat-invoices">

                <div class="worker-stat-content">

                    <h6 class="worker-stat-label">
                        Today Invoice
                    </h6>

                    <h2 class="worker-stat-value">
                        {{ $todayInvoices }}
                    </h2>

                </div>

            </div>

        </div>

        <!-- PENDING TALLY -->
        <div class="col-xl-3 col-md-6">

            <div class="worker-stat-card stat-tally">

                <div class="worker-stat-content">

                    <h6 class="worker-stat-label">
                        Pending Tally
                    </h6>

                    <h2 class="worker-stat-value">
                        {{ $pendingTally }}
                    </h2>

                </div>

            </div>

        </div>

        <!-- TODAY SETTLEMENT -->
        <div class="col-xl-3 col-md-6">

            <div class="worker-stat-card stat-settlements">

                <div class="worker-stat-content">

                    <h6 class="worker-stat-label">
                        Today Settlement
                    </h6>

                    <h2 class="worker-stat-value">
                        {{ $todaySettlements }}
                    </h2>

                </div>

            </div>

        </div>

        <!-- PENDING STOCK-IN -->
        <div class="col-xl-3 col-md-6">

            <div class="worker-stat-card stat-stock">

                <div class="worker-stat-content">

                    <h6 class="worker-stat-label">
                        Pending Stock-In
                    </h6>

                    <h2 class="worker-stat-value">
                        {{ $pendingStockIn }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <!-- =====================================
         RECENT ACTIVITY
    ====================================== -->

    <div class="worker-activity-card">

        <!-- CARD HEADER -->
        <div class="worker-activity-header">

            <h5 class="worker-section-title">
                Recent Activity
            </h5>

        </div>

        <!-- CARD BODY -->
        <div class="worker-activity-body">

            @if($recentActivities->count() > 0)

                <ul class="list-group list-group-flush worker-activity-list">

                    @foreach($recentActivities as $activity)

                        <li class="list-group-item worker-activity-item">

                            <div class="worker-activity-description">

                                <span class="worker-activity-vendor">
                                    {{ $activity->vendor->name ?? 'Unknown Vendor' }}
                                </span>

                                @if($activity->settlement_status == 'completed')

                                    <span class="worker-activity-status activity-completed">
                                        - Settlement Approved & Completed
                                    </span>

                                @elseif($activity->status == 'settled')

                                    <span class="worker-activity-status activity-settled">
                                        - Sales Inputted (Pending Admin Approval)
                                    </span>

                                @else

                                    <span class="worker-activity-status activity-verified">
                                        - Stock Verified & Tally Completed
                                    </span>

                                @endif

                            </div>

                            <span class="worker-activity-time">
                                {{ $activity->created_at->diffForHumans() }}
                            </span>

                        </li>

                    @endforeach

                </ul>

            @else

                <div class="worker-empty-activity">

                    <i class="bi bi-clock-history"></i>

                    <p>
                        There is no recent activity to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection