<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenilaianMunaqosahResource\Pages;
use App\Models\PenilaianMunaqosah;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
    protected static ?string $navigationLabel = 'Penilaian Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?int $navigationSort = 63;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'nama')
                    ->required(),
                Select::make('materi_munaqosah_id')
                    ->relationship(name: 'materiMunaqosah', titleAttribute: 'recordTitle')
                    ->searchable(['recordTitle'])
                    ->required(),
                TextInput::make('nilai_materi')
                    ->columnSpanFull(),
                TextInput::make('nilai_hafalan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materiMunaqosah.recordTitle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPenilaianMunaqosahs::route('/'),
            'create' => Pages\CreatePenilaianMunaqosah::route('/create'),
            'view' => Pages\ViewPenilaianMunaqosah::route('/{record}'),
            'edit' => Pages\EditPenilaianMunaqosah::route('/{record}/edit'),
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
