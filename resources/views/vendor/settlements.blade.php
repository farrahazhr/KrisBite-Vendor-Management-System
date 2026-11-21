@extends('layouts.vendor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/vendor/settlements.css') }}">
@endpush

@section('content')

<div class="w-100 vendor-settlements-page">

    <!-- MAIN CARD -->
    <div class="settlements-main-card">

        <!-- PAGE HEADER -->
        <div class="settlements-page-header">
            <h2 class="page-title">My Financial & Settlement Log</h2>
            <p class="page-description">
                View your settlement records and payments approved by the Administrator.
            </p>
        </div>

        <div class="settlements-page-content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- SETTLEMENT RECORDS -->
            <div class="settlements-section">

                <div class="settlements-section-header">
                    <h5 class="settlements-section-title">
                        Settlement History
                    </h5>
                    <p class="settlements-section-description">
                        Review your gross sales, commission deductions, net payouts and settlement status.
                    </p>
                </div>

                @if($invoices->count() > 0)

                    <div class="table-responsive settlements-table-wrapper">

                        <table class="table table-hover settlements-table">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Gross Sales (RM)</th>
                                    <th>KrisBite Comm. (RM)</th>
                                    <th>My Payout (RM)</th>
                                    <th>Settlement Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($invoices as $invoice)

                                <tr>
                                    <td>
                                        {{ $invoice->invoice_date }}
                                    </td>

                                    <td class="settlements-amount">
                                        RM {{ number_format($invoice->total_sales, 2) }}
                                    </td>

                                    <td class="text-danger settlements-commission">
                                        - RM {{ number_format($invoice->commission_amount, 2) }}
                                    </td>

                                    <td class="text-success fw-bold settlements-payout">
                                        RM {{ number_format($invoice->net_payout, 2) }}
                                    </td>

                                    <td>
                                        @if($invoice->settlement_status == 'completed')
                                            <span class="badge bg-success settlement-status-badge">
                                                Complete
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark settlement-status-badge">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="settlements-action-buttons">

                                            <!-- VIEW RECEIPT -->
                                            <button type="button"
                                                    class="btn btn-sm btn-primary settlements-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#receiptModal{{ $invoice->id }}">
                                                <i class="bi bi-eye"></i>
                                                View Receipt
                                            </button>

                                            <!-- DOWNLOAD PDF -->
                                            <a href="{{ route('vendor.receipt.download', $invoice->id) }}"
                                               class="btn btn-sm btn-danger settlements-download-btn"
                                               target="_blank">
                                                <i class="bi bi-download"></i>
                                                Download
                                            </a>

                                        </div>
                                    </td>
                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="settlements-empty-state">
                        <h5>No settlement records yet.</h5>
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

<!-- SETTLEMENT RECEIPT MODALS (FULL DETAILS SAMA MACAM WORKER) -->
@foreach($invoices as $invoice)

<div class="modal fade vendor-settlement-modal"
     id="receiptModal{{ $invoice->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <!-- MODAL HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Settlement Receipt - {{ $invoice->invoice_date }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>

            <!-- MODAL BODY -->
            <div class="modal-body">

                <p class="settlement-receipt-date">
                    <b>Date:</b> {{ $invoice->invoice_date }}
                </p>

                <!-- RECEIPT ITEMS TABLE (FULL BREAKDOWN) -->
                <div class="table-responsive settlement-receipt-table-wrapper">

                    <table class="table table-bordered table-sm text-center align-middle settlement-receipt-table">

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

                            @foreach($invoice->items as $item)

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
                                    <td class="fw-bold text-start">
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

                                    <td class="text-danger">
                                        {{ number_format($itemComm, 2) }}
                                    </td>

                                    <td class="text-success">
                                        {{ number_format($itemPayout, 2) }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-center">
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

                                <td class="text-danger">
                                    {{ number_format($grandComm, 2) }}
                                </td>

                                <td class="text-success">
                                    {{ number_format($grandPayout, 2) }}
                                </td>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer">

                <a href="{{ route('vendor.receipt.download', $invoice->id) }}"
                   class="btn btn-danger settlement-modal-download-btn"
                   target="_blank">
                    <i class="bi bi-download"></i>
                    Download PDF
                </a>

                <button type="button"
                        class="btn btn-secondary settlement-modal-close-btn"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

@endforeach

@endsection