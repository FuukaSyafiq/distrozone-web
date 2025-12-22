<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use App\Models\RentedRoom;
use App\Models\Role;
use App\Models\Room;
use App\Models\Tagihan;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\Action;
use App\Helpers\GenerateMessage;
use App\Helpers\Sender;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class NotificationResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static ?string $navigationIcon = 'heroicon-s-bell';
    protected static ?string $navigationLabel = 'Notification';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        if (auth()->user()->role_id === Role::getIdByRole("OWNER")) {
            return true;
        }
        return false;
    }

    public static function getBreadcrumb(): string
    {
        return 'Notification';
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
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
        return auth()->user()->role->id !== Role::getIdByRole('PENYEWA');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->whereDate('tanggal_notif', '>=', Carbon::today())->where('is_settled', false);
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
                TextColumn::make('tanggal_notif')
                    ->label('Tanggal pemberitahuan')->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('d F Y'))

            ])
            ->filters([
                //
            ])
            ->actions([
                // Parameter Model di (Model $record) boleh diganti dengan nama Model
                // Tables\Actions\EditAction::make(),
                Action::make('notifyUser')
                    ->label('Notify User')
                    ->action(function (Model $record) {
                        try {
                            DB::beginTransaction();
                            $roomYangDisewa = RentedRoom::where('id', $record->rented_room_id)->first();
                            $room = Room::where('id', $roomYangDisewa->room_id)->first();
                            $user = User::where('id', $roomYangDisewa->user_id)->first();
                            $tagihan = Tagihan::where('no_invoice', $record->no_invoice)->first();
                            // dd($tagihan);
                            $message = GenerateMessage::whenAlmostDueDate($room->name,$tagihan->amount);
                           Sender::SendToWhatsapp($user->contact, $message);
                            DB::commit();
                        } catch (\Exception $exception) {
                            DB::rollBack();
                            throw $exception;
                        }
                    })
                    ->requiresConfirmation()
                    ->color('warning')
                    ->icon('heroicon-s-paper-airplane')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('notifyUser')
                        ->icon('heroicon-s-paper-airplane')
                        ->requiresConfirmation()
                        ->color('warning')
                        ->action(function (Collection $records) {
                            DB::beginTransaction();
                            try {
                                foreach ($records as $record) {
                                    $roomYangDisewa = RentedRoom::where('id', $record->rented_room_id)->first();
                                    $tagihan = Tagihan::where('rented_room_id', $roomYangDisewa->id)->first();
                                    $room = Room::where('id', $roomYangDisewa->room_id)->first();
                                    $user = User::where('id', $roomYangDisewa->user_id)->first();

                                    $message = GenerateMessage::whenAlmostDueDate($room->name);
                                    SendToWhatsapp($user->contact, $message);
                                }
                            } catch (\Exception $e) {
                                DB::rollBack();
                                throw $e;
                            }
                        }),
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            // 'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
