<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarnaKaosResource\Pages;
use App\Filament\Resources\WarnaKaosResource\RelationManagers;
use App\Models\Warna;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WarnaKaosResource extends Resource
{
    protected static ?string $model = Warna::class;

    protected static ?string $navigationIcon = 'heroicon-s-paint-brush';
    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Warna kaos";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('key')->label('Key'),
                TextInput::make('label')->label('Label'),
            ColorPicker::make('hex')
                ->label('Hex')
                ->required()
                ->default('#ff0000'), // default warna
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->label('Key'),
                TextColumn::make('label')->label('Label'),
                TextColumn::make('hex')->label('Hex')->formatStateUsing(function ($state) {
                    return '<div style="
                        width: 30px;
                        height: 20px;
                        background-color: '.$state.';
                        border: 1px solid #ccc;
                        border-radius: 3px;
                    "></div>';
                })
                ->html()
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
            'index' => Pages\ListWarnaKaos::route('/'),
            'create' => Pages\CreateWarnaKaos::route('/create'),
            'edit' => Pages\EditWarnaKaos::route('/{record}/edit'),
        ];
    }
}
