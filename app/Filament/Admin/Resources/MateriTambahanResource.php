<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MateriTambahanResource\Pages;
use App\Models\MateriTambahan;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriTambahanResource extends Resource
{
    protected static ?string $model = MateriTambahan::class;
    protected static ?string $slug = 'materi-tambahan';
    protected static ?string $modelLabel = 'Materi Tambahan';
    protected static ?string $pluralModelLabel = 'Materi Tambahan';
    protected static ?string $navigationLabel = 'Materi Tambahan';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationGroup = 'Manajemen Materi';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 74;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(96),
                TextInput::make('jumlah_halaman')
                    ->numeric()
                    ->default(null),
                Textarea::make('link_materi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('jumlah_halaman')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('link_materi')
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
            'index' => Pages\ManageMateriTambahans::route('/'),
        ];
    }
}
