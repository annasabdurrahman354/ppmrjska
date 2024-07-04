<?php

namespace App\Filament\Resources;

use App\Enums\StatusPondok;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\CreateJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\EditJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ListJadwalMunaqosahs;
use App\Filament\Resources\JadwalMunaqosahResource\Pages\ViewJadwalMunaqosah;
use App\Filament\Resources\JadwalMunaqosahResource\Widgets\JadwalMunaqosahCalendarWidget;
use App\Models\JadwalMunaqosah;
use App\Models\MateriMunaqosah;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JadwalMunaqosahResource extends Resource
{
    protected static ?string $model = JadwalMunaqosah::class;
    protected static ?string $slug = 'jadwal-munaqosah';
    protected static ?string $modelLabel = 'Jadwal Munaqosah';
    protected static ?string $pluralModelLabel = 'Jadwal Munaqosah';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Jadwal Munaqosah';
    protected static ?string $navigationGroup = 'Manajemen Munaqosah';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 62;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Munaqosah')
                    ->schema([
                        Select::make('materi_munaqosah_id')
                            ->label('Materi Munaqosah')
                            ->options(MateriMunaqosah::all()->pluck('recordTitle', 'id'))
                            ->searchable()
                            ->columnSpanFull()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function(Set $set) {
                                $set('plotJadwalMunaqosah', []);
                            }),
                        DateTimePicker::make('waktu')
                            ->label('Waktu Munaqosah')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, $state) => $set('batas_akhir_pendaftaran', Carbon::parse($state)->subDay())),
                        TextInput::make('maksimal_pendaftar')
                            ->label('Maksimal Pendaftar')
                            ->required()
                            ->numeric()
                            ->minValue(fn (Get $get) => count($get('plotJadwalMunaqosah')))
                            ->live(),
                        DateTimePicker::make('batas_awal_pendaftaran')
                            ->label('Batas Mulai Pendaftaran')
                            ->beforeOrEqual('batas_akhir_pendaftaran')
                            ->default(now())
                            ->required(),
                        DateTimePicker::make('batas_akhir_pendaftaran')
                            ->label('Batas Akhir Pendaftaran')
                            ->afterOrEqual('batas_awal_pendaftaran')
                            ->beforeOrEqual('waktu')
                            ->required(),
                    ]),

                Section::make('Plot Jadwal Munaqosah')
                    ->schema([
                        TableRepeater::make('plotJadwalMunaqosah')
                            ->hiddenLabel()
                            ->relationship('plotJadwalMunaqosah')
                            ->default([])
                            ->disabled(fn (Get $get) => !filled($get('materi_munaqosah_id')))
                            ->headers([
                                Header::make('Santri'),
                                Header::make('Status Terlaksana')
                            ])
                            ->schema([
                                Select::make('user_id')
                                    ->hiddenLabel()
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih santri sesuai kelas munaqosah...')
                                    ->getSearchResultsUsing(function (string $search, Get $get): array{
                                        $materiMunaqosah = MateriMunaqosah::where('id', $get('../../materi_munaqosah_id'))->first();
                                        $kelas = $materiMunaqosah->kelas ?? ['a'];
                                        return User::where('kelas', $kelas)
                                            ->where('nama', 'like', "%{$search}%")
                                            ->where('status_pondok', StatusPondok::AKTIF->value)
                                            ->whereNull('tanggal_lulus_pondok')
                                            ->limit(20)
                                            ->pluck('nama', 'id')
                                            ->toArray();
                                    })
                                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama),
                                Toggle::make('status_terlaksana')
                                    ->label('Terlaksana?')
                                    ->default(false)
                                    ->required(),
                            ])
                            ->addable()
                            ->addActionLabel('Tambah Pendaftar +')
                            ->maxItems(fn (Get $get) => $get('maksimal_pendaftar'))
                            ->live()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount([
                'plotJadwalMunaqosah as total_plotjadwalmunaqosah',
                'plotJadwalMunaqosah as terlaksana_plotjadwalmunaqosah' => function ($query) {
                    $query->where('status_terlaksana', true);
                },
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('materiMunaqosah.recordTitle')
                    ->label('Materi Munaqosah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu Munaqosah')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maksimal_pendaftar')
                    ->label('Maks Pendaftar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_plotjadwalmunaqosah')
                    ->label('Pendaftar')
                    ->numeric(),
                Tables\Columns\TextColumn::make('terlaksana_plotjadwalmunaqosah')
                    ->label('Terlaksana')
                    ->numeric(),
                Tables\Columns\TextColumn::make('batas_awal_pendaftaran')
                    ->label('Batas Mulai Pendaftaran')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas_akhir_pendaftaran')
                    ->label('Batas Akhir Pendaftaran')
                    ->dateTime()
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
            ->groups([
                Group::make('materiMunaqosah.kelas')
                    ->label('Kelas')
                    ->groupQueryUsing(fn (Builder $query) => $query->groupBy('materiMunaqosah.kelas')),
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

    public static function getWidgets(): array
    {
        return [
            JadwalMunaqosahCalendarWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJadwalMunaqosahs::route('/'),
            'create' => CreateJadwalMunaqosah::route('/create'),
            'view' => ViewJadwalMunaqosah::route('/{record}'),
            'edit' => EditJadwalMunaqosah::route('/{record}/edit'),
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
