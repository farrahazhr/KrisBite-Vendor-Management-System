
@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/settlement.css') }}">
@endpush

@section('content')

<div class="container-fluid admin-settlement-page">

    <!-- Main Settlement Card -->
    <div class="settlement-main-card">

        <!-- Card Header -->
        <div class="settlement-page-header">
            <h2 class="page-title">Vendor Settlement Approvals</h2>
            <p class="page-description">
                Review sales, commission (20%), and vendor payouts before approving settlements.
            </p>
        </div>

        <!-- Card Content -->
        <div class="settlement-card-content">

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Section Header -->
            <div class="settlement-section-header">
                <h5 class="settlement-section-title">
                    Pending Settlements
                </h5>
            </div>

            <!-- Settlement Table -->
            <div class="table-responsive settlement-table-wrapper">
                <table class="table settlement-table">

                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Date</th>
                            <th>Total Sales (RM)</th>
                            <th>Commission 20% (RM)</th>
                            <th>Vendor Payout (RM)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($invoices->count() > 0)

                            @foreach($invoices as $invoice)
                                <tr>
                                    <td class="vendor-name">
                                        {{ $invoice->vendor->name }}
                                    </td>

                                    <td>
                                        {{ $invoice->invoice_date }}
                                    </td>

                                    <td class="amount-cell">
                                        RM {{ number_format($invoice->total_sales, 2) }}
                                    </td>

                                    <td class="amount-cell commission-amount">
                                        - RM {{ number_format($invoice->commission_amount, 2) }}
                                    </td>

                                    <td class="amount-cell payout-amount">
                                        RM {{ number_format($invoice->net_payout, 2) }}
                                    </td>

                                    <td>
                                        <span class="settlement-status status-pending">
                                            Pending Approval
                                        </span>
                                    </td>

                                    <td>
                                        <div class="settlement-actions">

                                            <!-- View Receipt -->
                                            <button type="button"
                                                    class="settlement-btn btn-receipt"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#adminReceiptModal{{ $invoice->id }}">
                                                View Receipt
                                            </button>

                                            <!-- Approve Settlement -->
                                            <a href="{{ route('admin.settlements.approve', $invoice->id) }}"
                                               class="settlement-btn btn-approve"
                                               onclick="return confirm('Are you sure you want to approve this settlement?')">
                                                Approve
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        @else

                            <tr>
                                <td colspan="7" class="empty-state">
                                    Currently, there are no settlements pending approval.
                                </td>
                            </tr>

                        @endif
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

<!-- Receipt Details Modals -->
@foreach($invoices as $log)

<div class="modal fade settlement-receipt-modal"
     id="adminReceiptModal{{ $log->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">
                        Receipt Details - {{ $log->vendor->name }}
                    </h5>
                </div>

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
                        class="settlement-btn btn-close-receipt"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@endforeach

@endsection