<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* div {
            line-height: 15px;
        } */

        body {
            font-family: Arial, sans-serif;
            color: black;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            /* margin: 0 auto; */
            padding: 20px;
        }

        .header {
            display: flex;
            /* Use flexbox */
            justify-content: center;
            /* Center items horizontally */
            align-items: center;
            /* Center items vertically */
        }

        .header img {
            /* margin-right: 20px; */
            /* Add some spacing between image and text */
        }

        .header-text {
            display: inline-block;
            width: 50%;
            text-align: center;
            margin-bottom: 30px;
            padding-left: 150px
        }

        .header h1 {
            margin: 0;
            /* Remove default margin */
            font-size: 36px;
            /* Adjust font size as needed */
        }

        .header p {
            margin: 0;
            /* Remove default margin */
            font-size: 14px;
            /* Adjust font size as needed */
        }

        .lunas {
            width: 100%;
        }

        p {
            line-height: 10px;
        }

        .info-section-wrap {
            display: block;
            width: 100%;
        }

        .info-section {
            display: inline-block;
            /* justify-content: space-between; */
            width: 50%;
            /* margin-bottom: 30px; */
        }

        .info-section-2 {
            display: inline-block;
            /* justify-content: space-between; */
            width: 40%;
            /* margin-bottom: 30px; */
        }

        .table-section {
            width: 90%;
            margin: 0px auto;
            border-collapse: collapse;
        }

        .table-section th,
        .table-section td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        .table-section thead {
            background-color: #f0f0f0;
        }

        .additional-info {
            width: 70%;
            /* margin: 20px auto; */
            font-size: 16px;
        }

        .terbilang {
            width: 70%;
            /* margin: 20px auto; */
            font-size: 16px;
        }

        .payment-info,
        .terms {
            width: 70%;
            /* margin: 20px auto; */
            font-size: 16px;
        }

        .terms p {
            margin-top: 20px;
        }

        .signature {
            margin-right: 10px;
            font-size: 16px;
            text-align: right;
            justify-content: right;
        }

        .lunas {
            /* width: 100%;  */
            position: relative;
        }

        .lunas img {
            /* position: absolute; */
            margin-top: 10px;
            right: 100px;
            height: 120px;
        }

    </style>
</head>

<body>
    <!-- Container -->
    <div class="container">
        <!-- Logo and Header -->
        <div class="header">
            <img src="{{ public_path('assets/rumah.png') }}" height="100" width="150" alt="rumah Logo">
            {{-- <div> --}}
            <div class="header-text"> <!-- Wrap text in a div -->
                <h1>{{ $record->is_settled == true ? 'Kwitansi' : 'Invoice' }}</h1>
                <p>{{config('app.name')}} - Mantingan, Ngawi, Jawa Timur</p>
            </div>
            {{-- </div> --}}
        </div>

        <!-- Invoice Info -->
        <div class="info-section-wrap">
            <div class="info-section">
                <p><strong>Nomor Invoice:</strong> {{ $record->no_invoice }}</p>
                <p><strong>Penyewa :</strong> {{ $user->name }}</p>
                <p><strong>Alamat :</strong> {{ $user->address }}</p>
                <p><strong>Tanggal Sewa :</strong>
                    {{ Carbon\Carbon::parse($rented_room->rent_time)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="info-section-2">
                <p><strong>Date :</strong> {{ $record->created_at }}</p>
                <p><strong>No. Telp :</strong>{{ $user->contact }}</p>
                <p><strong>Pembayaran :</strong> Kos Bulan
                    {{ Carbon\Carbon::parse($record->due_date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <!-- Table Section -->
        <table class="table-section">
            <thead>
                <tr>
                    <th>Jatuh Tempo</th>
                    <th>Kamar</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ Carbon\Carbon::parse($record->due_date)->translatedFormat('d F Y') }}</td>
                    <td>{{ $room->name }}</td>
                    <td>Rp. {{ number_format($record->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Additional Info -->
        {{-- <div class="additional-info"> --}}
        <p><strong>Tipe Kamar:</strong> {{ $tipe_room->tipe }}</p>
        <p><strong>Fasilitas:</strong> {{ $tipe_room->facility }}</p>
        {{-- </div> --}}

        <!-- Terbilang Section -->
        {{-- <div class="terbilang"> --}}
        <strong>Terbilang :</strong>
        {{ (new \App\Helpers\Terbilang())->formatRupiahTerbilang($record->amount) }}
        {{-- </div> --}}

        <!-- Payment Instructions -->
        {{-- <div class="payment-info"> --}}
        <p><strong>Pembayaran:</strong>No. Rekening BCA 021874327498312327483272 A.N Junawati</p>
        {{-- </div> --}}

        <!-- Terms and Conditions -->
        {{-- <div class="terms"> --}}
        <strong>Syarat dan Ketentuan:</strong> Apabila terjadi kerusakan di karenakan penggunaan maka hal
        tersebut akan menjadi tanggung jawab penyewa.
        
        {{-- </div> --}}
        @if ($record->is_settled == true)
            <div class="lunas">
                <img src="{{ public_path('assets/lunas.jpg') }}" />
            </div>
        @endif
        
        <!-- Signature -->
        <div class="signature">
            <p>Hormat Kami,</p>
            <p>{{config('app.name')}}</p>
        </div>

</body>

</html>
