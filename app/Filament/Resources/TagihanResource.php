<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagihanResource\Pages;
use App\Filament\Resources\TagihanResource\RelationManagers;
use App\Models\RentedRoom;
use App\Models\Role;
use App\Models\Tagihan;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-c-banknotes';
    protected static ?string $navigationGroup = 'Financial Transactions';

    public static function canCreate(): bool
    {
        return auth()->user()->role->id !== Role::getIdByRole('PENYEWA');
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->id === $record->id;
    }

    public static function form(Form $form): Form
    {
        $latestDueDate = Tagihan::latest('due_date')->value('due_date');
        $minDate = Carbon::parse($latestDueDate)->addMonth();

        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('rented_room_id')
                            ->label('Kamar')
                            ->relationship(
                                name: 'rentedRoom',
                                titleAttribute: 'rooms.name', // Access the room name through the rentedRoom relationship
                                modifyQueryUsing: fn(Builder $query) => $query->join('rooms', 'rented_rooms.room_id', '=', 'rooms.id')
                                    ->where('rooms.available', false)
                            )
                            ->required()
                            ->reactive() // Make this field reactive to trigger changes
                            ->afterStateUpdated(
                                fn(callable $set, $state) =>
                                // Fetch the price based on the selected room ID
                                $set('amount', RentedRoom::find($state)?->room->tipe_room?->price)
                            ),
                        TextInput::make('amount')
                            ->label('Jumlah Tagihan')
                            ->required()
                            ->prefix('Rp.')
                            ->reactive()
                            ->default(fn($get) => $get('amount'))
                            ->readOnly(true),
                        // DatePicker::make('due_date')
                        //     ->label('Tanggal jatuh tempo')
                        //     ->required()
                        //     ->reactive() // Make this field reactive
                        //     ->minDate($minDate),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        //Variable for filter section
        $unavailableRoomCount = Room::where('available', false)->count();
        // $tennants =  User::where('role_id', Role::getIdByRole('PENYEWA'))
        //     ->pluck('name', 'id')
        //     ->toArray();

        return $table
            ->modifyQueryUsing(function ($query) {
                if (auth()->user()->role_id === Role::getIdByRole("PENYEWA")) {
                    // Ambil semua rented_room_id yang dimiliki user
                    $rentedRoomIds = RentedRoom::where('user_id', auth()->user()->id)->pluck('id');


                    // Jika ada rented_room_ids, filter query dengan rented_room_id yang sesuai
                    $tagihan = $query->whereIn('rented_room_id', $rentedRoomIds);
                    // $tagihanYangBelumDibayar = $query->whereIn('rented_room_id', $rentedRoomIds)->where('is_settled', false);
                    // if (isset($tagihanYangBelumDibayar)) {
                    //     Notification::make('amount')->title("Ada tagihan yang belum dibayar")->warning()->send();
                    // }

                    return $tagihan;
                }

                return $query; // Kembalikan query asli jika bukan PENYEWA
            })
            ->columns([
                TextColumn::make('rentedRoom.room.name')->label('Kamar'),
                TextColumn::make('rentedRoom.user.name')->label('Penyewa'),
                TextColumn::make('amount')
                    ->label('Jumlah tagihan')->formatStateUsing(fn($state) => 'Rp. ' . number_format($state, 0, ',', '.')), // Memformat sebagai mata uang
                BooleanColumn::make('is_settled')
                    ->label('Sudah dibayar'),
                TextColumn::make('due_date')
                    ->label('Tanggal jatuh Tempo')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('tanggal_dibayar')
                    ->label('Tanggal dibayar')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('no_invoice')->label("No invoice"),
                TextColumn::make('tanggal_notif')
                    ->label('Tanggal pemberitahuan')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y'))
            ])
            ->filters([
                SelectFilter::make('rented_room_id')
                    ->relationship(
                        name: 'rentedRoom',
                        titleAttribute: 'rooms.name', // Access the room name through the rentedRoom relationship
                        modifyQueryUsing: fn(Builder $query) => $query->join('rooms', 'rented_rooms.room_id', '=', 'rooms.id')
                            ->where('rooms.available', false)
                    )
                    ->label('Kamar')
                    ->visible(
                        $unavailableRoomCount > 0
                    ),
                Filter::make('is_settled')
                    ->query(fn(Builder $query): Builder => $query->where('is_settled', true))
                    ->label('Sudah Dibayar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('PayTagihan')
                    ->label('Bayar Tagihan')
                    ->url(fn(Tagihan $record): string => route(
                        auth()->user()->role_id === Role::GetIdByRole('OWNER')
                            ? 'filament.owner.resources.payments.create'
                            : 'filament.penyewa.resources.payments.create',
                        [
                            'rented_room_id' => $record->rented_room_id, // Pass the rented_room_id
                            'amount' => $record->amount, // Pass the amount
                            'due_date' => $record->due_date
                        ]
                    ))
                    ->visible(fn(Tagihan $record): bool => $record->is_settled === false)
                    ->color('success')
                    ->icon('heroicon-o-credit-card'),
                Action::make('Cetak')
                    ->url(fn(Tagihan $record) => route('transaction.settled.pdf', $record))
                    ->visible(fn(Tagihan $record): bool => $record->is_settled === false)
                    ->openUrlInNewTab(),
                Action::make('Cetak kuitansi')
                    ->url(fn(Tagihan $record) => route('transaction.settled.pdf', $record))
                    ->visible(fn(Tagihan $record): bool => $record->is_settled === true)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagihans::route('/'),
            'create' => Pages\CreateTagihan::route('/create'),
            'edit' => Pages\EditTagihan::route('/{record}/edit'),
        ];
    }
}
