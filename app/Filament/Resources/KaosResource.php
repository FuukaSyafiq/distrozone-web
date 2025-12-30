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
use Filament\Tables\Filters\SelectFilter;
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
                SectionForm::make('Identitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_kaos')->default("Kaos busuk")
                            ->label('Nama')->required(),
                        TextInput::make('merek_kaos')->required()->default('Palazzo'),
                        TextInput::make('type_kaos')->required()->default('lengan panjang')
                            ->label('Tipe kaos'),
                        TextInput::make('warna_kaos')->required()
                            ->label('Warna')->default("blue"),
                        TextInput::make('ukuran')->default("L")
                            ->label('Ukuran')
                            ->required(),
                        TextInput::make('harga_jual')->numeric()->required()->default("100000")
                            ->label('Harga jual'),
                        TextInput::make('harga_pokok')->numeric()->required()->default("90000")
                            ->label('Harga pokok'),
                        TextInput::make('stok_kaos')->numeric()->required()->default("100")
                            ->label('Stok kaos'),
                    ]),

                SectionForm::make('Foto')
                    ->schema([
                        // View::make('filament.components.image-preview')
                        //     ->viewData(
                        //         function ($record) {
                        //             $images = Image::where('id_kaos', $record->id_kaos)->get();
                        //             return [
                        //                 'images' => $images ? $images : [],
                        //             ];
                        //         }
                        //     )
                        //     ->visible(fn($record) => filled($record?->image)),
                      
                        FileUpload::make('foto_kaos')
                            ->multiple()
                            ->label('Upload Foto')
                            ->image()
                            ->imageEditor()
                            ->directory('kaos')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Cari kaos...')
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
                    ->label('Harga jual')->money('IDR', true),
                TextColumn::make('harga_pokok')
                    ->label('Harga pokok')->money('IDR', true),
                TextColumn::make('stok_kaos')
                    ->label('Stok kaos'),
                TextColumn::make('nama_kaos')
                    ->searchable(),
                ImageColumn::make('image.path')->disk('s3')->label('Foto')
            ])
            ->filters([
                SelectFilter::make('merek_kaos')
                    ->label('Merek')
                    ->options(
                        fn() =>
                        Kaos::query()
                            ->distinct()
                            ->pluck('merek_kaos', 'merek_kaos')
                            ->toArray()
                    ),
                SelectFilter::make('stok_kaos')
                    ->label('Stok')
                    ->default('many')
                    ->options([
                        'out' => 'Habis',
                        'low' => 'Hampir Habis',
                        'many' => 'Banyak',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'out' => $query->where('stok_kaos', 0),
                            'low' => $query->whereBetween('stok_kaos', [1, 10]),
                            'many' => $query->where('stok_kaos', '>', 10),
                            default => $query,
                        };
                    })
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()->before(function ($record) {
                        $images = Image::where('id_kaos', $record->id_kaos)->get();
                        foreach ($images as $image) {
                            if ($image->path) {
                                Storage::disk('local')->delete($image->path);
                                Storage::disk('s3')->delete($image->path);
                            }
                            $image->delete();
                        }
                    })
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->before(function ($records) {
                        foreach ($records as $record) {
                            $images = Image::where('id_kaos', $record->id_kaos)->get();
                            foreach ($images as $image) {
                                dd($images);
                                if ($image->path) {
                                    Storage::disk('local')->delete($image->path);
                                    Storage::disk('s3')->delete($image->path);
                                }
                                $image->delete();
                            }
                        }
                    }),
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
