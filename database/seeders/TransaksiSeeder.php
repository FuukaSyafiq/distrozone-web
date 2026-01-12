<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\Kode;
use App\Models\Image;
use App\Models\Kaos;
use App\Models\KaosVariant;
use App\Models\Ongkir;
use App\Models\Pendapatan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'nota-2.jpg';

        $disk = Storage::disk('s3'); // atau 'public', 'local'

        $image = Image::create([
            'path'      => $path,
            'file_name' => basename($path),
            'mime_type' => $disk->mimeType($path),
            'size'      => $disk->size($path),
        ]);

        $customer = User::getUserByName('Mas Aril');
        $kasir = User::getUserByName('Mbak Lua');
        $kaos = Kaos::getKaosByName('Kaos biru polo lengan pendek');
        $kaos_varian = KaosVariant::getKaosVarian($kaos->nama_kaos);
        $quantity = 2;
        $hargaSatuan = $kaos->harga_jual;
        $subtotal = $quantity * $hargaSatuan;
        $ongkir = Ongkir::getOngkirByWilayah('Jakarta');
        $total_ongkir = $ongkir->tarif_per_kg * $quantity;
        
        $transaksi = Transaksi::create([
            'kode_transaksi' => Kode::GenerateKodeTransaksi(),
            'jenis_transaksi' => 'ONLINE',
            'metode_pembayaran' => 'TRANSFER',
            'total_harga' => $subtotal + $total_ongkir,
            'id_customer' => $customer->id_user,
            'id_kasir' => $kasir->id_user,
            'id_ongkir' => $ongkir->id,
            'ongkir' => $total_ongkir,
            'status' => 'PENDING'
        ]);
        TransaksiDetail::create([
            'id_kaos_varian' => $kaos_varian->id,
            'id_transaksi' => $transaksi->id_transaksi,
            'harga_satuan' => $kaos->harga_jual,
            'qty' => $quantity,
            'subtotal' => $subtotal,
        ]);

        Pembayaran::create([
            'status' => "MENUNGGU",
            'no_invoice' => Kode::GenerateInvoiceNumber(),
            'id_transaksi' => $transaksi->id_transaksi,
            'bukti_transfer' => $image->id,
        ]);

        Pendapatan::create([
            'qty' => $quantity,
            'nama_kaos' => $kaos->nama_kaos,
            'total_harga_jual' => $subtotal,
            'total_harga_pokok' => $kaos->harga_pokok * $quantity,
            'ongkir' => $total_ongkir
        ]);
    }

    public static function down()
    {
        TransaksiDetail::query()->delete();
        Pembayaran::query()->delete();
        Transaksi::query()->delete();
    }
}
