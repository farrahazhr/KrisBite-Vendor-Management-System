<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        .table th, .table td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Settlement KrisBite</h1>
        <h3>Dijana Pada: {{ date('d-m-Y H:i:s') }}</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Tarikh</th>
                <th>Vendor</th>
                <th>Jualan (RM)</th>
                <th>Komisen (RM)</th>
                <th>Payout (RM)</th>
            </tr>
        </thead>
        <tbody>
            @php $total_sales = 0; $total_comm = 0; $total_payout = 0; @endphp
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ $invoice->vendor->name }}</td>
                <td>{{ number_format($invoice->total_sales, 2) }}</td>
                <td>{{ number_format($invoice->commission_amount, 2) }}</td>
                <td>{{ number_format($invoice->net_payout, 2) }}</td>
            </tr>
            @php
                $total_sales += $invoice->total_sales;
                $total_comm += $invoice->commission_amount;
                $total_payout += $invoice->net_payout;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td colspan="2">JUMLAH KESELURUHAN</td>
                <td>RM {{ number_format($total_sales, 2) }}</td>
                <td>RM {{ number_format($total_comm, 2) }}</td>
                <td>RM {{ number_format($total_payout, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>