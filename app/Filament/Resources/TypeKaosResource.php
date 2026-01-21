<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeKaosResource\Pages;
use App\Filament\Resources\TypeKaosResource\RelationManagers;
use App\Models\TypeKaos;
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
class TypeKaosResource extends Resource
{
    protected static ?string $model = TypeKaos::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';

    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Type kaos";


public static function canAccess(): bool
{
    return auth()->user()->role_id === Role::getIdByRole('ADMIN');
}


    public static function form(Form $form): Form


    {
        return $form
            ->schema([
                TextInput::make('type')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')

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
            'index' => Pages\ListTypeKaos::route('/'),
            'create' => Pages\CreateTypeKaos::route('/create'),
            'edit' => Pages\EditTypeKaos::route('/{record}/edit'),
        ];
    }
}
