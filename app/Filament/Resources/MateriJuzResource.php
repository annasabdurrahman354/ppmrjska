<?php

namespace App\Filament\Resources;

use App\Models\MateriJuz;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriJuzResource extends Resource
{
    protected static ?string $model = MateriJuz::class;
    protected static ?string $slug = 'materi-juz';
    protected static ?string $modelLabel = 'Materi Juz';
    protected static ?string $pluralModelLabel = 'Materi Juz';
    protected static ?string $navigationLabel = 'Materi Juz';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationGroup = 'Manajemen Materi';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 71;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(6),
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
                TextColumn::make('nama')
                    ->searchable(),
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
            'index' => \App\Filament\Resources\MateriJuzResource\Pages\ManageMateriJuzs::route('/'),
        ];
    }
}
