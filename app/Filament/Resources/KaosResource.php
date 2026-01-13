<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaosResource\Pages;
use App\Filament\Resources\KaosResource\RelationManagers;
use App\Models\Kaos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\View;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section as SectionForm;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables;
use Filament\Infolists\Components\Section as SectionEntry;
use Filament\Tables\Table;
use Filament\Infolists\Components\ImageEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use App\Models\Kota;
use Filament\Infolists\Components\Actions;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Infolists\Components\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Warna;
use App\Models\KaosVariant;
use Filament\Forms\Components\Group;

class KaosResource extends Resource
{
    protected static ?string $model = Kaos::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Kaos";

    public static function form(Form $form): Form
    {
        return $form->schema([
            SectionForm::make('Informasi Kaos')
                ->schema([
                    TextInput::make('nama_kaos')
                        ->label('Nama Kaos')
                        ->required()
                        ->maxLength(255),

                    Select::make('merek_id')
                        ->label('Merek')
                        ->relationship('merek', 'merek') // pastikan relasi 'merek' di model Kaos ada
                        ->required(),

                    Select::make('type_id')
                        ->label('Type')
                        ->relationship('type', 'type') // pastikan relasi 'type' di model Kaos ada
                        ->required(),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->maxLength(1000),

                    TextInput::make('harga_jual')
                        ->label('Harga Jual')
                        ->numeric()
                        ->required(),

                    TextInput::make('harga_pokok')
                        ->label('Harga Pokok')
                        ->numeric()
                        ->required(),
                ])
                ->columns(2), // tampil 2 kolom untuk layout lebih rapi
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Cari kaos...')
            ->columns([
                TextColumn::make('nama_kaos')
                    ->label('Nama'),
                TextColumn::make('merek.merek')
                    ->label('Merek'),
                TextColumn::make('type.type')
                    ->label('Tipe'),
                TextColumn::make('harga_jual')
                    ->label('Harga jual')->money('IDR', true),
                TextColumn::make('harga_pokok')
                    ->label('Harga pokok')->money('IDR', true),
                TextColumn::make('nama_kaos')
                    ->searchable(),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionEntry::make('Informasi Kaos')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nama_kaos')
                            ->label('Nama'),

                        TextEntry::make('merek.merek')
                            ->label('Merek'),

                        TextEntry::make('type.type')
                            ->label('Tipe'),
                        TextEntry::make('harga_jual')
                            ->label('Harga jual')
                            ->money('IDR'),

                        TextEntry::make('harga_pokok')
                            ->label('Harga pokok')
                            ->money('IDR'),
                    ]),

                SectionEntry::make('Deskripsi')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Deskripsi'),
                    ]),

                Actions::make([
                    Action::make('edit')
                        ->label('Edit')
                        ->icon('heroicon-o-pencil')
                        ->url(fn($record) => static::getUrl('edit', ['record' => $record])),

                    Action::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            Storage::disk('s3')->delete($record->image_path);
                            $record->delete();
                        }),
                ])
                    ->columns(2),
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
            'index' => Pages\ListKaos::route('/'),
            'create' => Pages\CreateKaos::route('/create'),
            'view' => Pages\ViewKaos::route('/{record}'),
            'edit' => Pages\EditKaos::route('/{record}/edit'),
        ];
    }
}
