<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasirResource\Pages;
use App\Filament\Resources\KasirResource\RelationManagers;
use App\Models\Kasir;
use App\Models\User;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\View;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\Section as SectionForm;
use Filament\Infolists\Components\Actions\Action;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Models\Image;
use Filament\Tables;
use Filament\Infolists\Components\Section as SectionEntry;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Facades\Hash;

class KasirResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-user';
    protected static ?string $label = "Kasir";
    protected static ?string $navigationGroup = 'Toko';

    public static function canAccess(): bool
    {
        return auth()->user()->role_id === Role::getIdByRole('ADMIN');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SectionForm::make('Identitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')->default("testing")
                            ->label('Nama')->required(),
                        TextInput::make('username')->required()->dehydrateStateUsing(fn($state) => strtolower($state))
                            ->label('Username')->default("testing"),
                        TextInput::make('password')->required(fn(string $context) => $context === 'create')
                            ->label('Password')->password()->default("password")->dehydrated(fn($state) => filled($state))
                    ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                        TextInput::make('email')->email()->required()
                            ->label('Email')->default("testing@gmail.com"),
                        TextInput::make('no_telepon')->default("089123821321")
                            ->label('No telepon')
                            ->tel()
                            ->numeric()
                            ->minLength(9)
                            ->maxLength(15)
                            ->live()
                            ->helperText('Harus diawali 0 dan 9–15 digit angka')
                            ->required(),
                        TextInput::make('nik')->tel()->numeric()->required()->default("35165232645332")
                            ->label('NIK')->maxLength(16)->helperText('NIK Harus 16 digit angka'),
                        TextArea::make('alamat')->default("sukodono")
                            ->label('Alamat')->required()
                    ]),

                SectionForm::make('Foto')
                    ->schema([
                View::make('filament.components.image-preview')
                    ->viewData(fn($record) => [
                        'images' => $record?->foto_id
                        ? [Image::find($record->foto_id)]
                        : [],
                    ])
                ->visible(fn($record) => filled($record?->foto_id)),
                FileUpload::make('foto_karyawan')
                            ->label('Upload Foto')
                            ->image()
                            ->disk('local')
                    ->imageEditor()
                            ->directory('foto_karyawan')
                    ]),
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
                TextColumn::make('no_telepon')
                    ->label('No telepon'),
                TextColumn::make('nik')
                    ->label('NIK'),
                TextColumn::make('alamat')
                    ->label('Alamat'),

                ImageColumn::make('image.path')
                    ->label('Foto')
                    ->disk('s3')
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->before(function ($record) {
                        $image = Image::where('id', $record->foto_id)->first();
                        if ($image->path) {
                            Storage::disk('local')->delete($image->path);
                            Storage::disk('s3')->delete($image->path);
                        }
                    })
                ]),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionEntry::make('Identitas')->columns(3)
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama'),
                        TextEntry::make('username')
                            ->label('Username'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('no_telepon')
                            ->label('No telepon'),
                        TextEntry::make('nik')
                            ->label('NIK'),
                        TextEntry::make('alamat')
                            ->label('Alamat'),
                    ]),
                SectionEntry::make('Foto karyawan')
                    ->schema([
                        ImageEntry::make('image.path')
                            ->label('Foto')
                            ->disk('s3')
                            ->height(300)
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKasirs::route('/'),
            'create' => Pages\CreateKasir::route('/create'),
            'edit' => Pages\EditKasir::route('/{record}/edit'),
            'view' => Pages\ViewKasir::route('/{record}')
        ];
    }
}
