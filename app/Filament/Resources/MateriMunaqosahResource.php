<?php

namespace App\Filament\Resources;

use App\Enums\JenisMateriMunaqosah;
use App\Filament\Resources\MateriMunaqosahResource\Pages\CreateMateriMunaqosah;
use App\Filament\Resources\MateriMunaqosahResource\Pages\EditMateriMunaqosah;
use App\Filament\Resources\MateriMunaqosahResource\Pages\ListMateriMunaqosahs;
use App\Filament\Resources\MateriMunaqosahResource\Pages\ViewMateriMunaqosah;
use App\Models\Asrama;
use App\Models\MateriHafalan;
use App\Models\MateriMunaqosah;
use App\Models\MateriSurat;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MateriMunaqosahResource extends Resource
{
    protected static ?string $model = MateriMunaqosah::class;

    protected static ?string $slug = 'materi-munaqosah';
    protected static ?string $modelLabel = 'Materi Munaqosah';
    protected static ?string $pluralModelLabel = 'Materi Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Materi Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 61;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(MateriMunaqosah::getForm());
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
                    ->label('Angkatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_materi')
                    ->label('Jenis Materi')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('detail')
                    ->label('Detail Materi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hafalan')
                    ->label('Materi Hafalan')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indikator_materi')
                    ->label('Penilaian Materi')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indikator_hafalan')
                    ->label('Penilaian Hafalan')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dewanGuru.nama')
                    ->label('Dewan Guru')
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
            ->groups([
                Group::make('kelas')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('kelas')),
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
            'index' => ListMateriMunaqosahs::route('/'),
            'create' => CreateMateriMunaqosah::route('/create'),
            'view' => ViewMateriMunaqosah::route('/{record}'),
            'edit' => EditMateriMunaqosah::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
