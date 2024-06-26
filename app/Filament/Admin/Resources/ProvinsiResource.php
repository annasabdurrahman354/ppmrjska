<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProvinsiResource\Pages;
use App\Models\Provinsi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProvinsiResource extends Resource
{
    protected static ?string $model = Provinsi::class;
    protected static ?string $slug = 'provinsi';
    protected static ?string $modelLabel = 'Provinsi';
    protected static ?string $pluralModelLabel = 'Provinsi';
    protected static ?string $navigationLabel = 'Provinsi';
    protected static ?string $recordTitleAttribute = 'nama';
    
    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';
    protected static ?string $navigationGroup = 'Manajemen Database';
    protected static ?int $navigationSort = 71;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            'index' => Pages\ListProvinsis::route('/'),
            'create' => Pages\CreateProvinsi::route('/create'),
            'edit' => Pages\EditProvinsi::route('/{record}/edit'),
        ];
    }
}
