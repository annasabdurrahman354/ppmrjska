<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriJuzResource\Pages\ManageMateriJuzs;
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
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Materi Juz';
    protected static ?string $navigationGroup = 'Manajemen Kurikulum';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    //protected static ?int $navigationSort = 71;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(MateriJuz::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('halaman_awal')
                    ->label('Halaman Awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('halaman_akhir')
                    ->label('Halaman Akhir')
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
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMateriJuzs::route('/'),
        ];
    }
}
