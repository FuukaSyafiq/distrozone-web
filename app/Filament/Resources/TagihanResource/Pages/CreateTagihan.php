<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use App\Filament\Resources\TagihanResource;
use App\Helpers\Sender;
use App\Models\RentedRoom;
use App\Models\Room;
use App\Models\Tagihan;
use App\Models\TipeRoom;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use App\Helpers\GenerateMessage;
use App\Helpers\Invoice;
use  \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;

  
    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();
        try {
            $tagihanTerakhir = Tagihan::where('rented_room_id', $data['rented_room_id'])->orderBy('due_date', 'desc')->first();
            $rentedRoom = RentedRoom::where('id', $data['rented_room_id'])->first();
            $user = User::where('id', $rentedRoom->user_id)->first();
            $room = Room::where('id', $rentedRoom->room_id)->first();
            $tipeRoom = TipeRoom::where('id', $room->tipe_room_id)->first();

            $invoice = Invoice::GenerateInvoiceNumber();

            $record = static::getModel()::create([
                "amount" => $tipeRoom->price,
                "no_invoice" => $invoice,
                "is_settled" => false,
                "rented_room_id" => $data['rented_room_id'],
                "due_date" => Carbon::parse($tagihanTerakhir->due_date)->addDays(30),
                "tanggal_notif" => Carbon::parse($tagihanTerakhir->due_date)->addDays(25)
            ]);

            $message = GenerateMessage::whenCreateTagihan($room->name, $record->amount);
            Sender::SendToWhatsapp($user->contact, $message);

            $record->save();
            DB::commit();
            return $record;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Buat'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
