<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceVerificationResource\Pages;
use App\Filament\Resources\InvoiceVerificationResource\RelationManagers;
use App\Helpers\Sender;
use App\Models\VerifikasiPembayaran;
use App\Models\Image;
use App\Models\RentedRoom;
use App\Models\Role;
use App\Models\Room;
use App\Models\Tagihan;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use App\Helpers\GenerateMessage;
use App\Helpers\Invoice;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class InvoiceVerificationResource extends Resource
{
    protected static ?string $model = VerifikasiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Financial Transactions';
    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

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
                if (auth()->user()->role_id == Role::getIdByRole("PENYEWA")) {
                    $invoice = $query->where('pengirim', auth()->user()->name)->where('is_valid', false);
                    return $invoice;
                }
                return $query->where('is_valid', false);
            })
            ->columns([
                TextColumn::make('pengirim')
                    ->label('Pengirim'),
                TextColumn::make('room')
                    ->label('Kamar'),
                TextColumn::make('amount')
                    ->formatStateUsing(fn($state) => "Rp " . number_format($state, 0, ',', '.'))
                    ->label('Amount'),
                TextColumn::make('tanggal_dibayar')
                    ->label('Tanggal dibayar'),
                TextColumn::make('no_invoice')
                    ->label('Nomor Invoice'),
                BooleanColumn::make('is_valid')
                    ->label('Terverifikasi'),
                ImageColumn::make('invoice_file')
                    ->label('Bukti Pembayaran')
                    ->getStateUsing(callback: function ($record) {
                        $image = Image::where('id', $record->bukti_file)->first();
                        // Debugging untuk melihat nilai yang didapat
                        return url($image->path) ?? null;
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                action::make('verifyInvoice')
                    ->label('Verifikasi')
                    ->action(function (VerifikasiPembayaran $record) {
                        // Update the 'is_verified' field in register table
                        try {

                            DB::beginTransaction();
                            $room = Room::where('name', $record->room)->first();
                            $rentedRoom = RentedRoom::where('room_id', $room->id)->first();
                            $user = User::where('name', $record->pengirim)->first();
                            
                            $tagihan = Tagihan::where('rented_room_id', $rentedRoom->id)->where('no_invoice',$record->no_invoice)->first();

                            $tagihan->is_settled = true;
                            $tagihan->tanggal_dibayar = $record->tanggal_dibayar;
                            $tagihan->save();
                          
                            $record->update([
                                'is_valid' => true,
                            ]);

                            $tagihanJatuhtempoTerakhir = Tagihan::where('rented_room_id', $rentedRoom->id)->orderBy('due_date', 'desc')->first();

                            $invoice = Invoice::GenerateInvoiceNumber();

                            Tagihan::create([
                                "amount" => $record->amount,
                                "no_invoice" => $invoice,
                                "rented_room_id" => $rentedRoom->id,
                                "is_settled" => false,
                                "tanggal_dibayar" => null,
                                "due_date" => Carbon::parse($tagihanJatuhtempoTerakhir->due_date)->addDays(30),  // 30 days after current due_date
                                "tanggal_notif" => Carbon::parse($tagihanJatuhtempoTerakhir->due_date)->addDays(25),  // 25 days from now
                            ]);
                            $tanggalTagihan = Carbon::parse($tagihan->due_date)->translatedFormat('d F Y');
                            $message =  GenerateMessage::whenIsVerified($tanggalTagihan, $room->name, $tagihan->amount);
                            Sender::SendToWhatsapp($user->contact, $message);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            throw $e;
                        }
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(function (VerifikasiPembayaran $record) {
                        // only show the verify button if the user is not verified
                        return !$record->is_valid && auth()->user()->role_id === Role::getIdByRole("OWNER");
                    }),
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
            'index' => Pages\ListInvoiceVerifications::route('/'),
            'create' => Pages\CreateInvoiceVerification::route('/create'),
            'edit' => Pages\EditInvoiceVerification::route('/{record}/edit'),
        ];
    }
}
