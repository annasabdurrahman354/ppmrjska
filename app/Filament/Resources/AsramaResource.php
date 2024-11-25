<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsramaResource\Pages;
use App\Models\Asrama;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;
    protected static ?string $slug = 'asrama';
    protected static ?string $modelLabel = 'Asrama';
    protected static ?string $pluralModelLabel = 'Asrama';

    protected static ?string $navigationLabel = 'Asrama';
    protected static ?string $navigationGroup = 'Manajemen Administrasi';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    //protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Asrama::getForm());
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penghuni')
                    ->label('Penghuni')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('biaya_asrama_tahunan')
                    ->label('Biaya Asrama Per Tahun')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pembebanan_biaya_asrama')
                    ->label('Pembebanan Biaya Asrama')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kapasitas_per_kamar')
                    ->label('Kapasitas Per Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kapasitas_total')
                    ->label('Kapasitas Total')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepemilikan_gedung')
                    ->label('Kepemilikan Gedung')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pemilik')
                    ->label('Nama Pemilik')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_telepon_pemilik')
                    ->label('Nomor Telepon Pemilik')
                    ->sortable()
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('foto')
                    ->label('Foto')
                    ->collection('asrama_foto')
                    ->conversion('thumb')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation(),
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
            'index' => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'view' => Pages\ViewAsrama::route('/{record}'),
            'edit' => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
