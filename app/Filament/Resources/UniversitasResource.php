<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversitasResource\Pages;
use App\Models\Universitas;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniversitasResource extends Resource
{
    protected static ?string $model = Universitas::class;
    protected static ?string $slug = 'universitas';
    protected static ?string $modelLabel = 'Universitas';
    protected static ?string $pluralModelLabel = 'Universitas';

    protected static ?string $navigationLabel = 'Universitas';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Universitas::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('link_website')
                    ->label('Link Website')
                    ->searchable()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('foto')
                    ->label('Foto')
                    ->collection('universitas_foto')
                    ->conversion('thumb')
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
                    //
                ]),
            ])
            ->selectCurrentPageOnly();
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
            'index' => Pages\ListUniversitas::route('/'),
            'create' => Pages\CreateUniversitas::route('/create'),
            'view' => Pages\ViewUniversitas::route('/{record}'),
            'edit' => Pages\EditUniversitas::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
