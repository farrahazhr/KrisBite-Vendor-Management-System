
@extends('layouts.worker')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/worker/settlement.css') }}">
@endpush

@section('content')

<div class="w-100 worker-settlement-page">

    <div class="settlement-main-card">

        <!-- PAGE HEADER -->
        <div class="settlement-page-header">
            <h2 class="settlement-page-title">Settlement Management</h2>
            <p class="settlement-page-description">
                Update end-of-day sales, calculate vendor payouts and review settlement records.
            </p>
        </div>

        <div class="settlement-page-content">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- BAHAGIAN 1: PENDING SETTLEMENT -->
            <div class="settlement-section">

                <div class="settlement-section-header">
                    <h4 class="settlement-section-title">
                        Step 2: Update Sales & Unsold Items (End of Day)
                    </h4>
                    <p class="settlement-section-description">
                        Enter the remaining unsold items and total sales collected before submitting the settlement to Admin.
                    </p>
                </div>

                @if($pendingSettlements->count() > 0)

                    @foreach($pendingSettlements as $invoice)

                    <div class="settlement-vendor-card">

                        <div class="settlement-vendor-header">
                            <div>
                                <div class="settlement-vendor-name">
                                    <b>Vendor:</b> {{ $invoice->vendor->name }}
                                </div>
                                <small class="settlement-vendor-meta">
                                    INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }} | {{ $invoice->invoice_date }}
                                </small>
                            </div>
                        </div>

                        <div class="settlement-vendor-body">

                            <form action="{{ route('worker.settlements.calculate', $invoice->id) }}" method="POST">
                                @csrf

                                <div class="table-responsive settlement-table-wrapper">

                                    <table class="table table-bordered table-sm settlement-table">

                                        <thead class="text-center">
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

                                        <tbody class="text-center align-middle">

                                            @foreach($invoice->items as $item)

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
                                                    {{ number_format($item->actual_qty * $item->price, 2) }}
                                                </td>

                                                <td style="width: 100px;">
                                                    <!-- UBAH: Name guna item_id sebagai key -->
                                                    <input type="number"
                                                           name="unsold[{{ $item->id }}]"
                                                           class="form-control form-control-sm text-center settlement-input"
                                                           placeholder="0"
                                                           required>
                                                </td>

                                                <td style="width: 140px;">
                                                    <input type="number"
                                                           step="0.01"
                                                           name="total_sales[{{ $item->id }}]"
                                                           class="form-control form-control-sm text-center actual-sales settlement-input"
                                                           required
                                                           placeholder="0.00"
                                                           oninput="calcSettlement(this)">
                                                </td>

                                                <td>
                                                    <input type="text"
                                                           class="form-control form-control-sm text-center bg-light border-0 fw-bold text-danger commission-amount settlement-readonly"
                                                           readonly
                                                           value="0.00">
                                                </td>

                                                <td>
                                                    <input type="text"
                                                           class="form-control form-control-sm text-center bg-light border-0 fw-bold text-success vendor-payout settlement-readonly"
                                                           readonly
                                                           value="0.00">
                                                </td>
                                            </tr>

                                            @endforeach

                                        </tbody>

                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td colspan="5" class="text-end">
                                                    GRAND TOTAL (RM)
                                                </td>

                                                <td class="text-center">
                                                    <span class="h6 mb-0 gtsales">0.00</span>
                                                </td>

                                                <td class="text-center text-danger">
                                                    <span class="h6 mb-0 gtcomm">0.00</span>
                                                </td>

                                                <td class="text-center text-success">
                                                    <span class="h6 mb-0 gtpayout">0.00</span>
                                                </td>
                                            </tr>
                                        </tfoot>

                                    </table>

                                </div>

                                <div class="settlement-form-actions">
                                    <button type="submit" class="btn btn-primary settlement-submit-btn">
                                        <i class="bi bi-send"></i>
                                        Submit to Admin
                                    </button>
                                </div>

                            </form>

                        </div>

                    </div>

                    @endforeach

                @else

                    <div class="settlement-empty-state">
                        <h5>
                            Currently, there is no pending settlement that you need to complete.
                        </h5>
                    </div>

                @endif

            </div>

            <div class="settlement-section-divider"></div>

            <!-- BAHAGIAN 2: FINANCIAL LOG -->
            <div class="settlement-section">

                <div class="settlement-section-header">
                    <h4 class="settlement-section-title">
                        Financial & Settlement Log
                    </h4>
                    <p class="settlement-section-description">
                        Review submitted settlements, payment amounts and approval status.
                    </p>
                </div>

                @if($settlementLogs->count() > 0)

                <div class="table-responsive settlement-table-wrapper">

                    <table class="table table-hover settlement-table">

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Gross Sales (RM)</th>
                                <th>Comm. (RM)</th>
                                <th>Payout (RM)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($settlementLogs as $log)

                            <tr>
                                <td>{{ $log->invoice_date }}</td>

                                <td>{{ $log->vendor->name }}</td>

                                <td>
                                    RM {{ number_format($log->total_sales, 2) }}
                                </td>

                                <td>
                                    RM {{ number_format($log->commission_amount, 2) }}
                                </td>

                                <td>
                                    RM {{ number_format($log->net_payout, 2) }}
                                </td>

                                <td>
                                    @if($log->settlement_status == 'completed')
                                        <span class="badge bg-success settlement-status-badge">
                                            Complete
                                        </span>
                                    @elseif($log->status == 'settled')
                                        <span class="badge bg-warning text-dark settlement-status-badge">
                                            Pending Admin
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-primary settlement-view-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#receiptModal{{ $log->id }}">
                                        <i class="bi bi-eye"></i>
                                        View Receipt
                                    </button>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                @else

                <div class="settlement-empty-state">
                    <h5>No settlement logs yet.</h5>
                </div>

                @endif

            </div>

        </div>

    </div>

</div>


<!-- MODAL POPUP UNTUK VIEW RECEIPT (SEMUA DETAILS) -->
@foreach($settlementLogs as $log)

<div class="modal fade worker-settlement-modal"
     id="receiptModal{{ $log->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

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

            <div class="modal-body">

                <p>
                    <b>Date:</b> {{ $log->invoice_date }}
                </p>

                <div class="table-responsive settlement-modal-table-wrapper">

                    <table class="table table-bordered table-sm text-center align-middle settlement-modal-table">

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

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary settlement-close-btn"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

@endforeach


<!-- JAVASCRIPT UNTUK AUTO KIRA COMMISSION, PAYOUT & GRAND TOTAL -->
<script>
function calcSettlement(input) {
    var table = input.closest('table');
    var row = input.closest('tr');

    var totalSalesGet = parseFloat(input.value) || 0;

    var commission = totalSalesGet * 0.20;
    var payout = totalSalesGet - commission;

    row.querySelector('.commission-amount').value = commission.toFixed(2);
    row.querySelector('.vendor-payout').value = payout.toFixed(2);

    var grandSales = 0;
    var grandComm = 0;
    var grandPayout = 0;

    var allSalesInputs = table.querySelectorAll('.actual-sales');
    allSalesInputs.forEach(function(inputEl) {
        var salesVal = parseFloat(inputEl.value) || 0;
        grandSales += salesVal;
        grandComm += (salesVal * 0.20);
        grandPayout += (salesVal - (salesVal * 0.20));
    });

    table.querySelector('.gtsales').innerText = grandSales.toFixed(2);
    table.querySelector('.gtcomm').innerText = grandComm.toFixed(2);
    table.querySelector('.gtpayout').innerText = grandPayout.toFixed(2);
}
</script>

@endsection