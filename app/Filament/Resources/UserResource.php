<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\DeleteImages;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Components\Tab;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data diri')
                    ->schema([
                        TextInput::make('nama')
                            ->label('nama penyewa')
                            ->required(),
                        TextInput::make('alamat')
                            ->label('Alamat')
                            ->required(),
                        TextInput::make('no_telepon')
                            ->label('Kontak')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(),
                    ])->columns(2),
                // Section::make('lampiran')
                //     ->schema([
                //         FileUpload::make('ktp_id')
                //             ->label('KTP')
                //             ->required()->directory("KTP")
                //             ->required(fn($livewire) => !$livewire->record)
                //             ->default(function ($record) {
                //                 // Check if the KTP ID exists and retrieve the path
                //                 $image = Image::where('id', $record->ktp_id)->first();
                //                 // Debugging untuk melihat nilai yang didapat
                //                 return url($image->path) ?? null;
                //             })
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->where('role_id', Role::getIdByRole("KASIR"));
            })
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama'),
                TextColumn::make('username')
                    ->label('Username'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('role.role')
                    ->label('Role'),
                TextColumn::make('no_telepon')
                    ->label('No telepon'),
                TextColumn::make('nik')
                    ->label('NIK'),
                TextColumn::make('alamat')
                    ->label('Alamat'),
                ImageColumn::make('images.path')
                    ->disk('s3')->label("tes"),
                ImageColumn::make('images.path')
                    ->label('Foto'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation()->color('danger')
                        ->action(function (Collection $records) {

                        }),
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
                // Section::make('')
                //     ->schema([
                TextEntry::make('nama')
                    ->label('nama'),
                TextEntry::make('email')
                    ->label('email'),
                TextEntry::make('no_telepon')
                    ->label('contact'),
                TextEntry::make('alamat')
                    ->label('Alamat'),
                // ImageEntry::make('ktp_id')
                //     ->label('KTP')->getStateUsing(callback: function ($record) {
                //         $image = Image::where('id', $record->ktp_id)->first();
                //         // Debugging untuk melihat nilai yang didapat
                //         return url($image->path) ?? null;
                //     })
                //     ->width('100')
                //     ->height('50'),
                // ])
            ]);
    }
}
