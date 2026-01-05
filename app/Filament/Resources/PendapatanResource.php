<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendapatanResource\Pages;
use App\Filament\Resources\PendapatanResource\RelationManagers;
use App\Models\Pendapatan;
use App\Models\TransaksiDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendapatanResource extends Resource
{
    protected static ?string $model = TransaksiDetail::class;

    protected static ?string $navigationIcon = 'heroicon-s-chart-bar';
    protected static ?string $navigationLabel = 'Pendapatan';
    protected static ?string $navigationGroup = 'Toko';


    public static function canCreate(): bool
    {
        return false;
    }

    public static function getBreadcrumb(): string
    {
        return '';
    }
    public static function canView(Model $record): bool
    {
        return true;
    }


    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPendapatans::route('/'),
            'create' => Pages\CreatePendapatan::route('/create'),
            'view' => Pages\ViewPendapatan::route('/{record}'),
            'edit' => Pages\EditPendapatan::route('/{record}/edit'),
        ];
    }
}
