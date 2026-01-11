<!DOCTYPE html>
<html>

<head>
    <title>Detail Transaction</title>
    <!-- Import Tailwind classes via Curlwind -->
    <link rel="stylesheet"
        href="https://cdn.curlwind.com?classes=text-center,text-2xl,font-bold,text-lg,italic,mb-4,mb-6,mt-6,p-6,mx-auto,my-24,my-auto,my-6,w-4/5,border,border-black,bg-gray-200,py-2,px-4,w-48,h-auto">
</head>

<body>

    <!-- Title -->
    <h1 class="text-center text-2xl font-bold mb-4">Transaction History</h1>
    <hr class="border-black mb-4">

    <!-- Table Section -->
    <div class="my-6">
        <table class="w-4/5 mx-auto my-24 border border-black">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-black py-2 px-4 text-center">Id</th>
                    <th class="border border-black py-2 px-4 text-center">No Invoice</th>
                    <th class="border border-black py-2 px-4 text-center">Pengirim</th>
                    <th class="border border-black py-2 px-4 text-center">Kamar</th>
                    <th class="border border-black py-2 px-4 text-center">Amount</th>
                    <th class="border border-black py-2 px-4 text-center">Tanggal Dibayar</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterate through each transaction -->
                @foreach ($records as $record)
                <tr>
                    <td class="border border-black py-2 px-4 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $record->no_invoice }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $record->pengirim }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $record->room }}</td>
                    <td class="border border-black py-2 px-4 text-center">Rp.{{ number_format($record->amount, 0, ',', '.') }}</td>
                    <td class="border border-black py-2 px-4 text-center">{{ $record->tanggal_dibayar }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
