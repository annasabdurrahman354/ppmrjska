<?php

namespace App\Filament\Admin\Resources;

use App\Enums\JenisMateriMunaqosah;
use App\Filament\Admin\Resources\MateriMunaqosahResource\Pages;
use App\Models\MateriHafalan;
use App\Models\MateriMunaqosah;
use App\Models\MateriSurat;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
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
    protected static ?string $navigationLabel = 'Materi Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 61;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kelas')
                    ->schema([
                        Select::make('kelas')
                            ->label('Kelas')
                            ->required()
                            ->disabled(auth()->user()->isNotSuperAdmin())
                            ->disabledOn('edit')
                            ->options(
                                User::where('kelas', '!=', 'admin')
                                    ->select('kelas')
                                    ->distinct()
                                    ->get()
                                    ->sortBy('kelas')
                                    ->pluck('kelas', 'kelas'))
                            ->default(match (auth()->user()->kelas) {
                                'admin' => 'Takmili',
                                default => auth()->user()->kelas
                            }),
        
                        TextInput::make('semester')
                            ->required()
                            ->numeric()
                            ->maxValue(8),
        
                        Cluster::make([
                            TextInput::make('tahun_ajaran_awal')
                                ->required()
                                ->numeric()
                                ->default(date('Y')),
                            TextInput::make('tahun_ajaran_akhir')
                                ->required()
                                ->numeric()
                                ->default(date('Y')+1)
                                ->gte('tahun_ajaran_awal'),
                        ])
                        ->label('Tahun Ajaran'),
        
                        Select::make('dewan_guru_id')
                            ->label('Dewan Guru')
                            ->required()
                            ->searchable()
                            ->relationship('dewanGuru', 'nama'),
                        ]),
                Section::make('Materi Munaqosah')
                    ->schema([
                        ToggleButtons::make('jenis_materi')
                            ->label('Jenis Materi')
                            ->required()
                            ->inline()
                            ->options(JenisMateriMunaqosah::class)
                            ->default(MateriSurat::class)
                            ->live()
                            ->afterStateUpdated(function(Set $set) {
                                $set('materi', null);
                            }),

                        Select::make('materi')
                            ->label('Pilih Materi')
                            ->placeholder('Bisa lebih dari satu.')
                            ->hidden(fn (Get $get) => $get('jenis_materi') == null)
                            ->multiple()
                            ->getSearchResultsUsing(fn (Get $get, string $search): array => 
                                $get('jenis_materi')::where('nama', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'nama')
                                    ->toArray(),
                            )
                            ->getOptionLabelUsing(fn (Get $get, $values): ?string =>     
                                    $get('jenis_materi')::whereIn('nama', $values)->pluck('nama', 'nama')->toArray()
                            )
                            ->hidden(function (Get $get) {
                                return $get('jenis_materi') == MateriHafalan::class;
                            })
                            ->disabled(function (Get $get) {
                                return $get('jenis_materi') == MateriHafalan::class;
                            })
                            ->required(function (Get $get) {
                                return $get('jenis_materi') != MateriHafalan::class;
                            })
                            ->live(),

                        TextInput::make('detail')
                            ->placeholder('Tuliskan detail materi yang akan diujikan.')
                            ->maxLength(255)
                            ->default(null)
                            ->hidden(function (Get $get) {
                                return $get('jenis_materi') == MateriHafalan::class;
                            })
                            ->disabled(function (Get $get) {
                                return $get('jenis_materi') == MateriHafalan::class;
                            }),

                        Select::make('hafalan')
                            ->label('Pilih Hafalan')
                            ->placeholder('Bisa lebih dari satu.')
                            ->multiple()
                            ->getSearchResultsUsing(fn (string $search): array => 
                                MateriHafalan::where('nama', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->orderBy('nama')
                                    ->pluck('nama', 'nama')
                                    ->toArray(),
                            )
                            ->getOptionLabelUsing(fn ($values): ?string =>     
                                MateriHafalan::whereIn('nama', $values)->pluck('nama', 'nama')->toArray()
                            )
                            ->required(function (Get $get) {
                                return $get('jenis_materi') == MateriHafalan::class;
                            })
                            ->live(),
                            
                        TagsInput::make('indikator_materi')
                            ->label('Indikator Penilaian Materi')
                            ->hidden(function (Get $get) {
                                return empty($get('materi'));
                            })
                            ->disabled(function (Get $get) {
                                return empty($get('materi'));
                            })
                            ->required(function (Get $get) {
                                return !empty($get('materi'));
                            })
                            ->placeholder('Tuliskan indikator penilaian materi.'),

                        TagsInput::make('indikator_hafalan')
                            ->label('Indikator Penilaian Hafalan')
                            ->hidden(function (Get $get) {
                                return empty($get('hafalan'));
                            })
                            ->disabled(function (Get $get) {
                                return empty($get('hafalan'));
                            })
                            ->required(function (Get $get) {
                                return !empty($get('hafalan'));
                            })
                            ->placeholder('Tuliskan indikator penilaian hafalan.'),
                        ]),
                
                Section::make('Jadwal Munaqosah')
                    ->schema([
                        Shout::make('st-empty')
                            ->content('Belum ada jadwal munaqosah untuk materi ini!')
                            ->type('info')
                            ->color(Color::Yellow)
                            ->visible(fn (Get $get) => count($get('jadwalMunaqosah')) == 0 || $get('jadwalMunaqosah') == null),
                        Repeater::make('jadwalMunaqosah')
                            ->hiddenLabel()
                            ->addable()
                            ->addActionLabel('Tambah Jadwal +')
                            ->deletable()
                            ->relationship('jadwalMunaqosah')
                            ->live()
                            ->schema([
                                DateTimePicker::make('waktu')
                                    ->label('Waktu Munaqosah')
                                    ->distinct()
                                    ->required(),
                                TextInput::make('maksimal_pendaftar')
                                    ->label('Maksimal Pendaftar')
                                    ->required()
                                    ->numeric(),
                                DateTimePicker::make('batas_awal_pendaftaran')
                                    ->label('Batas Awal Pendaftaran')
                                    ->beforeOrEqual('batas_akhir_pendaftaran')
                                    ->required(),
                                DateTimePicker::make('batas_akhir_pendaftaran')
                                    ->label('Batas Akhir Pendaftaran')
                                    ->afterOrEqual('batas_awal_pendaftaran')
                                    ->beforeOrEqual('waktu')
                                    ->required(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('kelas')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('kelas')),
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
            'index' => Pages\ListMateriMunaqosahs::route('/'),
            'create' => Pages\CreateMateriMunaqosah::route('/create'),
            'view' => Pages\ViewMateriMunaqosah::route('/{record}'),
            'edit' => Pages\EditMateriMunaqosah::route('/{record}/edit'),
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
