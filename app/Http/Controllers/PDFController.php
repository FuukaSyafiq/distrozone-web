<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiPembayaran;
use App\Models\Image;
use App\Models\Kaos;
use App\Models\Pembayaran;
use App\Models\RentedRoom;
use App\Models\Room;
use App\Models\User;
use App\Models\Tagihan;
use App\Models\TipeRoom;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\PDF;

class PDFController extends Controller
{
    //Contoh pdf dengan fitur render gambar dari DB
    public function transactionpdf($id)
    {
        // Get transaction (single)
        $transaction = Transaksi::find($id);

        // Get the image path based on the bukti_file value
        $image = Image::where('id', $transaction->bukti_transfer)->first();

        //splice "/storage/" if exist so the link is not broken
        $image_path = $image->path;
        // if (strpos($image_path, '/storage/') === 0) {
        //     // Remove the '/storage/' from the path
        //     $image_path = substr($image_path, strlen('/storage/'));
        // }

        // // Get the full path to the image file
        // $filePath = storage_path('app/public/' . $image_path);
        // // dd($filePath);

        // Convert the image to base64 if the file exists
        $base64Image = null;
        if (file_exists($image_path)) {
            $imageData = file_get_contents($image_path);
            $imageType = pathinfo($image_path, PATHINFO_EXTENSION);
            $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
        }
        // dd($base64Image);

        // Prepare the data to pass to the PDF view
        $data = [
            'record' => $transaction,
            'image_base64' => $base64Image, // Pass the base64 encoded image
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.TransactionDetail', $data);

        // return $pdf->download('transaction-' . $transaction->id . '.pdf');
        return $pdf->stream('transaction.pdf');
    }

    public function allTransactionPdf()
    {
        // Get the authenticated user's name
        $pengirim = auth()->user()->name;

        // Fetch all valid transactions for the user
        $transactions = Transaksi::where('pengirim', $pengirim)
            ->where('is_valid', true)
            ->get();

        // Load the view for the PDF with all transactions
        $pdf = FacadePdf::loadView('pdf.TransactionHistory', ['records' => $transactions]);

        // Return the PDF as a stream
        return $pdf->stream('all-transactions.pdf');
    }

   
    public function cetak($id)
    {
        // Get the tagihan (single)
        // dd($no_invoice);
        $transaksi = Transaksi::where('id_transaksi', $id)->first();

        $user = User::find($transaksi->id_customer);

        $transaksiDetails = TransaksiDetail::where('id_transaksi', $transaksi->id_transaksi)->first();

        $kaos = Kaos::where('id_kaos', $transaksiDetails->id_kaos)->first();

        $data = [
            'record' => $transaksi,
            'user' => $user,
            'kaos' => $kaos,
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.TransactionHistory', $data);

        // return $pdf->download('tagihan-' . $tagihan->id . '.pdf');
        return $pdf->stream('transaksi.pdf');
    }
}
