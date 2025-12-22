<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Helpers\DeleteImages;
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

    public static function getNavigationLabel(): string
    {
        if (auth()->user()->role->id === Role::getIdByRole('PENYEWA')) {
            return 'Profile'; // Change label for PENYEWA users
        }
        return 'users';
    }


    private static function checkPermission(string $action): bool
    {
        $permission = Permission::getPermissionByUserAndPermissionAndAction('User', $action);
        return isset($permission) && $permission->action;
    }

    public static function canView(Model $record): bool
    {

        $canSeeProfile = self::checkPermission('VIEWPAGE');

        if ($canSeeProfile) {
            return true;
        }
        return false;
    }

    public static function canAccess(): bool
    {
        $canView = self::checkPermission('ACCESS');

        if ($canView) {
            return true;
        }
        return true;
    }

    // Check if the user can view any data
    public static function canViewAny(): bool
    {
        $canView = self::checkPermission('READ');

        if (!$canView) {
            return false;
        }

        return true;
    }

    // Check if the user can create
    public static function canCreate(): bool
    {
        if (!self::canViewAny()) {
            return false;
        }
        return self::checkPermission('CREATE');
    }

    // Check if the user can edit
    public static function canEdit(Model $record): bool
    {
        if (!self::canViewAny()) {
            return false;
        }
        return self::checkPermission('UPDATE');
    }

    // Check if the user can delete
    public static function canDeleteAny(): bool
    {
        if (!self::canViewAny()) {
            return false;
        }
        return self::checkPermission('DELETE');
    }
    public static function canDelete(Model $record): bool
    {
        if (!self::canViewAny()) {
            return false;
        }
        return self::checkPermission('DELETE');
    }

    public static function getTitle(): string
    {
        $user = request()->route('record'); // Get the current record
        return "{$user->name} Data"; // Customize the title
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data diri')
                    ->schema([
                        TextInput::make('name')
                            ->label('nama penyewa')
                            ->required(),
                        TextInput::make('address')
                            ->label('Alamat')
                            ->required(),
                        TextInput::make('contact')
                            ->label('Kontak')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->required(),
                    ])->columns(2),
                Section::make('lampiran')
                    ->schema([
                        FileUpload::make('ktp_id')
                            ->label('KTP')
                            ->required()->directory("KTP")
                            ->required(fn($livewire) => !$livewire->record)
                            ->default(function ($record) {
                                // Check if the KTP ID exists and retrieve the path
                                $image = Image::where('id', $record->ktp_id)->first();
                                // Debugging untuk melihat nilai yang didapat
                                return url($image->path) ?? null;
                            })
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->where('role_id', Role::getIdByRole("PENYEWA"));
            })
            ->columns([
                TextColumn::make('name')
                    ->label('nama penyewa'),
                TextColumn::make('email')
                    ->label('email'),
                TextColumn::make('address')
                    ->label('Alamat'),
                TextColumn::make('contact')
                    ->label('Kontak'),
                ImageColumn::make('ktp_id')
                    ->label('KTP')->getStateUsing(callback: function ($record) {
                        // dd($record->id);
                        $image = Image::where('id', $record->ktp_id)->first();
                        // Debugging untuk melihat nilai yang didapat
                        return url($image->path) ?? null;
                    })
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
                            foreach ($records as $record) {
                                // Temukan gambar terkait berdasarkan tipe_room_id
                                $image = Image::where('room_id', $record->id)->first();

                                // Hapus file gambar menggunakan helper DeleteImages (pastikan helper sudah ada)
                                if ($image) {
                                    DeleteImages::DeleteImages($image->file_name);
                                }

                                // Hapus record dari tabel
                                $record->delete();
                            }
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
                TextEntry::make('name')
                    ->label('nama'),
                TextEntry::make('email')
                    ->label('email'),
                TextEntry::make('contact')
                    ->label('contact'),
                TextEntry::make('address')
                    ->label('Alamat'),
                ImageEntry::make('ktp_id')
                    ->label('KTP')->getStateUsing(callback: function ($record) {
                        $image = Image::where('id', $record->ktp_id)->first();
                        // Debugging untuk melihat nilai yang didapat
                        return url($image->path) ?? null;
                    })
                    ->width('100')
                    ->height('50'),
                // ])
            ]);
    }
}
