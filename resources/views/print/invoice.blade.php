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
    <title>Faktur {{ $payment->external_id }}</title>
    <style>
        @font-face {
            font-family: SourceSansPro;
            src: url(SourceSansPro-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: 20.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 70px;
        }

        #company {
            float: right;
            text-align: right;
        }


        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 20px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #555555;
            font-size: 1.6em;
        }

        table .desc {
            text-align: left;
            text-transform: capitalize;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            color: #555555;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ public_path() }}/assets/images/api-iws.png">
        </div>
        <div id="company">
            <h2 class="name">Asosiasi Pengelasan Indonesia</h2>
            <div>Graha Surveyor Indonesia Lt. 14
                Jl. Jenderal Gatot Subroto Kavling 56 Jakarta,
                12950</div>
            <div>+62-21-5265526</div>
            <div><a href="mailto:api-iws@yahoogroups.com">api-iws@yahoogroups.com</a></div>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">Faktur Untuk:</div>
                <h2 class="name">{{ $payment->user->name }}</h2>
                <div class="email"><a href="mailto:{{ $payment->user->email }}">{{ $payment->user->email }}</a></div>
            </div>
            <div id="invoice">
                <h1>{{ $payment->external_id }}</h1>
                <div class="date">Tanggal Pembayaran:
                    {{ Carbon::createFromFormat('Y-m-d H:i:s', $payment->updated_at)->isoFormat('DD MMMM YY') }}</div>
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc" colspan="2">DESKRIPSI</th>
                    <th class="qty"></th>
                    <th class="qty"></th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="no">1</td>
                    <td class="desc" colspan="2">
                        {{ $payment->description }}
                    </td>
                    <td class="qty"></td>
                    <td class="qty"></td>
                    <td class="total">{{ formatRupiah($payment->amount) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2">Total Pembayaran</td>
                    <td>{{ formatRupiah($payment->amount) }}</td>
                </tr>
            </tfoot>
        </table>
        <div id="thanks">Terima Kasih!</div>
    </main>
    <footer>
        Faktur dibuat di komputer dan sah tanpa tanda tangan dan stempel.
    </footer>
</body>

</html>
