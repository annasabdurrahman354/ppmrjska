<?php

namespace App\Filament\Resources;

use App\Enums\JenjangKelas;
use App\Filament\Resources\KurikulumResource\Pages\CreateKurikulum;
use App\Filament\Resources\KurikulumResource\Pages\EditKurikulum;
use App\Filament\Resources\KurikulumResource\Pages\ListKurikulums;
use App\Filament\Resources\KurikulumResource\Pages\ViewKurikulum;
use App\Models\Kurikulum;
use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriJuz;
use App\Models\MateriTambahan;
use Awcodes\Shout\Components\Shout;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KurikulumResource extends Resource
{
    protected static ?string $model = Kurikulum::class;
    protected static ?string $slug = 'kurikulum';
    protected static ?string $modelLabel = 'Kurikulum';
    protected static ?string $pluralModelLabel = 'Kurikulum';
    protected static ?string $recordTitleAttribute = 'angkatan_pondok';

    protected static ?string $navigationLabel = 'Kurikulum';
    protected static ?string $navigationGroup = 'Manajemen Kurikulum';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    //protected static ?int $navigationSort = 51;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Kurikulum::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('angkatan_pondok')
                    ->label('Angkatan Pondok')
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
            'index' => ListKurikulums::route('/'),
            'create' => CreateKurikulum::route('/create'),
            'view' => ViewKurikulum::route('/{record}'),
            'edit' => EditKurikulum::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
