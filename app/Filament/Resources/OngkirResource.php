<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OngkirResource\Pages;
use App\Filament\Resources\OngkirResource\RelationManagers;
use App\Models\Kota;
use App\Models\Ongkir;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OngkirResource extends Resource
{
    protected static ?string $model = Ongkir::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-bar';
    protected static ?string $navigationLabel = 'Ongkir';
    protected static ?string $navigationGroup = 'Toko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('wilayah')
                    ->label('Wilayah')
                    ->options(
                        Kota::query()
                            ->orderBy('kota')
                            ->pluck('kota', 'kota')
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('tarif_per_kg')
                    ->label('Tarif')->required()->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wilayah')
                    ->label('Wilayah'),
                TextColumn::make('tarif_per_kg')
                    ->label('Tarif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOngkirs::route('/'),
            'create' => Pages\CreateOngkir::route('/create'),
            'edit' => Pages\EditOngkir::route('/{record}/edit'),
        ];
    }
}
