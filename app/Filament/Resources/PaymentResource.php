<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\RentedRoom;
use App\Models\Room;
use App\Models\User;
use App\Models\Role;
use App\Models\Tagihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Request;
use Filament\Forms\Components\DatePicker;

class PaymentResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getBreadcrumb(): string
    {
        return '';
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    protected static bool $shouldRegisterNavigation = false;

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';

    protected static ?string $navigationLabel = 'Payment';
    protected static ?string $navigationGroup = 'Financial Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user')
                    ->label('Penyewa')
                    ->default(function () {
                        // Set the default user based on the rented_room_id
                        $rentedRoomId = Request::query('rented_room_id');
                        $rentedRoom = RentedRoom::find($rentedRoomId);
                        $user = User::find($rentedRoom->user_id);
                        return $user ? $user->name : null;
                    })
                    ->required()
                    ->readOnly(true),
                TextInput::make('room')
                    ->label('Kamar')
                    ->default(function () {
                        // Get the rented_room_id from the query parameter
                        $rentedRoomId = Request::query('rented_room_id');
                        $rentedRoom = RentedRoom::find($rentedRoomId);

                        // Find the associated room and return its name
                        $room = $rentedRoom ? Room::find($rentedRoom->room_id) : null;
                        return $room ? $room->name : null;
                    })
                    ->required()
                    ->readOnly(true),
                TextInput::make('due_date')
                    ->label('Pilih Jatuh Tempo')
                    ->default(fn() => Request::query('due_date'))
                    ->readOnly(true)
                    ->required(),
                // DatePicker::make('tanggal_dibayar')
                //         ->hidden(true)
                //         // ->visible(fn($get) => auth()->user()->role_id === Role::getIdByRole("OWNER"))
                //         ->label("Tanggal dibayar")
                //         ->default(now()),
                TextInput::make('tagihan')
                    ->label('Tagihan')
                    // ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->prefix("Rp.")
                    ->reactive()
                    ->required()
                    ->readOnly(true) // Make the field reactive to updates->
                    ->default(fn() => Request::query('amount')),
                Section::make('lampiran')
                    ->schema([
                        FileUpload::make('invoice_file')
                            ->required()
                            ->label('Bukti pembayaran')
                            ->required(fn($get) => auth()->user()->role_id === Role::getIdByRole("PENYEWA"))
                            ->directory("INVOICE")
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreatePayment::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            // 'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    // public static function routes()
    // {
    //     return [
    //         'index' => function () {
    //             return redirect()->route('filament.resources.payment.create');
    //         },
    //         'edit' => function () {
    //             return redirect()->route('filament.resources.payment.create');
    //         },
    //     ];
    // }
}
