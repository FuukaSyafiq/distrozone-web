<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UkuranKaosResource\Pages;
use App\Filament\Resources\UkuranKaosResource\RelationManagers;
use App\Models\UkuranKaos;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Role;


class UkuranKaosResource extends Resource
{
    protected static ?string $model = UkuranKaos::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';

    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Ukuran kaos";


    public static function canAccess(): bool
    {
        return false;
    }
   


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('ukuran')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ukuran')
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
            'index' => Pages\ListUkuranKaos::route('/'),
            'create' => Pages\CreateUkuranKaos::route('/create'),
            'edit' => Pages\EditUkuranKaos::route('/{record}/edit'),
        ];
    }
}
