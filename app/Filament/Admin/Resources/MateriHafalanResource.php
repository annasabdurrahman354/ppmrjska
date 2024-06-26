<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MateriHafalanResource\Pages;
use App\Models\MateriHafalan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriHafalanResource extends Resource
{
    protected static ?string $model = MateriHafalan::class;
    protected static ?string $slug = 'materi-hafalan';
    protected static ?string $modelLabel = 'Materi Hafalan';
    protected static ?string $pluralModelLabel = 'Materi Hafalan';
    protected static ?string $navigationLabel = 'Materi Hafalan';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationGroup = 'Manajemen Materi';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 75;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(96),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
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
            'index' => Pages\ManageMateriHafalan::route('/'),
        ];
    }
}
