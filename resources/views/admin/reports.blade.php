@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/reports.css') }}">
@endpush

@section('content')

<div class="container-fluid admin-reports-page">

    <!-- Main Reports Card -->
    <div class="reports-main-card">

        <!-- Card Header -->
        <div class="reports-page-header">

            <div class="reports-header-content">
                <div>
                    <h2 class="page-title">Financial Reports & Audit</h2>
                    <p class="page-description">
                        Review financial transactions, vendor payouts, and commission records.
                    </p>
                </div>

                <!-- Export PDF (Kita buat ia submit form filter melalui GET) -->
                <a href="{{ route('admin.reports.export', request()->query()) }}"
                   class="reports-btn btn-export">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Export to PDF
                </a>
            </div>

        </div>

        <!-- Card Content -->
        <div class="reports-card-content">

            <!-- BAHARU: FILTER TOOLS -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.reports.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Vendor</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">-- All Vendors --</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Reset</a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Section Header -->
            <div class="reports-section-header">
                <h5 class="reports-section-title">
                    Financial Records
                </h5>
            </div>

            <!-- Reports Table -->
            @if($invoices->count() > 0)

                <div class="table-responsive reports-table-wrapper">
                    <table class="table reports-table">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Sales (RM)</th>
                                <th>Commission (RM)</th>
                                <th>Vendor Payout (RM)</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <!-- Date -->
                                    <td>{{ $invoice->invoice_date }}</td>

                                    <!-- Vendor -->
                                    <td class="vendor-name">
                                        {{ $invoice->vendor->name }}
                                    </td>

                                    <!-- Sales -->
                                    <td class="amount-cell">
                                        RM {{ number_format($invoice->total_sales, 2) }}
                                    </td>

                                    <!-- Commission -->
                                    <td class="amount-cell commission-amount">
                                        RM {{ number_format($invoice->commission_amount, 2) }}
                                    </td>

                                    <!-- Vendor Payout -->
                                    <td class="amount-cell payout-amount">
                                        RM {{ number_format($invoice->net_payout, 2) }}
                                    </td>

                                    <!-- View Receipt -->
                                    <td>
                                        <button type="button"
                                                class="reports-btn btn-receipt"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reportReceiptModal{{ $invoice->id }}">
                                            View Settlement Receipt
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else

                <!-- Empty Reports Message -->
                <div class="reports-empty-state">
                    No financial reports are available for the selected filters.
                </div>

            @endif

        </div>

    </div>

</div>

<!-- Receipt Details Modals -->
@foreach($invoices as $log)

<div class="modal fade reports-receipt-modal"
     id="reportReceiptModal{{ $log->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    Receipt Details - {{ $log->vendor->name }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <p class="receipt-date">
                    <strong>Date:</strong> {{ $log->invoice_date }}
                </p>

                <!-- Receipt Table -->
                <div class="table-responsive receipt-table-wrapper">
                    <table class="table table-bordered table-sm text-center align-middle receipt-table">

                        <thead>
                            <tr>
                                <th>Food Item</th>
                                <th>Price (RM)</th>
                                <th>Quantity</th>
                                <th>Gross Sales (RM)</th>
                                <th>Unsold Quantity</th>
                                <th>Total Sales (RM)</th>
                                <th>KrisBite Commission (RM)</th>
                                <th>Vendor Payout (RM)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $grandGross = 0;
                                $grandUnsold = 0;
                                $grandSales = 0;
                                $grandComm = 0;
                                $grandPayout = 0;
                            @endphp

                            @foreach($log->items as $item)

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
                                    <td class="receipt-item-name">
                                        {{ $item->name }}
                                    </td>

                                    <td>
                                        {{ number_format($item->price, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->actual_qty }}
                                    </td>

                                    <td>
                                        {{ number_format($grossSales, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->unsold_qty ?? 0 }}
                                    </td>

                                    <td>
                                        {{ number_format($itemSales, 2) }}
                                    </td>

                                    <td class="commission-amount">
                                        {{ number_format($itemComm, 2) }}
                                    </td>

                                    <td class="payout-amount">
                                        {{ number_format($itemPayout, 2) }}
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>

                        <!-- Grand Total -->
                        <tfoot>
                            <tr>
                                <td colspan="3" class="grand-total-label">
                                    GRAND TOTAL (RM)
                                </td>

                                <td>
                                    {{ number_format($grandGross, 2) }}
                                </td>

                                <td>
                                    {{ $grandUnsold }}
                                </td>

                                <td>
                                    {{ number_format($grandSales, 2) }}
                                </td>

                                <td class="commission-amount">
                                    {{ number_format($grandComm, 2) }}
                                </td>

                                <td class="payout-amount">
                                    {{ number_format($grandPayout, 2) }}
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button"
                        class="reports-btn btn-close-receipt"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@endforeach

@endsection