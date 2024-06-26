<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KelurahanResource\Pages;
use App\Models\Kelurahan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;
    protected static ?string $slug = 'kelurahan';
    protected static ?string $modelLabel = 'Kelurahan';
    protected static ?string $pluralModelLabel = 'Kelurahan';
    protected static ?string $navigationLabel = 'Kelurahan';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationGroup = 'Manajemen Database';
    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';
    protected static ?int $navigationSort = 74;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kecamatan_id')
                    ->relationship('kecamatan', 'nama')
                    ->searchable()
                    ->required(),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(48),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kecamatan.nama')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kecamatan.kota.nama')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
        ];
    }
}
