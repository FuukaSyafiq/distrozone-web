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
    protected static ?int $navigationSort = 2;

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
        $user = auth()->user();

        // Check if the user is a "PENYEWA"
        if ($user->role->id === Role::getIdByRole('PENYEWA')) {
            // Check if the user has left a review
            $hasLeftReview = Review::where('user_id', $user->id)->exists();

            if ($hasLeftReview) {
                return true; // Allow viewing the Review module if a review exists
            }

            return false; // Deny access if no review has been left
        }

        // If the role is "OWNER", allow viewing all rented rooms
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
            ->modifyQueryUsing(function ($query) {
                if (auth()->user()->role_id == Role::getIdByRole("PENYEWA")) {
                    return $query->where('user_id', auth()->user()->id);
                }
                return $query;
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penyewa'),
                TextColumn::make('room.name')
                    ->label('Kamar'),
                TextColumn::make('review')
                    ->label('Review'),
                TextColumn::make('star')
                    ->label('rating')
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
                // SelectFilter::make('room_id')
                //     ->relationship(
                //         name: 'room',
                //         titleAttribute: 'name',
                //         modifyQueryUsing: fn(Builder $query, $get) => $query
                //          ->whereHas('role', fn($q) => $q->where('name', 'PENYEWA')))
                //     ->label('Kamar'),
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
