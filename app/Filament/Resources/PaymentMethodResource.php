<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Symfony\Component\HttpFoundation\File\File;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationGroup = 'Toko';
    protected static ?string $navigationLabel = 'Metode Pembayaran';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_bank')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nomor_rekening')
                    ->required()->numeric()
                    ->maxLength(255),
                TextInput::make('nama_penerima')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->disk('s3')->image()
                    ->imageEditor()
                    ->required()
                    ->directory('payment-method'),
                TextInput::make('instruksi')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bank')->label('Nama Bank')->sortable()->searchable(),
                TextColumn::make('nomor_rekening')->label('Nomor Rekening')->sortable()->searchable(),
                TextColumn::make('nama_penerima')->label('Nama Penerima')->sortable()->searchable(),
                ImageColumn::make('logo')->label('Logo')->disk('s3'),
                IconColumn::make('is_active')->label('Is Active')->boolean()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(
                [
                    TextEntry::make('nama_bank')->label('Nama Bank'),
                    TextEntry::make('nomor_rekening')->label('Nomor Rekening'),
                    TextEntry::make('nama_penerima')->label('Nama Penerima'),
                    ImageEntry::make('logo')->label('Logo')->disk('s3'),
                    TextEntry::make('instruksi')->label('Instruksi'),
                    IconEntry::make('is_active')->label('Is Active')->boolean(),
                ]
            );
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
            'view' => Pages\ViewPaymentMethods::route('/{record}'),
        ];
    }
}
