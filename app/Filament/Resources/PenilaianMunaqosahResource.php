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
                    ->label('Santri')
                    ->relationship('user', 'nama')
                    ->required()
                    ->live(),

                Select::make('materi_munaqosah_id')
                    ->label('Materi Munaqosah')
                    ->options(MateriMunaqosah::all()->sortBy('created_at')->pluck('recordTitle', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(
                        function (Set $set, $state){
                            if(filled($state)){
                                $materiMunaqosah = MateriMunaqosah::where('id', $state)->first();

                                $indikator_materi = [];

                                foreach ($materiMunaqosah->indikator_materi as $indikator) {
                                    $indikator_materi[$indikator] = 0;
                                }

                                $indikator_hafalan = [];
                                foreach ($materiMunaqosah->indikator_hafalan as $indikator) {
                                    $indikator_hafalan[$indikator] = 0;
                                }

                                $set('nilai_materi', $indikator_materi);
                                $set('nilai_hafalan', $indikator_hafalan);

                                $set('materi', $materiMunaqosah->materi);
                                $set('hafalan', $materiMunaqosah->hafalan);
                            }
                        }
                    ),

                TagsInput::make('materi')
                    ->label('Materi')
                    ->placeholder('Materi yang diujikan')
                    ->disabled()
                    ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

                TagsInput::make('hafalan')
                    ->label('Hafalan')
                    ->placeholder('Hafalan yang diujikan')
                    ->disabled()
                    ->dehydrated()
                    ->default(function (Get $get){
                        return filled($get('materi_munaqosah_id')) ?
                            MateriMunaqosah::where('id', $get('materi_munaqosah_id'))->first()->hafalan
                            : [];
                    })
                    ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

                KeyValue::make('nilai_materi')
                    ->label('Nilai Munaqosah Materi')
                    ->columnSpanFull()
                    ->keyLabel('Indikator Penilaian')
                    ->keyPlaceholder('Indikator Penilaian')
                    ->editableKeys(false)
                    ->addable(false)
                    ->deletable(false)
                    ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),

                KeyValue::make('nilai_hafalan')
                    ->label('Nilai Munaqosah Hafalan')
                    ->columnSpanFull()
                    ->keyLabel('Indikator Penilaian')
                    ->keyPlaceholder('Indikator Penilaian')
                    ->editableKeys(false)
                    ->addable(false)
                    ->deletable(false)
                    ->visible(fn(Get $get) => filled($get('materi_munaqosah_id'))),
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
            'index' => ListPenilaianMunaqosahs::route('/'),
            'create' => CreatePenilaianMunaqosah::route('/create'),
            'view' => ViewPenilaianMunaqosah::route('/{record}'),
            'edit' => EditPenilaianMunaqosah::route('/{record}/edit'),
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
