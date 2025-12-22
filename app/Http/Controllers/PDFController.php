<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiPembayaran;
use App\Models\Image;
use App\Models\RentedRoom;
use App\Models\Room;
use App\Models\User;
use App\Models\Tagihan;
use App\Models\TipeRoom;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\PDF;

class PDFController extends Controller
{
    //Contoh pdf dengan fitur render gambar dari DB
    public function transactionpdf($id)
    {
        // Get transaction (single)
        $transaction = VerifikasiPembayaran::find($id);

        // Get the image path based on the bukti_file value
        $image = Image::where('id', $transaction->bukti_file)->first();

        //splice "/storage/" if exist so the link is not broken
        $image_path = $image->path;
        if (strpos($image_path, '/storage/') === 0) {
            // Remove the '/storage/' from the path
            $image_path = substr($image_path, strlen('/storage/'));
        }

        // Get the full path to the image file
        $filePath = storage_path('app/public/' . $image_path);
        // dd($filePath);

        // Convert the image to base64 if the file exists
        $base64Image = null;
        if (file_exists($filePath)) {
            $imageData = file_get_contents($filePath);
            $imageType = pathinfo($filePath, PATHINFO_EXTENSION);
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
        $transactions = VerifikasiPembayaran::where('pengirim', $pengirim)
            ->where('is_valid', true)
            ->get();

        // Load the view for the PDF with all transactions
        $pdf = FacadePdf::loadView('pdf.TransactionHistory', ['records' => $transactions]);

        // Return the PDF as a stream
        return $pdf->stream('all-transactions.pdf');
    }

    public function allSelectedUserTransactionPdf(Request $request)
    {
        // Get the selected 'pengirim' value
        $pengirim = $request->get('pengirim');

        // Fetch transactions based on 'pengirim' or fetch all if 'All' is selected
        if ($pengirim === 'all') {
            $transactions = VerifikasiPembayaran::where('is_valid', true)->get();
        } else {
            $transactions = VerifikasiPembayaran::where('pengirim', $pengirim)
                ->where('is_valid', true)
                ->get();
        }

        // Load the view for the PDF with the filtered transactions
        $pdf = FacadePdf::loadView('pdf.TransactionHistory', ['records' => $transactions]);

        // Return the PDF as a stream
        return $pdf->stream('selected-transactions.pdf');
    }
    public function allUserTransactionPdf()
    {
        // Get the authenticated user's name
        $pengirim = auth()->user()->name;

        // Fetch all valid transactions for the user
        $transactions = VerifikasiPembayaran::where('pengirim', $pengirim)
            ->where('is_valid', true)
            ->get();

        // return $transactions;
        //  Load the view for the PDF with all transactions
        $pdf = FacadePdf::loadView('pdf.UserTransactionHistory', ['records' => $transactions]);

        //  Return the PDF as a stream
        return $pdf->stream('all-transactions.pdf');
    }
 
    public function cetakbuktibayar($id)
    {
        // Get the tagihan (single)
        $transaction = VerifikasiPembayaran::where('id', $id)->first();
        // dd($transaction);
        $tagihan = Tagihan::where('no_invoice', $transaction->no_invoice)->first();

        $rentedRoom = RentedRoom::find($tagihan->rented_room_id);
        $user = User::find($rentedRoom->user_id);

        $room = Room::find($rentedRoom->room_id);
        $tipeRoom = TipeRoom::find($room->tipe_room_id);


        $data = [
            'record' => $tagihan,
            'user' => $user,
            'room' => $room,
            "rented_room" => $rentedRoom,
            "tipe_room" => $tipeRoom
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.TagihanInvoice', $data);

        // return $pdf->download('tagihan-' . $tagihan->id . '.pdf');
        return $pdf->stream('tagihan.pdf');
    }

    public function cetak($id)
    {
        // Get the tagihan (single)
        // dd($no_invoice);
        $tagihan = Tagihan::where('id', $id)->first();

        $rentedRoom = RentedRoom::find($tagihan->rented_room_id);
        $user = User::find($rentedRoom->user_id);

        $room = Room::find($rentedRoom->room_id);
        $tipeRoom = TipeRoom::find($room->tipe_room_id);


        $data = [
            'record' => $tagihan,
            'user' => $user,
            'room' => $room,
            "rented_room" => $rentedRoom,
            "tipe_room" => $tipeRoom
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.TagihanInvoice', $data);

        // return $pdf->download('tagihan-' . $tagihan->id . '.pdf');
        return $pdf->stream('tagihan.pdf');
    }


    public function tagihanpdf($id)
    {
        // Get the tagihan (single)
        $tagihan = Tagihan::find($id);

        $rentedRoom = RentedRoom::find($tagihan->rented_room_id);

        $user = User::find($rentedRoom->user_id);

        $room = Room::find($rentedRoom->room_id);
        $tipeRoom = TipeRoom::find($room->tipe_room_id);


        $data = [
            'title' => "INVOICE",
            'record' => $tagihan,
            'user' => $user,
            'room' => $room,
            "rented_room" => $rentedRoom,
            "tipe_room" => $tipeRoom
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.TagihanInvoice', $data);

        // return $pdf->download('tagihan-' . $tagihan->id . '.pdf');
        return $pdf->stream('tagihan.pdf');
    }
}
