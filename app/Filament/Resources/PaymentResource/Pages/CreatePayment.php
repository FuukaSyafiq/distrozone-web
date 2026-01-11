<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Helpers\DeleteImages;
use App\Helpers\Kode;
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
   
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Bayar'),
            $this->getCancelFormAction()->label('Batal'),
        ];
    }
}
