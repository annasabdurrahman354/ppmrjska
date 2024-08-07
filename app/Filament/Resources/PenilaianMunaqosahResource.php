<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianMunaqosahResource\Pages\CreatePenilaianMunaqosah;
use App\Filament\Resources\PenilaianMunaqosahResource\Pages\EditPenilaianMunaqosah;
use App\Filament\Resources\PenilaianMunaqosahResource\Pages\ListPenilaianMunaqosahs;
use App\Filament\Resources\PenilaianMunaqosahResource\Pages\ViewPenilaianMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\PenilaianMunaqosah;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenilaianMunaqosahResource extends Resource
{
    protected static ?string $model = PenilaianMunaqosah::class;
    protected static ?string $slug = 'penilaian-munaqosah';
    protected static ?string $modelLabel = 'Penilaian Munaqosah';
    protected static ?string $pluralModelLabel = 'Penilaian Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Penilaian Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = 63;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(PenilaianMunaqosah::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.nama')
                    ->label('Nama Santri')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('materiMunaqosah.recordTitle')
                    ->label('Materi Munaqosah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPenilaianMunaqosahs::route('/'),
            'create' => CreatePenilaianMunaqosah::route('/create'),
            'view' => ViewPenilaianMunaqosah::route('/{record}'),
            'edit' => EditPenilaianMunaqosah::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
