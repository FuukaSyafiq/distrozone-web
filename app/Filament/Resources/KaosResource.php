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
    protected static ?string $model = KaosVariant::class;

    protected static ?string $navigationIcon = 'heroicon-m-archive-box';
    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Kaos";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SectionForm::make('Identitas Kaos')
                    ->schema([
                        Group::make()
                            ->relationship('kaos')
                            ->schema([
                                TextInput::make('nama_kaos')
                                    ->label('Nama Kaos')
                                    ->required(),

                                Select::make('merek_id')
                                    ->label('Merek')
                                    ->relationship('merek', 'merek')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('type_id')
                                    ->label('Tipe Kaos')
                                    ->relationship('type', 'type')
                                    ->required(),

                                TextInput::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),

                                TextInput::make('harga_jual')
                                    ->numeric()
                                    ->label('Harga Jual')
                                    ->required(),

                                TextInput::make('harga_pokok')
                                    ->numeric()
                                    ->label('Harga Pokok')
                                    ->required(),
                            ])
                            ->columns(2),
                    ]),
                SectionForm::make('Varian')
                    ->schema([
                        Select::make('warna_id')
                            ->label('Warna')
                            ->relationship('warna', 'label')
                            ->searchable()
                            ->required(),

                        Select::make('ukuran_id')
                            ->label('Ukuran')
                            ->relationship('ukuran', 'ukuran')
                            ->required(),

                        TextInput::make('stok_kaos')
                            ->numeric()
                            ->label('Stok')
                            ->required(),
                    ])
                    ->columns(3),

                SectionForm::make('Foto Kaos')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Foto')
                            ->disk('s3')
                            ->directory('kaos')
                            ->image()
                            ->imageEditor()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Cari kaos...')
            ->columns([
                TextColumn::make('kaos.nama_kaos')
                    ->label('Nama'),
                TextColumn::make('kaos.merek.merek')
                    ->label('Merek'),
                TextColumn::make('kaos.type.type')
                    ->label('Tipe'),
                TextColumn::make('warna.label')
                    ->label('Warna'),
                TextColumn::make('ukuran.ukuran')
                    ->label('Ukuran'),
                TextColumn::make('kaos.harga_jual')
                    ->label('Harga jual')->money('IDR', true),
                TextColumn::make('kaos.harga_pokok')
                    ->label('Harga pokok')->money('IDR', true),
                TextColumn::make('stok_kaos')
                    ->label('Stok kaos'),
                TextColumn::make('kaos.nama_kaos')
                    ->searchable(),
                ImageColumn::make('image_path')->disk('s3')->label('Foto')
            ])
            ->filters([
                SelectFilter::make('stok_kaos')
                    ->label('Stok')
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
                        TextEntry::make('kaos.nama_kaos')
                            ->label('Nama'),

                        TextEntry::make('kaos.merek.merek')
                            ->label('Merek'),

                        TextEntry::make('kaos.type.type')
                            ->label('Tipe'),

                        TextEntry::make('warna.label')
                            ->label('Warna'),

                        TextEntry::make('ukuran.ukuran')
                            ->label('Ukuran'),

                        TextEntry::make('kaos.harga_jual')
                            ->label('Harga jual')
                            ->money('IDR'),

                        TextEntry::make('kaos.harga_pokok')
                            ->label('Harga pokok')
                            ->money('IDR'),

                        TextEntry::make('stok_kaos')
                            ->label('Stok kaos'),
                    ]),

                SectionEntry::make('Deskripsi')
                    ->schema([
                        TextEntry::make('kaos.description')
                            ->label('Deskripsi'),
                    ]),

                SectionEntry::make('Foto Kaos')
                    ->schema([
                        ImageEntry::make('image_path')
                            ->label('Foto')
                            ->disk('s3')
                            ->height(200),
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
