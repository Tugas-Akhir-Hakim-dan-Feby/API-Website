@php
    use Carbon\Carbon;
    function formatRupiah($amount)
    {
        return 'Rp. ' . number_format($amount, 0, ',', '.');
    }
@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            font-family: Arial, sans-serif;
        }

        .invoice .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .invoice .header p {
            margin-top: 5px;
        }

        .invoice .content {
            margin-bottom: 40px;
        }

        .invoice .table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice .table th,
        .invoice .table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .invoice .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .invoice .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div class="header">
            <h1>Kode: {{ $payment->external_id }}</h1>
            <p>Tanggal: {{ Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->isoFormat('DD MMMM YY') }}</p>
        </div>
        <div class="content">
            <table class="table">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <b>{{ $payment->description }}</b>
                            <br />
                        </td>
                        <td>
                            {{ formatRupiah($payment->amount) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="total">
                            Total:
                        </td>
                        <td>
                            {{ formatRupiah($payment->amount) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
