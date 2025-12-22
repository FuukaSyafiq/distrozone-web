<?php

namespace App\Helpers;
class GenerateMessage {

	public static function whenIsVerified($tanggal, $kamar, $amount) {
		return "Pembayaran pada kamar '$kamar' sebesar Rp." .  number_format($amount, 0, ',', '.') . " untuk Tanggal $tanggal sudah diverifikasi";
	}

	public static function whenCreateTagihan($kamar, $amount) {
		return "ada Tagihan baru sebesar Rp." . number_format($amount, 0, ',', '.') . " untuk kamar '$kamar',Cek Aplikasi untuk detail lebih lanjut";
	}

	public static function whenCreatePayment($tanggal, $kamar, $amount) {
		return "tagihan sebesar Rp." . number_format($amount, 0, ',', '.') . " pada kamar '$kamar' untuk tanggal $tanggal Telah lunas!";
	}

	public static function whenAlmostDueDate($kamar, $amount) {
		return "Segera bayar tagihan untuk kamar $kamar, sejumlah Rp." . number_format($amount, 0, ',', '.');
	}
}
