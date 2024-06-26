<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KotaResource\Pages;
use App\Models\Kota;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class KotaResource extends Resource
{
    protected static ?string $model = Kota::class;
    protected static ?string $slug = 'kota';
    protected static ?string $modelLabel = 'Kota';
    protected static ?string $pluralModelLabel = 'Kota';
    protected static ?string $navigationLabel = 'Kota';
    protected static ?string $recordTitleAttribute = 'nama';
    
    protected static ?string $navigationGroup = 'Manajemen Database';
    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';
    protected static ?int $navigationSort = 72;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('provinsi_id')
                    ->relationship('provinsi', 'nama')
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provinsi.nama')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListKotas::route('/'),
            'create' => Pages\CreateKota::route('/create'),
            'edit' => Pages\EditKota::route('/{record}/edit'),
        ];
    }
}
