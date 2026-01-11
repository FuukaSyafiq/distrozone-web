<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Sender
{
	public static function SendToWhatsapp($noTelp, $text)
	{
		try {
			// $apiUrl = env("API_WHATSAPP_URL");
			// // dd($apiUrl);
			// Http::asForm()->post($apiUrl, [
			// 	'no_telepon' => $noTelp,
			// 	'isi_pesan' => $text,
			// 	'app_name' => 'ojt_kost'
			// ]);
			
		} catch (\Exception $e) {
			dd($e->getMessage());
			throw $e;
		}
	}
}
