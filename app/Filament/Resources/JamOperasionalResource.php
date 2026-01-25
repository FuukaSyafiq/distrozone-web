<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JamOperasionalResource\Pages;
use App\Filament\Resources\JamOperasionalResource\RelationManagers;
use App\Models\JamOperasional;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JamOperasionalResource extends Resource
{
    protected static ?string $model = JamOperasional::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Toko';

    public static function canAccess(): bool
    {
        return auth()->user()->role_id === Role::getIdByRole('ADMIN');
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
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
            ->paginated(false)
            ->columns([
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn(string $state) => $state === 'offline' ? 'gray' : 'info')
                    ->formatStateUsing(fn($state) => strtoupper($state)),

                TextColumn::make('hari')
                    ->label('Hari')
                    ->sortable()
                    ->formatStateUsing(fn($state) => ucfirst(strtolower($state))),
           
                TextInputColumn::make('jam_buka')
                    ->label('Jam Buka')
                    ->type('time')
                    ->disabled(fn($record) => $record->status !== 'BUKA'),

                TextInputColumn::make('jam_tutup')
                    ->label('Jam Tutup')
                    ->type('time')
                    ->disabled(fn($record) => $record->status !== 'BUKA'),
                SelectColumn::make('status')
                    ->options([
                        'BUKA' => 'BUKA',
                        'TUTUP' => 'TUTUP',
                    ])
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
            'index' => Pages\ListJamOperasionals::route('/'),
            'create' => Pages\CreateJamOperasional::route('/create'),
            'edit' => Pages\EditJamOperasional::route('/{record}/edit'),
        ];
    }
}
