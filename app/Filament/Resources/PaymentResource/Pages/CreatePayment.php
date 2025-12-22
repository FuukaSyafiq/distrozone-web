<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Helpers\DeleteImages;
use App\Helpers\Invoice;
use App\Helpers\Sender;
use App\Models\RentedRoom;
use App\Models\Role;
use App\Models\Room;
use App\Models\Tagihan;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VerifikasiPembayaran;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Actions\Action;
use App\Helpers\GenerateMessage;
use App\Helpers\StoreImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    // redirect user ke Tabel tagihan lagi
    protected function getRedirectUrl(): string
    {
        return \App\Filament\Resources\TagihanResource::getUrl('index');
    }

    public  function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return "Payments";
    }

    public function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        $user = User::where('name', $data['user'])->first();

        try {
            // dd($data);
            $roomYangDisewa = Room::where('name', $data['room'])->first();
            $rentedRoom = RentedRoom::where('room_id', $roomYangDisewa->id)->where('user_id', $user->id)->first();

            $tagihanYangAkanDibayar = Tagihan::where('rented_room_id', $rentedRoom->id)->where('is_settled', false)->where('due_date', $data['due_date'])->first();

            if (auth()->user()->role_id === Role::getIdByRole('OWNER')) {
                $tagihanYangAkanDibayar->is_settled = true;
                $tagihanYangAkanDibayar->tanggal_dibayar = Carbon::now('utc');

                $tagihanYangAkanDibayar->save();
                $tanggalTagihan = Carbon::parse($tagihanYangAkanDibayar->due_date)->translatedFormat('Y-m-d');

                $message = GenerateMessage::whenCreatePayment($tanggalTagihan, $roomYangDisewa->name, $tagihanYangAkanDibayar->amount);
                Sender::SendToWhatsapp($user->contact, $message);

                $tagihanJatuhtempoTerakhir = Tagihan::where('rented_room_id', $rentedRoom->id)->orderBy('due_date', 'desc')->first();

                $invoice = Invoice::GenerateInvoiceNumber();

                Tagihan::create([
                    "amount" => $data['tagihan'],
                    "no_invoice" => $invoice,
                    "rented_room_id" => $rentedRoom->id,
                    "is_settled" => false,
                    "due_date" => Carbon::parse($tagihanJatuhtempoTerakhir->due_date)->addDays(30),  // 30 days after current due_date
                    "tanggal_notif" => Carbon::parse($tagihanJatuhtempoTerakhir->due_date)->addDays(25),  // 25 days from now
                ]);

                $buktiPembayaran = StoreImages::StoreImages($data['invoice_file']);

                VerifikasiPembayaran::create([
                    "is_valid" => true,
                    "pengirim" => $user->name,
                    "amount" => $data['tagihan'],
                    "tanggal_dibayar" => Carbon::now('utc'),
                    "room" => $roomYangDisewa->name,
                    "no_invoice" => $tagihanYangAkanDibayar->no_invoice,
                    "bukti_file" => $buktiPembayaran->id
                ]);


                DB::commit();
                return $user;
            }

            $roomYangDisewa = Room::where('id', $rentedRoom->room_id)->first();

            $buktiFile = StoreImages::StoreImages($data['invoice_file']);

            VerifikasiPembayaran::create([
                "is_valid" => false,
                "amount" => $data['tagihan'],
                "tanggal_dibayar" => Carbon::now('utc'),
                "pengirim" => $user->name,
                "room" => $roomYangDisewa->name,
                "no_invoice" => $tagihanYangAkanDibayar->no_invoice,
                "bukti_file" => $buktiFile->id
            ]);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            DeleteImages::DeleteImages($data['invoice_file']);

            throw $e;
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Bayar'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
