<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages;
use App\Models\Carousel;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;
    protected static ?string $slug = 'carousel';
    protected static ?string $modelLabel = 'Carousel';
    protected static ?string $pluralModelLabel = 'Carousel';

    protected static ?string $navigationLabel = 'Carousel';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Carousel::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('link_tujuan')
                    ->label('Link Tujuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status_aktif')
                    ->label('Status Aktif')
                    ->sortable()
                    ->boolean(),
                SpatieMediaLibraryImageColumn::make('cover')
                    ->label('Cover')
                    ->collection('carousel_cover')
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
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'),
            'view' => Pages\ViewCarousel::route('/{record}'),
            'edit' => Pages\EditCarousel::route('/{record}/edit'),
        ];
    }
}
