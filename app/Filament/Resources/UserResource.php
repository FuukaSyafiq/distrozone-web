<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\NikVerified;
use App\Helpers\UserStatus;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\Section as SectionForm;
use Filament\Infolists\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?string $label = "Customer";

    protected static ?string $navigationGroup = 'Customer';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()->role_id === Role::getIdByRole('ADMIN') || auth()->user()->role_id === Role::getIdByRole('KASIR');
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
            ->modifyQueryUsing(function ($query) {
                return $query->where('role_id', Role::getIdByRole("CUSTOMER"));
            })
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('no_telepon')
                    ->label('No telepon'),
                TextColumn::make('nik')
                    ->label('NIK'),
                TextColumn::make('kota.kota')
                    ->label('Kota'),
                TextColumn::make('kota.provinsi.provinsi')
                    ->label('Provinsi'),
                TextColumn::make('alamat_lengkap')
                    ->label('Alamat'),
                TextColumn::make('nik_verified')->badge()
                    ->label('Status NIK'),
                TextColumn::make('status')->badge()
                    ->label('Status'),
                ImageColumn::make('foto_user')
                    ->label('Foto')
                    ->disk('s3')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    ActionTable::make('nik_verified')
                        ->label(fn($record) => match (true) {
                            $record->nik_verified == NikVerified::APPROVED && $record->status === UserStatus::ACTIVE => 'Suspend',
                            $record->nik_verified == NikVerified::APPROVED && $record->status === UserStatus::SUSPENDED => 'Activate',
                            $record->nik_verified == NikVerified::PENDING && $record->status === UserStatus::ACTIVE => 'Verifikasi'
                        })
                        ->icon(
                            fn($record) =>
                            $record->status === UserStatus::ACTIVE && $record->nik_verified == NikVerified::APPROVED
                                ? 'heroicon-o-check-circle'
                                : 'heroicon-o-pause-circle'
                        )
                        ->color(
                            fn($record) =>
                            $record->status === 'ACTIVE'  && $record->nik_verified == NikVerified::APPROVED
                                ? 'warning'
                                : 'success'
                        )
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            if ($record->status === UserStatus::ACTIVE && $record->nik_verified == NikVerified::APPROVED) {
                                $record->status = UserStatus::SUSPENDED;
                            } else if ($record->status == UserStatus::SUSPENDED && $record->nik_verified == NikVerified::APPROVED) {
                                $record->status = UserStatus::ACTIVE;
                            }
                            if ($record->nik_verified == NikVerified::PENDING)
                                $record->nik_verified = NikVerified::APPROVED;
                            $record->save();
                        }),
                    ActionTable::make('blokir')
                        ->label('Blokir')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->visible(fn($record) => $record->status !== 'BANNED' && $record->verified)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'BANNED',
                            ]);
                            $record->save();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation()->color('danger')
                        ->action(function (Collection $records) {}),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}')
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Identitas')->columns(3)
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('no_telepon')
                            ->label('No telepon'),
                        TextEntry::make('nik')
                            ->label('NIK'),
                        TextEntry::make('kota.kota')
                            ->label('Kota'),
                        TextEntry::make('kota.provinsi.provinsi')
                            ->label('Provinsi'),
                        TextEntry::make('alamat_lengkap')
                            ->label('Alamat'),
                        TextEntry::make('nik_verified')->badge()
                            ->label('Nik Terverifikasi'),
                    ])->footerActions([
                        Action::make('nik_verified')
                            ->label(fn($record) => match (true) {
                                $record->nik_verified == NikVerified::APPROVED && $record->status === UserStatus::ACTIVE => 'Suspend',
                                $record->nik_verified == NikVerified::APPROVED && $record->status === UserStatus::SUSPENDED => 'Activate',
                                $record->nik_verified == NikVerified::PENDING && $record->status === UserStatus::ACTIVE => 'Verifikasi'
                            })
                            ->icon(
                                fn($record) =>
                                $record->status === UserStatus::ACTIVE && $record->nik_verified == NikVerified::APPROVED
                                    ? 'heroicon-o-check-circle'
                                    : 'heroicon-o-pause-circle'
                            )
                            ->color(
                                fn($record) =>
                                $record->status === 'ACTIVE'  && $record->nik_verified == NikVerified::APPROVED
                                    ? 'warning'
                                    : 'success'
                            )
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                if ($record->status === UserStatus::ACTIVE && $record->nik_verified == NikVerified::APPROVED) {
                                    $record->status = UserStatus::SUSPENDED;
                                } else if ($record->status == UserStatus::SUSPENDED && $record->nik_verified == NikVerified::APPROVED) {
                                    $record->status = UserStatus::ACTIVE;
                                }
                                if ($record->nik_verified == NikVerified::PENDING)
                                    $record->nik_verified = NikVerified::APPROVED;
                                $record->save();
                            }),
                        Action::make('blokir')
                            ->label('Blokir')
                            ->icon('heroicon-o-no-symbol')
                            ->color('danger')
                            ->visible(fn($record) => $record->status !== 'BANNED' && $record->verified == NikVerified::APPROVED)
                            ->requiresConfirmation()
                            ->action(function ($record) {
                                $record->update([
                                    'status' => 'BANNED',
                                ]);
                                $record->save();
                            }),
                    ]),
                Section::make('Foto customer')
                    ->schema([
                        ImageEntry::make('foto_user')
                            ->label('Foto')
                            ->disk('s3')
                            ->height(300)      // optional
                    ])
            ]);
    }
}
