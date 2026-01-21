<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerifikasiPembayaran;
use App\Models\Image;
use App\Models\Kaos;
use App\Models\Pembayaran;
use App\Models\Pendapatan;
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

    public function cetakpendapatan($ids, Request $request) {
        $idArray = explode(',', $ids);

        // ambil banyak data
        $pendapatan = Pendapatan::whereIn('id', $idArray)->get();

        $totalKeuntungan = $pendapatan->sum(function ($record) {
            return $record->keuntungan;
        });


        $data = [
            'records' => $pendapatan,
            'start_date' => $request->query('start_date'),
            'end_date'   => $request->query('end_date'),
            'totalKeuntungan' => $totalKeuntungan
        ];


        $pdf = FacadePdf::loadView('pdf.pendapatan', $data)->setPaper('A4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        // return $pdf->download('transaction-' . $transaction->id . '.pdf');
        return $pdf->stream('pendapatan.pdf');
    }


    public function cetaktransaksi($id, Request $request)
    {
        // Get transaction (single)
        $pembayaran = Pembayaran::where('id_transaksi', $id)->first();
        $transaksi_detail = TransaksiDetail::where('id_transaksi', $id)->get();

        // Get the image path based on the bukti_file value
        // $image = Image::where('id', $pembayaran->bukti_transfer)->first();

        $data = [
            'transaksi' => $pembayaran->transaksi,
            'pembayaran' => $pembayaran,
            'transaksi_detail' => $transaksi_detail,
            'bukti_transfer' => $pembayaran->transaksi->bukti_transfer,
            'date' => $request->query('date')
        ];

        // Load the view for the PDF
        $pdf = FacadePdf::loadView('pdf.transaksi', $data)->setPaper('A4', 'portrait')
            ->setOption('isRemoteEnabled', true);;

        // return $pdf->download('transaction-' . $transaction->id . '.pdf');
        return $pdf->stream('transaksi.pdf');
    }
}
