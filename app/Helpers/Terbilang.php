<?php

namespace App\Helpers;


class Terbilang
{
	public  function terbilang($angka)
	{
		$angka = (float)$angka;
		$bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

		if ($angka < 12) {
			return $bilangan[$angka];
		} elseif ($angka < 20) {
			return $this->terbilang($angka - 10) . ' Belas';
		} elseif ($angka < 100) {
			return $this->terbilang($angka / 10) . ' Puluh ' . $this->terbilang($angka % 10);
		} elseif ($angka < 200) {
			return 'Seratus ' . $this->terbilang($angka - 100);
		} elseif ($angka < 1000) {
			return $this->terbilang($angka / 100) . ' Ratus ' . $this->terbilang($angka % 100);
		} elseif ($angka < 2000) {
			return 'Seribu ' . $this->terbilang($angka - 1000);
		} elseif ($angka < 1000000) {
			return $this->terbilang($angka / 1000) . ' Ribu ' . $this->terbilang(fmod($angka, 1000));
		} elseif ($angka < 1000000000) {
			return $this->terbilang($angka / 1000000) . ' Juta ' . $this->terbilang(fmod($angka, 1000000));
		}

		return $angka;
	}
	public function formatRupiahTerbilang($angka)
	{
		return $this->terbilang($angka) . ' Rupiah';
	}
}

