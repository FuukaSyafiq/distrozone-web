<?php

namespace App\Helpers;

class Estimated
{
	public static function calculate($user)
	{

		// Pastikan mengambil data provinsi dan diubah ke uppercase agar cocok dengan case
		$provinsi = $user->kota->provinsi->provinsi;
		$estimatedMessage = "";
		switch ($provinsi) {
			
			// Dekat Jakarta (Hitungan Jam)
			case 'DKI JAKARTA':
			case 'BANTEN':
			case 'JAWA BARAT':
				$estimatedMessage = "3 - 6 Jam (Sameday/Instant)";
				break;

			// Masih di Pulau Jawa (Harian)
			case 'JAWA TENGAH':
			case 'DI YOGYAKARTA':
				$estimatedMessage = "1 - 2 Hari Kerja";
				break;
				
			case 'JAWA TIMUR':
				$estimatedMessage = "3 - 4 Hari Kerja";
			// Kalimantan dan Selain Pulau Jawa (Mingguan)
			default:
				// Case ini akan menangkap semua provinsi di luar Jawa termasuk Kalimantan, 
				// Sumatera, Sulawesi, Papua, Maluku, dan Nusa Tenggara.
				$estimatedMessage = "1 - 2 Minggu";
				break;
		}

		return $estimatedMessage;
	}
}
