<?php

namespace App\Helpers;
// use App\Models\Transaction;

use App\Models\Pembayaran;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Kode
{
	public static function GenerateInvoiceNumber()
	{
		// Ambil nomor terakhir dari database
		$lastInvoice = Pembayaran::orderBy('id_pembayaran', 'desc')->first();

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
	
	public static function GenerateKodeTransaksi() {
		$date = Carbon::now()->format('Ymd'); // 20260101

		do {
			// Random 4 karakter (huruf besar + angka)
			$random = strtoupper(Str::random(4));

			$kode = "TRX-{$date}-{$random}";

			// cek ke DB biar 100% unik
			$exists = Transaksi::where('kode_transaksi', $kode)->exists();
		} while ($exists);

		return $kode;
	}
}
