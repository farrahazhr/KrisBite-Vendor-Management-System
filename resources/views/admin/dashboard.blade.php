@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')

<div class="container-fluid admin-dashboard">

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2 class="dashboard-title">Dashboard Overview</h2>
        <p class="dashboard-description">
            Welcome back, Admin. View vendor activities and financial performance here.
        </p>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">

        <!-- Active Vendors -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-card-blue">
                <div class="card-body">
                    <h6 class="stat-label">Active Vendors</h6>
                    <h2 class="stat-value">{{ $totalVendors }}</h2>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-card-amber">
                <div class="card-body">
                    <h6 class="stat-label">Pending Vendor Applications</h6>
                    <h2 class="stat-value">{{ $pendingApps }}</h2>
                </div>
            </div>
        </div>

        <!-- Today's Commission -->
        <div class="col-md-4 mb-3">
            <div class="card stat-card stat-card-slate">
                <div class="card-body">
                    <h6 class="stat-label">Today's Commission</h6>
                    <h2 class="stat-value">
                        RM {{ number_format($todayCommission, 2) }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts -->
    <div class="row">

        <!-- Monthly Commission -->
        <div class="col-md-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5>Monthly Commission Overview</h5>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="commissionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Vendors -->
        <div class="col-md-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5>Top 5 Vendors by Commission</h5>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topVendorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Daily Summary -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card summary-card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Daily Summary</h5>
                    <span class="summary-date">
                        {{ now()->format('d M Y') }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row text-center">

                        <!-- Invoices -->
                        <div class="col-md-3 summary-item border-end">
                            <h6 class="summary-label">
                                Total Invoices Submitted
                            </h6>
                            <h2 class="summary-value">
                                {{ $totalInvoicesSent }}
                            </h2>
                        </div>

                        <!-- Commission -->
                        <div class="col-md-3 summary-item border-end">
                            <h6 class="summary-label">
                                Today's Total Commission
                            </h6>
                            <h2 class="summary-value summary-value-navy">
                                RM {{ number_format($todayCommission, 2) }}
                            </h2>
                        </div>

                        <!-- Vendor Payout -->
                        <div class="col-md-3 summary-item border-end">
                            <h6 class="summary-label">
                                Total Vendor Payouts
                            </h6>
                            <h2 class="summary-value summary-value-navy">
                                RM {{ number_format($payoutToVendor, 2) }}
                            </h2>
                        </div>

                        <!-- Completed Settlements -->
                        <div class="col-md-3 summary-item">
                            <h6 class="summary-label">
                                Completed Settlements
                            </h6>
                            <h2 class="summary-value">
                                {{ $completedSettlements }}
                            </h2>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Dashboard Charts -->
<script>
    // Chart Font Settings (Consistent with Inter)
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#667085';

    // Monthly Commission Bar Chart (Theme: Roasted Amber #C86200)
    const ctx1 = document.getElementById('commissionChart').getContext('2d');

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr',
                'May', 'Jun', 'Jul', 'Aug',
                'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: [{
                label: 'Commission (RM)',
                data: @json($monthlyData),
                backgroundColor: 'rgba(200, 98, 0, 0.8)',
                borderColor: '#C86200',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Top 5 Vendors Doughnut Chart (Harmonized Orange Palette)
    const ctx2 = document.getElementById('topVendorsChart').getContext('2d');

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: @json($topVendorLabels),
            datasets: [{
                label: 'Commission (RM)',
                data: @json($topVendorData),
                backgroundColor: [
                    '#C86200',
                    '#DF7300',
                    '#E67E22',
                    '#F59E0B',
                    '#FBBF24'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

@endsection