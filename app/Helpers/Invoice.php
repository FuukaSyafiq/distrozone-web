<?php

namespace App\Helpers;
// use App\Models\Transaction;
use App\Models\Tagihan;
use App\Models\VerifikasiPembayaran;
use Carbon\Carbon;

class Invoice
{
	public static function GenerateInvoiceNumber()
	{
		// Ambil nomor terakhir dari database
		$lastInvoice = Tagihan::orderBy('id', 'desc')->first();

		if ($lastInvoice) {
			$lastInvoiceNumber = intval(substr($lastInvoice->no_invoice, 4, 4)); // Ambil angka dari no_invoice (misal 'INV-0001' -> 0001)
			$newInvoiceNumber = $lastInvoiceNumber + 1;
		} else {
			$newInvoiceNumber = 1;
		}

		$newInvoiceNumberFormatted = str_pad($newInvoiceNumber, 4, '0', STR_PAD_LEFT); // Format dengan 4 digit angka

		// Dapatkan tahun, bulan, dan tanggal saat ini menggunakan Carbon atau fungsi date
		$datePart = Carbon::now()->format('Y-m-d'); // Jika menggunakan Carbon
		// Atau
		// $datePart = date('Y-m-d'); // Jika menggunakan PHP native date function

		// Gabungkan nomor invoice dengan tanggal
		$no_invoice = 'INV-' . $newInvoiceNumberFormatted . '/' . $datePart;


		return $no_invoice;
	}
}
