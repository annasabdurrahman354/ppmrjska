<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriSuratResource\Pages\ManageMateriSurats;
use App\Models\MateriSurat;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriSuratResource extends Resource
{
    protected static ?string $model = MateriSurat::class;
    protected static ?string $slug = 'materi-surat';
    protected static ?string $modelLabel = 'Materi Surat';
    protected static ?string $pluralModelLabel = 'Materi Surat';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Materi Surat';
    protected static ?string $navigationGroup = 'Manajemen Kurikulum';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 72;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor')
                    ->required()
                    ->numeric(),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(96),
                TextInput::make('jumlah_ayat')
                    ->required()
                    ->numeric(),
                TextInput::make('jumlah_halaman')
                    ->required()
                    ->numeric(),
                TextInput::make('halaman_awal')
                    ->required()
                    ->numeric(),
                TextInput::make('halaman_akhir')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('nomor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('jumlah_ayat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_halaman')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('halaman_awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('halaman_akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMateriSurats::route('/'),
        ];
    }
}
