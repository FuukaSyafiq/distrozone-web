<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KotaResource\Pages;
use App\Filament\Resources\KotaResource\RelationManagers;
use App\Models\Kota;
use App\Models\Provinsi;
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

use App\Models\Role;

class KotaResource extends Resource
{
    protected static ?string $model = Kota::class;
    protected static ?string $navigationGroup = 'Toko';
    protected static ?string $navigationIcon = 'heroicon-s-home-modern';



    public static function canAccess(): bool
    {
        return auth()->user()->role_id === Role::getIdByRole('ADMIN');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kota')
                    ->label('Kota')->required(),
                Select::make('provinsi_id')
                    ->label('Provinsi')
                    ->options(
                        Provinsi::query()
                            ->orderBy('provinsi')
                            ->pluck('provinsi', 'id')
                    )
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kota')
                    ->label('Kota'),
                TextColumn::make('provinsi.provinsi')
                    ->label('Provinsi'),
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
            'index' => Pages\ListKotas::route('/'),
            'create' => Pages\CreateKota::route('/create'),
            'edit' => Pages\EditKota::route('/{record}/edit'),
        ];
    }
}
