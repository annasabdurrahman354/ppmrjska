<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlbumResource\Pages\CreateAlbum;
use App\Filament\Resources\AlbumResource\Pages\EditAlbum;
use App\Filament\Resources\AlbumResource\Pages\ListAlbum;
use App\Filament\Resources\AlbumResource\Pages\ViewAlbum;
use App\Models\Album;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;
    protected static ?string $slug = 'album';
    protected static ?string $modelLabel = 'Album';
    protected static ?string $pluralModelLabel = 'Album';
    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?string $navigationLabel = 'Album';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Album::getForm());
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
                    ->label('Judul')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable(),
                SpatieTagsColumn::make('tag')
                    ->label('Tag')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('penulis.nama')
                    ->label('Penulis')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('seo_judul')
                    ->label('SEO Judul')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('seo_deskripsi')
                    ->label('SEO Deskripsi')
                    ->limit(50)
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                SpatieTagsColumn::make('seo_keyword')
                    ->label('SEO Keyword')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('highlight')
                    ->label('Highlight')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('kategori.nama')
                    ->label('Kategori')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('kategori.nama')),
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
            'index' => ListAlbum::route('/'),
            'create' => CreateAlbum::route('/create'),
            'view' => ViewAlbum::route('/{record}'),
            'edit' => EditAlbum::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
