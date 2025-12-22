<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;


class BulkPDFController extends Controller
{
    public function generate()
    {
        $data = session('bulk_pdf_records', []);
        dd($data);
    
        $pdf = PDF::loadView('pdf.bulk-transaction', ['records' => $data]);
    
        return response()->streamDownload(fn() => print($pdf->output()), 'transactions.pdf');
    }
}
