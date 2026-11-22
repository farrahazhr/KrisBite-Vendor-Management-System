@extends('layouts.vendor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/vendor/dashboard.css') }}">
@endpush

@section('content')

<div class="container-fluid vendor-dashboard">

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Vendor Dashboard</h2>
            <p class="dashboard-description">
                Welcome back, {{ Auth::user()->name }}! Here is your summary.
            </p>
        </div>

        <div class="dashboard-business">
            <h5>{{ Auth::user()->vendorApplication->business_name ?? 'Your Business' }}</h5>
            <span>{{ now()->format('d M Y') }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="vendor-stat-card stat-invoices">
                <div class="stat-label">Today Invoices</div>
                <div class="stat-value">{{ $todayInvoices }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="vendor-stat-card stat-sales">
                <div class="stat-label">Today's Sales</div>
                <div class="stat-value">RM {{ number_format($todaySales, 2) }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="vendor-stat-card stat-commission">
                <div class="stat-label">Total Commission</div>
                <div class="stat-value">RM {{ number_format($totalCommission, 2) }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="vendor-stat-card stat-pending">
                <div class="stat-label">Pending Settlements</div>
                <div class="stat-value">{{ $pendingSettlements }}</div>
            </div>
        </div>

    </div>

    <!-- Invoice Status and Financial Summary -->
    <div class="row g-4 mb-4">

        <!-- Today's Invoice Status -->
        <div class="col-xl-8">
            <div class="vendor-dashboard-card h-100">

                <div class="dashboard-card-header">
                    <h5>Today's Invoice Status</h5>
                </div>

                <div class="dashboard-card-body">

                    @if($todayInvoiceData->count() > 0)

                        @foreach($todayInvoiceData as $inv)

                            <div class="invoice-group">
                                <h6 class="invoice-heading">
                                    INV-{{ str_pad($inv->id, 3, '0', STR_PAD_LEFT) }}
                                    <span>({{ $inv->invoice_date }})</span>
                                </h6>

                                <div class="table-responsive dashboard-table-wrapper">
                                    <table class="table dashboard-table">
                                        <thead>
                                            <tr>
                                                <th>Food Item</th>
                                                <th>Qty Claimed</th>
                                                <th>Price (RM)</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($inv->items as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->declared_qty }}</td>
                                                    <td>RM {{ number_format($item->price, 2) }}</td>
                                                    <td>
                                                        @if($inv->status == 'pending')
                                                            <span class="vendor-status status-waiting">
                                                                Waiting for worker tally
                                                            </span>
                                                        @elseif($inv->status == 'verified')
                                                            <span class="vendor-status status-verified">
                                                                Verified by Worker
                                                            </span>
                                                        @else
                                                            <span class="vendor-status status-settled">
                                                                Settled
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @endforeach

                    @else

                        <div class="dashboard-empty-state">
                            <p>No invoices submitted today.</p>
                            <a href="{{ route('vendor.invoice.create') }}"
                               class="vendor-dashboard-btn btn-primary-navy">
                                Submit New Invoice
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="col-xl-4">
            <div class="vendor-dashboard-card h-100">

                <div class="dashboard-card-header">
                    <h5>Financial Summary ({{ date('M Y') }})</h5>
                </div>

                <div class="dashboard-card-body">

                    <div class="financial-summary-item">
                        <span>Total Sales (All Time)</span>
                        <h4 class="financial-sales">
                            RM {{ number_format($totalSalesOverall, 2) }}
                        </h4>
                    </div>

                    <div class="financial-summary-item">
                        <span>Total Commission Deducted</span>
                        <h4 class="financial-commission">
                            RM {{ number_format($totalCommission, 2) }}
                        </h4>
                    </div>

                    <div class="financial-summary-item">
                        <span>Total Payout Received</span>
                        <h4 class="financial-payout">
                            RM {{ number_format($totalPayoutOverall, 2) }}
                        </h4>
                    </div>

                    <div class="financial-chart-container">
                        <canvas id="financialChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Revenue Section -->
    <div class="vendor-dashboard-card revenue-card">

        <div class="dashboard-card-header">
            <h5>Revenue</h5>
        </div>

        <div class="dashboard-card-body">

            <div class="row g-4">

                <!-- Pending Settlements -->
                <div class="col-xl-6">

                    <h6 class="revenue-section-title">Pending Settlements</h6>

                    <div class="table-responsive dashboard-table-wrapper">
                        <table class="table dashboard-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Sales</th>
                                    <th>Payout</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if($pendingRevenues->count() > 0)

                                    @foreach($pendingRevenues as $rev)
                                        <tr>
                                            <td>{{ $rev->invoice_date }}</td>
                                            <td>INV-{{ str_pad($rev->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td>RM {{ number_format($rev->total_sales, 2) }}</td>
                                            <td>RM {{ number_format($rev->net_payout, 2) }}</td>
                                            <td>
                                                <span class="vendor-status status-waiting">
                                                    Pending
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach

                                @else

                                    <tr>
                                        <td colspan="5" class="table-empty">
                                            No pending settlements.
                                        </td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Settlement History -->
                <div class="col-xl-6">

                    <h6 class="revenue-section-title">Settlement History</h6>

                    <div class="table-responsive dashboard-table-wrapper">
                        <table class="table dashboard-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice</th>
                                    <th>Payout</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if($historyRevenues->count() > 0)

                                    @foreach($historyRevenues as $rev)
                                        <tr>
                                            <td>{{ $rev->invoice_date }}</td>
                                            <td>INV-{{ str_pad($rev->id, 3, '0', STR_PAD_LEFT) }}</td>
                                            <td>RM {{ number_format($rev->net_payout, 2) }}</td>
                                            <td>
                                                <span class="vendor-status status-settled">
                                                    Complete
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="vendor-dashboard-btn btn-outline-navy"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#vendorReceiptModal{{ $rev->id }}">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                @else

                                    <tr>
                                        <td colspan="5" class="table-empty">
                                            No history yet.
                                        </td>
                                    </tr>

                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<!-- Vendor Receipt Modals (FULL BREAKDOWN) -->
@foreach($historyRevenues as $rev)

<div class="modal fade vendor-receipt-modal"
     id="vendorReceiptModal{{ $rev->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Receipt Details - {{ $rev->invoice_date }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="receipt-information">
                    <div>
                        <p>
                            <strong>Invoice:</strong>
                            INV-{{ str_pad($rev->id, 3, '0', STR_PAD_LEFT) }}
                        </p>

                        <p>
                            <strong>Date:</strong> {{ $rev->invoice_date }}
                        </p>
                    </div>

                    <span class="vendor-status status-settled">
                        Completed
                    </span>
                </div>

                <div class="table-responsive receipt-table-wrapper">
                    <table class="table table-bordered table-sm text-center align-middle receipt-table">
                        <thead>
                            <tr>
                                <th>Food Item</th>
                                <th>Price (RM)</th>
                                <th>Quantity</th>
                                <th>Gross Sales (RM)</th>
                                <th>Unsold (Baki)</th>
                                <th>Total Sales Get (RM)</th>
                                <th>KrisBite Comm. (RM)</th>
                                <th>Vendor Payout (RM)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $grandGross = 0; $grandUnsold = 0; $grandSales = 0; $grandComm = 0; $grandPayout = 0; @endphp

                            @foreach($rev->items as $item)
                                @php
                                    $grossSales = $item->actual_qty * $item->price;
                                    $itemSales = $item->item_total_sales ?? 0;
                                    $itemComm = $itemSales * 0.20;
                                    $itemPayout = $itemSales - $itemComm;

                                    $grandGross += $grossSales;
                                    $grandUnsold += $item->unsold_qty ?? 0;
                                    $grandSales += $itemSales;
                                    $grandComm += $itemComm;
                                    $grandPayout += $itemPayout;
                                @endphp

                                <tr>
                                    <td class="fw-bold text-start">{{ $item->name }}</td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->actual_qty }}</td>
                                    <td>{{ number_format($grossSales, 2) }}</td>
                                    <td>{{ $item->unsold_qty ?? 0 }}</td>
                                    <td>{{ number_format($itemSales, 2) }}</td>
                                    <td class="text-danger">{{ number_format($itemComm, 2) }}</td>
                                    <td class="text-success">{{ number_format($itemPayout, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-center">GRAND TOTAL (RM)</td>
                                <td>{{ number_format($grandGross, 2) }}</td>
                                <td>{{ $grandUnsold }}</td>
                                <td>{{ number_format($grandSales, 2) }}</td>
                                <td class="text-danger">{{ number_format($grandComm, 2) }}</td>
                                <td class="text-success">{{ number_format($grandPayout, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <div class="modal-footer">

                <a href="{{ route('vendor.receipt.download', $rev->id) }}"
                   class="vendor-dashboard-btn btn-primary-navy">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Download PDF
                </a>

                <button type="button"
                        class="vendor-dashboard-btn btn-close-receipt"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>
    </div>
</div>

@endforeach

<!-- Financial Chart (Consistent Roasted Amber Palette) -->
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#667085';

    const ctx = document.getElementById('financialChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr',
                'May', 'Jun', 'Jul', 'Aug',
                'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: [{
                label: 'Monthly Sales (RM)',
                data: @json($monthlyData),
                borderColor: '#C86200',
                backgroundColor: 'rgba(200, 98, 0, 0.10)',
                borderWidth: 2,
                pointBackgroundColor: '#C86200',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
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
</script>

@endsection