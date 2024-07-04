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
    protected static ?int $navigationSort = 52;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('angkatan_pondok')
                        ->label('Angkatan Pondok')
                        ->placeholder('Isi angkatan pondok...')
                        ->required()
                        ->numeric()
                        ->minValue(2015)
                        ->columnSpanFull(),

                Repeater::make('plotKurikulum')
                    ->hiddenLabel()
                    ->relationship('plotKurikulum')
                    ->itemLabel(function ($uuid, $component) {
                        $keys = array_keys($component->getState());
                        $index = array_search($uuid, $keys);
                        return "Semester ".$index + 1;
                    })
                    ->schema([
                        Select::make('jenjang_kelas')
                            ->required()
                            ->options(JenjangKelas::class),
                        TextInput::make('semester')
                            ->hidden()
                            ->numeric(),
                        Shout::make('st-empty')
                            ->content('Belum ada plotingan materi kurikulum!')
                            ->type('info')
                            ->color(Color::Yellow)
                            ->visible(fn(Get $get) => !filled($get('plot_materi'))),
                        Repeater::make('plotKurikulumMateri')
                            ->hiddenLabel()
                            ->relationship('plotKurikulumMateri')
                            ->schema([
                                ToggleButtons::make('materi_type')
                                    ->hiddenLabel()
                                    ->required()
                                    ->inline()
                                    ->options([
                                        MateriJuz::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriHafalan::class => 'Hafalan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->default(MateriJuz::class)
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('materi_id', null);
                                    }),

                                Select::make('materi_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih nomor juz Al-Quran...')
                                    ->required()
                                    ->hidden(fn (Get $get) => $get('materi_type') == null || $get('materi_type') != MateriJuz::class)
                                    ->options(
                                        MateriJuz::all('nama', 'id')
                                        ->pluck('nama', 'id')
                                        ->sortBy('nama')
                                        ->toArray(),
                                    )
                                    ->preload(),

                                Select::make('materi_id')
                                    ->hiddenLabel()
                                    ->required()
                                    ->placeholder('Pilih materi himpunan/materi kelas/hafalan...')
                                    ->hidden(fn (Get $get) => $get('materi_type') == null || $get('materi_type') == MateriJuz::class)
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)
                                            ->pluck('nama', 'id')
                                            ->sortBy('nama')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_type')::find($value)?->nama,
                                    ),

                                Toggle::make('status_tercapai')
                                    ->onColor('success')
                                    ->offColor('danger')
                            ])
                            ->live()
                            ->addable(true)
                            ->addActionLabel('Tambah Materi +')
                            ->reorderableWithButtons()
                            ->deletable()
                            ->deleteAction(
                                fn (Action $action) => $action->requiresConfirmation(),
                            )
                    ])
                    ->columnSpanFull()
                    ->addable(true)
                    ->addActionLabel('Tambah Semester +')
                    ->reorderableWithButtons()
                    ->deletable()
                    ->deleteAction(
                        fn (Action $action) => $action->requiresConfirmation(),
                    )
            ]);
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
                    ->sortable(),
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
            'index' => ListKurikulums::route('/'),
            'create' => CreateKurikulum::route('/create'),
            'view' => ViewKurikulum::route('/{record}'),
            'edit' => EditKurikulum::route('/{record}/edit'),
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
