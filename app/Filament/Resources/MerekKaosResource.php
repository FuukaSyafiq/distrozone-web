<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerekKaosResource\Pages;
use App\Filament\Resources\MerekKaosResource\RelationManagers;
use App\Models\MerekKaos;
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

class MerekKaosResource extends Resource
{
    protected static ?string $model = MerekKaos::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';

    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Merek kaos";


    public static function canAccess(): bool
    {
        return auth()->user()->role_id === Role::getIdByRole('ADMIN');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('merek')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('merek')
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
            'index' => Pages\ListMerekKaos::route('/'),
            'create' => Pages\CreateMerekKaos::route('/create'),
            'edit' => Pages\EditMerekKaos::route('/{record}/edit'),
        ];
    }
}
