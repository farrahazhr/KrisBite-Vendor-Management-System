@extends('layouts.worker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/worker/stock_tally.css') }}">
@endpush

@section('content')

<div class="w-100 worker-stock-tally">

    <!-- MAIN CARD -->
    <div class="stock-main-card">

        <!-- PAGE HEADER -->
        <div class="stock-page-header">
            <h2 class="stock-page-title">Stock Tally</h2>
        </div>

        <!-- PAGE CONTENT -->
        <div class="stock-page-content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- =========================================
                 BAHAGIAN 1: PENDING TALLY
            ========================================== -->
            <div class="stock-section">

                <div class="stock-section-header">
                    <h4 class="stock-section-title">
                        Step 1: Pending Tally (Morning Stock Verification)
                    </h4>

                    <p class="stock-section-description">
                        Compare the quantity claimed by each vendor with the actual stock received.
                    </p>
                </div>

                @if($pendingInvoices->count() > 0)

                    @foreach($pendingInvoices as $invoice)

                        <!-- VENDOR INVOICE CARD -->
                        <div class="stock-vendor-card">

                            <!-- VENDOR HEADER -->
                            <div class="stock-vendor-header">

                                <div>
                                    <div class="stock-vendor-name">
                                        <b>Vendor:</b> {{ $invoice->vendor->name }}
                                    </div>

                                    <small class="stock-vendor-meta">
                                        INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                                        |
                                        {{ $invoice->invoice_date }}
                                    </small>
                                </div>

                            </div>

                            <!-- VENDOR BODY -->
                            <div class="stock-vendor-body">

                                <form action="{{ route('worker.stock.verify', $invoice->id) }}"
                                      method="POST">

                                    @csrf

                                    <div class="table-responsive stock-table-wrapper">

                                        <table class="table table-bordered stock-table">

                                            <thead>
                                                <tr>
                                                    <th>Food Item</th>
                                                    <th>Price (RM)</th>
                                                    <th>Stock Claimed</th>
                                                    <th>Actual Stock</th>
                                                    <th>Short or Difference</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach($invoice->items as $item)

                                                    <tr>
                                                        <td>
                                                            {{ $item->name }}
                                                        </td>

                                                        <td>
                                                            RM {{ number_format($item->price, 2) }}
                                                        </td>

                                                        <td>
                                                            {{ $item->declared_qty }}
                                                        </td>

                                                        <td class="stock-actual-cell">

                                                            <input type="number"
                                                                   name="actual_qty[{{ $item->id }}]"
                                                                   class="form-control stock-actual-input"
                                                                   value="{{ $item->actual_qty ?? '' }}"
                                                                   required
                                                                   oninput="calculateDiff(this, {{ $item->declared_qty }}, 'diff_{{ $item->id }}')">

                                                        </td>

                                                        <td id="diff_{{ $item->id }}"
                                                            class="text-muted fw-bold">
                                                            Awaiting Input
                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                    <!-- CONFIRM TALLY BUTTON -->
                                    <div class="stock-form-actions">

                                        <button type="submit"
                                                class="btn btn-primary stock-confirm-btn">
                                            <i class="bi bi-check-circle"></i>
                                            Confirm Tally
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    @endforeach

                @else

                    <div class="stock-empty-state">
                        <h5>
                            Currently, there is no pending tally that you need to complete.
                        </h5>
                    </div>

                @endif

            </div>

            <!-- SECTION DIVIDER -->
            <div class="stock-section-divider"></div>

            <!-- =========================================
                 BAHAGIAN 2: INVOICE HISTORY
            ========================================== -->
            <div class="stock-section">

                <div class="stock-section-header">

                    <h4 class="stock-section-title">
                        Invoice History (Received Invoices)
                    </h4>

                    <p class="stock-section-description">
                        View previously received invoices and their stock verification status.
                    </p>

                </div>

                @if($historyInvoices->count() > 0)

                    <div class="table-responsive stock-table-wrapper">

                        <table class="table table-hover stock-table">

                            <thead>
                                <tr>
                                    <th>Vendor</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($historyInvoices as $invoice)

                                    <tr>
                                        <td>
                                            {{ $invoice->vendor->name }}
                                        </td>

                                        <td>
                                            INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                                        </td>

                                        <td>
                                            {{ $invoice->invoice_date }}
                                        </td>

                                        <td>

                                            @if($invoice->status == 'verified')

                                                <span class="badge bg-secondary stock-status-badge">
                                                    Verified
                                                </span>

                                            @elseif($invoice->status == 'settled')

                                                <span class="badge bg-warning text-dark stock-status-badge">
                                                    Settled
                                                </span>

                                            @elseif($invoice->status == 'completed')

                                                <span class="badge bg-success stock-status-badge">
                                                    Completed
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <button type="button"
                                                    class="btn btn-sm btn-primary stock-view-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#invoiceModal{{ $invoice->id }}">
                                                <i class="bi bi-eye"></i>
                                                View Invoice
                                            </button>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="stock-empty-state">
                        <h5>No invoice history yet.</h5>
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


<!-- =========================================
     MODAL POPUP UNTUK VIEW INVOICE
========================================== -->

@foreach($historyInvoices as $invoice)

<div class="modal fade worker-stock-modal"
     id="invoiceModal{{ $invoice->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <!-- MODAL HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Invoice Details - INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>

            <!-- MODAL BODY -->
            <div class="modal-body">

                <p>
                    <b>Vendor:</b> {{ $invoice->vendor->name }}
                </p>

                <p>
                    <b>Date:</b> {{ $invoice->invoice_date }}
                </p>

                <div class="table-responsive stock-modal-table-wrapper">

                    <table class="table table-bordered stock-modal-table">

                        <thead>
                            <tr>
                                <th>Food Item</th>
                                <th>Price (RM)</th>
                                <th>Claimed</th>
                                <th>Actual Received</th>
                                <th>Short/Difference</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($invoice->items as $item)

                                @php
                                    $diff = $item->actual_qty - $item->declared_qty;
                                @endphp

                                <tr>

                                    <td>{{ $item->name }}</td>

                                    <td>
                                        RM {{ number_format($item->price, 2) }}
                                    </td>

                                    <td>{{ $item->declared_qty }}</td>

                                    <td>
                                        {{ $item->actual_qty ?? 'N/A' }}
                                    </td>

                                    <td>

                                        @if($diff == 0)

                                            <span class="text-success fw-bold">
                                                Match (0)
                                            </span>

                                        @elseif($diff > 0)

                                            <span class="text-primary fw-bold">
                                                Extra (+{{ $diff }})
                                            </span>

                                        @else

                                            <span class="text-danger fw-bold">
                                                Short ({{ $diff }})
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- MODAL FOOTER -->
            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary stock-modal-close-btn"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

@endforeach


<!-- =========================================
     ORIGINAL JAVASCRIPT - UNCHANGED
========================================== -->

<script>
    function calculateDiff(input, declaredQty, diffId) {
        var actual = input.value;
        var diff = actual - declaredQty;
        var diffElement = document.getElementById(diffId);

        if (diff === 0) {
            diffElement.innerHTML = "Match (0)";
            diffElement.className = "text-success fw-bold";
        } else if (diff > 0) {
            diffElement.innerHTML = "Extra (+" + diff + ")";
            diffElement.className = "text-primary fw-bold";
        } else {
            diffElement.innerHTML = "Short (" + diff + ")";
            diffElement.className = "text-danger fw-bold";
        }
    }
</script>

@endsection