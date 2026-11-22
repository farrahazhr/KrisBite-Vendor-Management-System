@extends('layouts.vendor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/vendor/create_invoice.css') }}">
@endpush

@section('content')

<div class="w-100 vendor-invoice-page">

    <!-- MAIN CARD -->
    <div class="invoice-main-card">

        <!-- PAGE HEADER -->
        <div class="invoice-page-header">
            <h2 class="page-title">Invoice & Settlement Page</h2>
            <p class="page-description">
                Submit your daily invoices and review your invoice history and settlement status.
            </p>
        </div>

        <div class="invoice-page-content">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- BAHAGIAN 1: DAILY INVOICE (BORANG) -->
            <div class="invoice-section">

                <div class="invoice-section-header">
                    <h4 class="invoice-section-title">
                        Daily Invoice & Settlement ({{ now()->format('d/m/Y') }})
                    </h4>
                </div>

                <form action="{{ route('vendor.invoice.store') }}" method="POST">
                    @csrf

                    <!-- Vendor Pilih Tarikh -->
                    <div class="mb-3" style="max-width: 250px;">
                        <label class="form-label fw-bold">Select Invoice Date</label>

                        <input type="date"
                               name="invoice_date"
                               class="form-control invoice-input"
                               value="{{ date('Y-m-d') }}"
                               required>
                    </div>

                    <div class="table-responsive invoice-table-wrapper">

                        <table class="table invoice-table" id="invoiceTable">

                            <thead>
                                <tr>
                                    <th>Food Item</th>
                                    <th>Qty Send</th>
                                    <th>Price (RM)</th>
                                    <th>Total Amount (RM)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="itemsBody">
                                <tr>
                                    <td>
                                        <select name="items[0][food_id]"
                                                class="form-control food-select"
                                                onchange="setPrice(this)"
                                                required>

                                            <option value="">-- Select Food --</option>

                                            @foreach($foods as $food)
                                                <option value="{{ $food->id }}"
                                                        data-price="{{ $food->price }}">
                                                    {{ $food->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </td>

                                    <td>
                                        <!-- Qty Send -->
                                        <input type="number"
                                            name="items[0][declared_qty]"
                                            class="form-control qty-input"
                                            min="1"
                                            placeholder="0"
                                            required
                                            oninput="calcTotal(this)"
                                            style="width: 100px;"> 
                                    </td>
                                    <td>
                                        <!-- Price (RM) -->
                                        <input type="number"
                                            step="0.01"
                                            name="items[0][price]"
                                            class="form-control price-input"
                                            min="0"
                                            placeholder="0.00"
                                            required
                                            readonly
                                            oninput="calcTotal(this)"
                                            style="width: 120px; background-color: #F8F9FA; font-weight: 600;">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control total-input"
                                               readonly
                                               value="0.00"
                                               style="width: 140px; background-color: #F8F9FA;">
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-danger invoice-remove-btn"
                                                onclick="removeRow(this)">
                                            X
                                        </button>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                    </div>

                    <div class="invoice-form-actions">

                        <button type="button"
                                class="btn invoice-add-btn"
                                onclick="addRow()">
                            <i class="bi bi-plus-lg"></i>
                            Add New Item
                        </button>

                        <button type="submit" class="btn invoice-submit-btn">
                            Submit Invoice
                        </button>

                    </div>

                </form>

            </div>

            <div class="invoice-section-divider"></div>

            <!-- BAHAGIAN 2: INVOICE HISTORY -->
            <div class="invoice-section">

                <div class="invoice-section-header">
                    <h4 class="invoice-section-title">
                        Invoice History & Receipts
                    </h4>
                </div>

                @if($invoices->count() > 0)

                    <div class="table-responsive invoice-table-wrapper">

                        <table class="table table-hover invoice-table">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Total Items</th>
                                    <th>Total Amount (RM)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($invoices as $inv)
                                <tr>
                                    <td>{{ $inv->invoice_date }}</td>

                                    <td>
                                        INV-{{ str_pad($inv->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>

                                    <td>{{ $inv->items->count() }}</td>

                                    <td>
                                        RM {{ number_format($inv->items->sum(function($item) { return $item->declared_qty * $item->price; }), 2) }}
                                    </td>

                                    <td>
                                        @if($inv->settlement_status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($inv->status == 'pending')
                                            <span class="badge bg-info text-dark">Pending Tally</span>
                                        @elseif($inv->status == 'verified')
                                            <span class="badge bg-secondary">Verified</span>
                                        @elseif($inv->status == 'settled')
                                            <span class="badge bg-warning text-dark">Pending Settlement</span>
                                        @endif
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm invoice-view-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#invModal{{ $inv->id }}">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="text-center text-muted py-4">
                        <h5>No invoice history yet.</h5>
                    </div>

                @endif

            </div>

        </div>
    </div>
</div>

<!-- MODAL POPUP FOR INVOICE HISTORY -->
@foreach($invoices as $inv)

<div class="modal fade vendor-invoice-modal"
     id="invModal{{ $inv->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Invoice INV-{{ str_pad($inv->id, 3, '0', STR_PAD_LEFT) }}
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <p><b>Date:</b> {{ $inv->invoice_date }}</p>

                <div class="table-responsive invoice-modal-table-wrapper">

                    <table class="table table-bordered invoice-modal-table">

                        <thead>
                            <tr>
                                <th>Food Item</th>
                                <th>Qty Claimed</th>
                                <th>Price (RM)</th>
                                <th>Total (RM)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $grandTotal = 0; @endphp

                            @foreach($inv->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->declared_qty }}</td>
                                <td>RM {{ number_format($item->price, 2) }}</td>
                                <td>RM {{ number_format($item->declared_qty * $item->price, 2) }}</td>

                                @php $grandTotal += $item->declared_qty * $item->price; @endphp
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">
                                    Grand Total:
                                </td>

                                <td class="fw-bold text-success">
                                    RM {{ number_format($grandTotal, 2) }}
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn invoice-close-btn"
                        data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@endforeach

<!-- ORIGINAL JAVASCRIPT - FUNCTIONALITY UNCHANGED -->
<script>

let rowIndex = 1;

let foodOptionsHtml = `<option value="">-- Select Food --</option>`;

@foreach($foods as $food)
    foodOptionsHtml += `<option value="{{ $food->id }}" data-price="{{ $food->price }}">{{ $food->name }}</option>`;
@endforeach

function calcTotal(input) {

    var row = input.closest('tr');

    var qty = row.querySelector('.qty-input').value;

    var price = row.querySelector('.price-input').value;

    var total = (qty * price) || 0;

    row.querySelector('.total-input').value = total.toFixed(2);

}

function addRow() {

    const tableBody = document.getElementById('itemsBody');

    const row = `
        <tr>
            <td>
                <select name="items[${rowIndex}][food_id]" class="form-control food-select" onchange="setPrice(this)" required>
                    ${foodOptionsHtml}
                </select>
            </td>
            <td><input type="number" name="items[${rowIndex}][declared_qty]" class="form-control qty-input" min="1" required oninput="calcTotal(this)" style="width: 100px;"></td>
            <td><input type="number" step="0.01" name="items[${rowIndex}][price]" class="form-control price-input" min="0" required readonly oninput="calcTotal(this)" style="width: 120px; background-color: #F8F9FA; font-weight: 600;"></td>
            <td><input type="text" class="form-control total-input" readonly value="0.00" style="width: 140px; background-color: #F8F9FA;"></td>
            <td><button type="button" class="btn btn-sm btn-danger invoice-remove-btn" onclick="removeRow(this)">X</button></td>
        </tr>`;

    tableBody.insertAdjacentHTML('beforeend', row);

    rowIndex++;

}

function setPrice(selectElement) {

    var selectedOption = selectElement.options[selectElement.selectedIndex];

    var price = selectedOption.getAttribute('data-price');

    var row = selectElement.closest('tr');

    row.querySelector('.price-input').value = price;

    // Auto-kira terus bila set harga (kalau vendor dah isi kuantiti awal)
    calcTotal(row.querySelector('.qty-input'));

}

function removeRow(button) {

    button.closest('tr').remove();

}

</script>

@endsection