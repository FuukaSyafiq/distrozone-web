<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaosVarianResource\Pages;
use App\Filament\Resources\KaosVarianResource\RelationManagers;
use App\Models\KaosVarian;
use App\Models\KaosVariant;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section as SectionForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction; // ini Tables EditAction, bukan general modal EditAction
use Filament\Forms\Form;
use Filament\Tables\Actions\EditAction; // ini Tables EditAction, bukan general modal EditAction
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Textarea;
use App\Models\Role;

class KaosVarianResource extends Resource
{
    protected static ?string $model = KaosVariant::class;
    protected static ?string $navigationGroup = 'Kaos';
    protected static ?string $label = "Varian Kaos";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   
    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SectionForm::make('Varian')
                    ->schema([
                        Select::make('warna_id')
                            ->label('Warna')
                            ->relationship('warna', 'label')
                            ->required(),

                        Select::make('ukuran_id')
                            ->label('Ukuran')
                            ->relationship('ukuran', 'ukuran')
                            ->required(),

                        TextInput::make('stok_kaos')
                            ->numeric()
                            ->label('Stok')
                            ->required(),
                        FileUpload::make('image_path')
                            ->label('Foto')
                            ->disk('s3')
                            ->directory('kaos')
                            ->image()
                            ->imageEditor()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Cari varian kaos...')
            ->columns([
                TextColumn::make('kaos.nama_kaos')
                    ->label('Nama'),
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
                        Storage::disk('local')->delete($record->image_path);
                        Storage::disk('s3')->delete($record->image_path);
                    })
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->before(function ($records) {
                        foreach ($records as $record) {
                            Storage::disk('local')->delete($record->image_path);
                            Storage::disk('s3')->delete($record->image_path);
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
            'index' => Pages\ListKaosVarians::route('/'),
            'create' => Pages\CreateKaosVarian::route('/create'),
            'view' => Pages\ViewKaosVarian::route('/{record}'),
            'edit' => Pages\EditKaosVarian::route('/{record}/edit'),
        ];
    }
}
