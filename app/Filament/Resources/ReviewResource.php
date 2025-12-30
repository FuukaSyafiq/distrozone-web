<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-c-star';
    protected static ?string $navigationGroup = 'Customer';

    public static function getBreadcrumb(): string
    {
        return '';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    // Check if the user can edit
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    // Check if the user can delete
    public static function canDeleteAny(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return true;
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
            ->columns([
                TextColumn::make('user.nama')
                    ->label('Customer'),
                TextColumn::make('kaos.nama_kaos')
                    ->label('Kaos'),
                TextColumn::make('review')
                    ->label('Review'),
                TextColumn::make('star')
                    ->label('Rating')
                    ->icon('heroicon-c-star')
                    ->color('warning'),
            ])
            ->filters([
                // SelectFilter::make('user_id')
                //     ->relationship(
                //         name:'user',
                //         titleAttribute: 'name',
                //     )
                //     ->label('Penyewa'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Penyewa'),
                Infolists\Components\TextEntry::make('room.name')->label('Kamar'),
                Infolists\Components\TextEntry::make('review')->label('Review'),
                Infolists\Components\TextEntry::make('star')->label('rating')->icon('heroicon-c-star')
                ->color('warning')
            ]);
    }
}
