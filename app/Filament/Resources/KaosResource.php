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
use Filament\Infolists\Components\Actions;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Infolists\Components\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaosResource extends Resource
{
    protected static ?string $model = Kaos::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Kaos";

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
                TextColumn::make('nama_kaos')
                    ->label('Nama'),
                TextColumn::make('merek_kaos')
                    ->label('Merek'),
                TextColumn::make('type_kaos')
                    ->label('Tipe'),
                TextColumn::make('warna_kaos')
                    ->label('Warna'),
                TextColumn::make('ukuran')
                    ->label('Ukuran'),
                TextColumn::make('harga_jual')
                    ->label('Harga jual'),
                TextColumn::make('harga_pokok')
                    ->label('Harga pokok'),
                TextColumn::make('stok_kaos')
                    ->label('Stok kaos'),
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
                        $image = Image::where('id_kaos', $record->id)->first();
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                SectionEntry::make('Informasi Kaos')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nama_kaos')
                            ->label('Nama'),
                        TextEntry::make('merek_kaos')
                            ->label('Merek'),
                        TextEntry::make('type_kaos')
                            ->label('Tipe'),
                        TextEntry::make('warna_kaos')
                            ->label('Warna'),
                        TextEntry::make('ukuran')
                            ->label('Ukuran'),
                        TextEntry::make('harga_jual')
                            ->label('Harga jual')
                            ->money('IDR'),
                        TextEntry::make('harga_pokok')
                            ->label('Harga pokok')
                            ->money('IDR'),
                        TextEntry::make('stok_kaos')
                            ->label('Stok kaos'),
                    ]),

                SectionEntry::make('Foto Kaos')
                    ->schema([
                        ImageEntry::make('image.path')
                            ->label('Foto')
                            ->disk('s3')
                            ->height(200)
                            ->columns(3), // gallery
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
                                foreach ($record->images as $image) {
                                    Storage::disk('s3')->delete($image->path);
                                    $image->delete();
                                }

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
