<?php

namespace App\Filament\Shared\Resources;

use App\Enums\JenisKelamin;
use App\Enums\Sesi;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Shared\Resources\JurnalKelasResource\Pages;
use App\Models\DewanGuru;
use App\Models\JurnalKelas;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class JurnalKelasResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = JurnalKelas::class;
    protected static ?string $slug = 'jurnal-kelas';
    protected static ?string $modelLabel = 'Jurnal Kelas';
    protected static ?string $pluralModelLabel = 'Jurnal Kelas';
    protected static ?string $navigationLabel = 'Jurnal Kelas';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 51;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Manajemen Kelas')
                            ->icon('fluentui-people-list-24')
                            ->iconPosition(IconPosition::Before)
                            ->schema([
                                Section::make('Informasi KBM')
                                    ->schema([
                                        DatePicker::make('tanggal')
                                            ->label('Tanggal KBM')
                                            ->required()
                                            ->default(now()),

                                        Select::make('sesi')
                                            ->label('Sesi KBM')
                                            ->required()
                                            ->options(Sesi::class),

                                        Select::make('kelas')
                                            ->label('Kelas')
                                            ->multiple()
                                            ->disabledOn('edit')
                                            ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))
                                            ->dehydrated(cant('rekap_kelas_lain_jurnal::kelas'))
                                            ->maxItems(fn () => cant('rekap_kelas_lain_jurnal::kelas') ? 1 : 6)
                                            ->options(
                                                User::where('status_pondok', StatusPondok::AKTIF->value)
                                                ->where('tanggal_lulus_pondok', null)
                                                ->select('kelas')
                                                ->orderBy('kelas')
                                                ->distinct()
                                                ->get()
                                                ->pluck('kelas', 'kelas')
                                            )
                                            ->default(match (auth()->user()->kelas) {
                                                config('filament-shield.super_admin.name') => ['Takmili'],
                                                default => [auth()->user()->kelas]
                                            })
                                            ->live()
                                            ->afterStateUpdated(function(Get $get, Set $set, $state) {
                                                $users = User::whereIn('kelas', $state)
                                                    ->where('jenis_kelamin', $get('jenis_kelamin'))
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value,
                                                    ];
                                                }
                                                $set('presensiKelas', $result);
                                            }),

                                        ToggleButtons::make('jenis_kelamin')
                                            ->label('Santri')
                                            ->inline()
                                            ->grouped()
                                            ->required()
                                            ->disabledOn('edit')
                                            ->disabled(isNotSuperAdmin())
                                            ->dehydrated(isNotSuperAdmin())
                                            ->options(JenisKelamin::class)
                                            ->default(auth()->user()->jenis_kelamin)
                                            ->live()
                                            ->afterStateUpdated(function(Get $get, Set $set, $state) {
                                                $users = User::whereIn('kelas', $get('kelas'))
                                                    ->where('jenis_kelamin', $state)
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value,
                                                    ];
                                                }
                                                $set('presensiKelas', $result);
                                            }),

                                        Select::make('perekap_id')
                                            ->label('Perekap')
                                            ->required()
                                            ->disabledOn('edit')
                                            ->disabled(isNotSuperAdmin())
                                            ->dehydrated(isNotSuperAdmin())
                                            ->options(
                                                User::select('nama', 'id')
                                                    ->distinct()
                                                    ->get()
                                                    ->pluck('nama', 'id')
                                            )
                                            ->default(auth()->user()->id)
                                            ->preload()
                                            ->searchable(['nama'])
                                            ->columnSpanFull(),

                                        Fieldset::make()
                                            ->label('Dewan Guru')
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('dewan_guru_type')
                                                    ->hiddenLabel()
                                                    ->required()
                                                    ->inline()
                                                    ->grouped()
                                                    ->options([
                                                        DewanGuru::class => 'Dewan Guru',
                                                        User::class => 'Takmili',
                                                    ])
                                                    ->default(DewanGuru::class)
                                                    ->live()
                                                    ->afterStateUpdated(function(Set $set) {
                                                        $set('dewan_guru_id', null);
                                                    }),

                                                Select::make('dewan_guru_id')
                                                    ->required()
                                                    ->hiddenLabel()
                                                    ->placeholder('Pilih dewan guru/santri takmili...')
                                                    ->hidden(fn (Get $get) => $get('dewan_guru_type') == null)
                                                    ->searchable()
                                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                        match ($get('dewan_guru_type')) {
                                                            DewanGuru::class =>
                                                                DewanGuru::where('nama', 'like', "%{$search}%")
                                                                    ->limit(20)->pluck('nama', 'id')
                                                                    ->toArray(),
                                                            User::class =>
                                                                User::where('nama', 'like', "%{$search}%")
                                                                    ->where('kelas', 'takmili')
                                                                    ->limit(20)->pluck('nama', 'id')
                                                                    ->toArray()
                                                        }

                                                    )
                                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                        match ($get('dewan_guru_type')) {
                                                            DewanGuru::class =>
                                                                DewanGuru::find($value)?->nama,
                                                            User::class =>
                                                                User::find($value)?->nama
                                                        }

                                                    )
                                                    ->live(),
                                            ])
                                    ])->columns([
                                        'sm' => 1,
                                        'md' => 2
                                    ])
                                    ->columnSpanFull(),


                                Section::make('Presensi')
                                    ->schema([
                                        Shout::make('st-empty')
                                            ->content('Belum ada presensi santri!')
                                            ->type('info')
                                            ->color(Color::Yellow)
                                            ->visible(fn(Get $get) => !filled($get('presensiKelas'))),

                                        Repeater::make('presensiKelas')
                                            ->hiddenLabel()
                                            ->relationship('presensiKelas')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->live()
                                            ->default(function(Get $get) {
                                                $users = User::whereIn('kelas', $get('kelas'))
                                                    ->where('jenis_kelamin', $get('jenis_kelamin'))
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value
                                                    ];
                                                }
                                                return $result;
                                            })
                                            ->schema([
                                                Select::make('user_id')
                                                    ->hiddenLabel()
                                                    ->placeholder('Pilih santri sesuai kelas...')
                                                    ->required()
                                                    ->distinct()
                                                    ->disabledOn('edit')
                                                    ->disabled(isNotSuperAdmin())
                                                    ->dehydrated(isNotSuperAdmin())
                                                    ->searchable()
                                                    ->preload()
                                                    ->getSearchResultsUsing(fn (string $search, Get $get): array =>
                                                        User::where('nama', 'like', "%{$search}%")
                                                            ->where('jenis_kelamin', $get('../../jenis_kelamin'))
                                                            ->whereIn('kelas', $get('../../kelas'))
                                                            ->where('status_pondok',  StatusPondok::AKTIF->value)
                                                            ->where('tanggal_lulus_pondok', null)
                                                            ->limit(20)
                                                            ->pluck('nama', 'id')
                                                            ->toArray()
                                                    )
                                                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                                    ->columnSpan(4),

                                                ToggleButtons::make('status_kehadiran')
                                                    ->hiddenLabel()
                                                    ->inline()
                                                    ->grouped()
                                                    ->required()
                                                    ->options([
                                                        'hadir' => 'H',
                                                        'telat' => 'T',
                                                        'izin' => 'I',
                                                        'sakit' => 'S',
                                                        'alpa' => 'A',
                                                    ])
                                                    ->colors([
                                                        'hadir' => 'success',
                                                        'telat' => 'primary',
                                                        'izin' => 'warning',
                                                        'sakit' => 'secondary',
                                                        'alpa' => 'danger',
                                                    ])
                                                    ->default(StatusKehadiran::ALPA->value)
                                                    ->columnSpan(1),
                                            ])
                                            ->addActionLabel('Tambah +')
                                            ->columns([
                                                'sm' => 1,
                                                'md' => 5
                                            ])
                                            ->columnSpanFull(),
                                        ]),
                            ]),
                        Tab::make('Materi yang Disampaikan')
                            ->icon('fluentui-notebook-24')
                            ->iconPosition(IconPosition::Before)
                            ->schema([
                                Fieldset::make()
                                    ->label('Materi Awal')
                                    ->schema([
                                        ToggleButtons::make('materi_awal_type')
                                            ->hiddenLabel()
                                            ->inline()
                                            ->options([
                                                MateriSurat::class => 'Al-Quran',
                                                MateriHimpunan::class => 'Himpunan',
                                                MateriTambahan::class => 'Lainnya',
                                            ])
                                            ->default(MateriSurat::class)
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('materi_awal_id', null);
                                            }),

                                        Select::make('materi_awal_id')
                                            ->hiddenLabel()
                                            ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') == null)
                                            ->searchable()
                                            ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                                    ->limit(20)->pluck('nama', 'id')
                                                    ->toArray(),
                                            )
                                            ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                    $get('materi_awal_type')::find($value)?->nama,
                                            )
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('halaman_awal', null);
                                                $set('ayat_awal', null);
                                            }),

                                        TextInput::make('halaman_awal')
                                            ->numeric()
                                            ->minValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_awal ?? 1)
                                            ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_akhir ?? 1000)
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') == null || $get('materi_awal_id') == null)
                                            ->default(null)
                                            ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                        TextInput::make('ayat_awal')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->jumlah_ayat ?? 300)
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') != MateriSurat::class || $get('materi_awal_id') == null)
                                            ->default(null),
                                    ])
                                    ->columns([
                                        'sm' => 1,
                                        'lg' => 2
                                    ])
                                    ->columnSpanFull(),

                                Fieldset::make()
                                    ->label('Materi Akhir')
                                    ->schema([
                                        ToggleButtons::make('materi_akhir_type')
                                            ->hiddenLabel()
                                            ->inline()
                                            ->options([
                                                MateriSurat::class => 'Al-Quran',
                                                MateriHimpunan::class => 'Himpunan',
                                                MateriTambahan::class => 'Lainnya',
                                            ])
                                            ->default(MateriSurat::class)
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('materi_akhir_id', null);
                                            }),

                                        Select::make('materi_akhir_id')
                                            ->hiddenLabel()
                                            ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') == null)
                                            ->searchable()
                                            ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                                    ->limit(20)->pluck('nama', 'id')
                                                    ->toArray(),
                                            )
                                            ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                    $get('materi_akhir_type')::find($value)?->nama,
                                            )
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('halaman_akhir', null);
                                                $set('ayat_akhir', null);
                                            }),

                                        TextInput::make('halaman_akhir')
                                            ->numeric()
                                            ->minValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_awal ?? 1)
                                            ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_akhir ?? 1000)
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') == null || $get('materi_akhir_id') == null)
                                            ->default(null)
                                            ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                        TextInput::make('ayat_akhir')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->jumlah_ayat ?? 300)
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') != MateriSurat::class || $get('materi_akhir_id') == null)
                                            ->default(null),
                                    ])
                                    ->columns([
                                        'sm' => 1,
                                        'lg' => 2
                                    ])
                                    ->columnSpanFull(),

                                TextInput::make('keterangan')
                                    ->label('Detail Materi')
                                    ->maxLength(255),

                                TextInput::make('link_rekaman')
                                    ->hidden(cant('ubah_materi_rekaman_jurnal::kelas'))
                                    ->label('Link Rekaman')
                                    ->default(null),
                            ]),
                        ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->hidden(isNotSuperAdmin())
                    ->searchable(),
                TextColumn::make('tanggal')
                    ->label('Tanggal KBM')
                    ->date()
                    ->sortable(),
                TextColumn::make('sesi')
                    ->label('Sesi KBM')
                    ->badge()
                    ->searchable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('statusKehadiranSaya')
                    ->label('Kehadiran')
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'telat' => 'primary',
                        'izin' => 'warning',
                        'sakit' => 'secondary',
                        'alpa' => 'danger',
                        'Bukan Kelas' => 'gray',
                    })
                    ->badge(),
                TextColumn::make('dewanGuru.nama')
                    ->label('Dewan Guru')
                    ->searchable(),
                TextColumn::make('materiAwal.nama')
                    ->label('Materi Awal')
                    ->sortable(),
                TextColumn::make('materiAkhir.nama')
                    ->label('Materi Akhir')
                    ->sortable(),
                TextColumn::make('halaman_awal')
                    ->label('Halaman Awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('halaman_akhir')
                    ->label('Halaman Akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ayat_awal')
                    ->label('Ayat Awal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ayat_akhir')
                    ->label('Ayat Akhir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Detail Materi')
                    ->searchable(),
                TextColumn::make('namaPerekap')
                    ->label('Perekap'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Mulai Tanggal'),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Filter::make('jenis_materi')
                    ->form([
                        ToggleButtons::make('jenis_materi')
                                    ->label('Jenis Materi')
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['jenis_materi'],
                                fn (Builder $query): Builder => $query->where('materi_awal_type', '=', $data['jenis_materi'])->orWhere('materi_akhir_type', '=', $data['jenis_materi']),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['jenis_materi']) {
                            return null;
                        }

                        return 'Materi: ' . match($data['jenis_materi']){
                            MateriSurat::class => 'Al Quran',
                            MateriHimpunan::class => 'Himpunan',
                            MateriTambahan::class => 'Lainnya',
                        };
                    }),

                Filter::make('halaman_awal')
                    ->form([
                        TextInput::make('halaman_mulai')
                            ->label('Mulai Halaman')
                            ->numeric(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['halaman_mulai'],
                                fn (Builder $query): Builder => $query->where('halaman_awal', '<=', $data['halaman_mulai'])
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['halaman_mulai']) {
                            return null;
                        }

                        return 'Mulai halaman: ' . $data['halaman_mulai'];
                    }),
            ])
            ->groups([
                Group::make('tanggal')
                    ->getTitleFromRecordUsing(fn (JurnalKelas $record): string => ucfirst($record->tanggal->format('j F, Y'))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(function (JurnalKelas $record){
                        return !auth()->user()->cekPerekap($record) || !isKedisipilinan() || !isKeilmuan() || isNotSuperAdmin();
                    }),
                Tables\Actions\EditAction::make()
                    ->hidden(function (JurnalKelas $record){
                        return !auth()->user()->cekPerekap($record) || !isKedisipilinan() || !isKeilmuan() || isNotSuperAdmin();
                    }),
                Action::make('updateMateriRekaman')
                    ->label('Ubah Materi & Rekaman')
                    ->hidden(cant('ubah_materi_rekaman_jurnal::kelas'))
                    ->color('secondary')
                    ->fillForm(function (JurnalKelas $record): array {
                       return [
                                'materi_awal_type' => $record->materi_awal_type,
                                'materi_akhir_type' => $record->materi_akhir_type,
                                'materi_awal_id' => $record->materi_awal_id,
                                'materi_akhir_id' => $record->materi_akhir_id,
                                'halaman_awal' => $record->halaman_awal,
                                'halaman_akhir' => $record->halaman_akhir,
                                'ayat_awal' => $record->ayat_awal,
                                'ayat_akhir' => $record->ayat_akhir,
                                'link_rekaman' => $record->link_rekaman,
                                'keterangan' =>  $record->keterangan,
                       ];}
                    )
                    ->form([
                        Fieldset::make()
                            ->label('Materi Awal')
                            ->schema([
                                ToggleButtons::make('materi_awal_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->default(MateriSurat::class)
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('materi_awal_id', null);
                                    }),

                                Select::make('materi_awal_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') == null)
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_awal_type')::find($value)?->nama,
                                    )
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('halaman_awal', null);
                                        $set('ayat_awal', null);
                                    }),

                                TextInput::make('halaman_awal')
                                    ->numeric()
                                    ->minValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_awal ?? 1)
                                    ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_akhir ?? 1000)
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') == null || $get('materi_awal_id') == null)
                                    ->default(null)
                                    ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_awal')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->jumlah_ayat ?? 300)
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') != MateriSurat::class || $get('materi_awal_id') == null)
                                    ->default(null),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        Fieldset::make()
                            ->label('Materi Akhir')
                            ->schema([
                                ToggleButtons::make('materi_akhir_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->default(MateriSurat::class)
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('materi_akhir_id', null);
                                    }),

                                Select::make('materi_akhir_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') == null)
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_akhir_type')::find($value)?->nama,
                                    )
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('halaman_akhir', null);
                                        $set('ayat_akhir', null);
                                    }),

                                TextInput::make('halaman_akhir')
                                    ->numeric()
                                    ->minValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_awal ?? 1)
                                    ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_akhir ?? 1000)
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') == null || $get('materi_akhir_id') == null)
                                    ->default(null)
                                    ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_akhir')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->jumlah_ayat ?? 300)
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') != MateriSurat::class || $get('materi_akhir_id') == null)
                                    ->default(null),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        TextInput::make('keterangan')
                            ->label('Detail Materi')
                            ->maxLength(255)
                            ->default(null),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_nama_rekaman')
                                ->label('Generate Nama Rekaman')
                                ->action(function (Forms\Get $get, Forms\Set $set, JurnalKelas $record){
                                    if (!filled($get('materi_awal_type')) || !filled($get('materi_akhir_type')) || !filled($get('materi_awal_id')) || !filled($get('materi_akhir_id'))
                                        || !filled($get('halaman_awal')) || !filled($get('halaman_akhir')) || ($get('materi_awal_type') == MateriSurat::class && (!filled($get('ayat_awal')) || !filled($get('ayat_akhir'))))){
                                        Notification::make()
                                            ->title('Isi semua data terlebih dahulu!')
                                            ->danger()
                                            ->send();
                                        $set('nama_berkas_rekaman', '');
                                    }
                                    else {
                                        $model = $record;
                                        $model->fill([
                                            'materi_awal_type' => $get('materi_awal_type'),
                                            'materi_akhir_type' => $get('materi_akhir_type'),
                                            'materi_awal_id' => $get('materi_awal_id'),
                                            'materi_akhir_id' => $get('materi_akhir_id'),
                                            'halaman_awal' => $get('halaman_awal') ?? null,
                                            'halaman_akhir' => $get('halaman_akhir') ?? null,
                                            'ayat_awal' => $get('ayat_awal') ?? null,
                                            'ayat_akhir' => $get('ayat_akhir') ?? null,
                                            'link_rekaman' => $get('link_rekaman'),
                                            'keterangan' => $get('keterangan'),
                                        ]);
                                        $set('nama_berkas_rekaman', getRekamanFilename($model));
                                    }
                                })
                        ]),

                        TextInput::make('nama_berkas_rekaman')
                            ->disabled()
                            ->label('Nama Berkas Rekaman'),

                        TextInput::make('link_rekaman')
                            ->label('Link Rekaman')
                    ])
                    ->action(fn (array $data, JurnalKelas $record) => $record->update([
                        'materi_awal_type' => $data['materi_awal_type'],
                        'materi_akhir_type' => $data['materi_akhir_type'],
                        'materi_awal_id' => $data['materi_awal_id'],
                        'materi_akhir_id' => $data['materi_akhir_id'],
                        'halaman_awal' => $data['halaman_awal'] ?? null,
                        'halaman_akhir' => $data['halaman_akhir'] ?? null,
                        'ayat_awal' => $data['ayat_awal'] ?? null,
                        'ayat_akhir' => $data['ayat_akhir'] ?? null,
                        'link_rekaman' => $data['link_rekaman'],
                        'keterangan' => $data['keterangan'],
                    ])),

                Action::make('viewRekaman')
                    ->label('Lihat Rekaman')
                    ->modalSubmitAction(false)
                    ->hidden(function (JurnalKelas $record){
                        return !auth()->user()->cekKehadiran($record) || isNotSuperAdmin();
                    })
                    ->color('info')
                    ->fillForm(function (JurnalKelas $record): array {
                       return [
                                'materi_awal_type' => $record->materi_awal_type,
                                'materi_akhir_type' => $record->materi_akhir_type,
                                'materi_awal_id' => $record->materi_awal_id,
                                'materi_akhir_id' => $record->materi_akhir_id,
                                'halaman_awal' => $record->halaman_awal,
                                'halaman_akhir' => $record->halaman_akhir,
                                'ayat_awal' => $record->ayat_awal,
                                'ayat_akhir' => $record->ayat_akhir,
                                'link_rekaman' => $record->link_rekaman,
                                'keterangan' =>  $record->keterangan,
                       ];}
                    )
                    ->form([
                        Fieldset::make()
                            ->label('Materi Awal')
                            ->schema([
                                ToggleButtons::make('materi_awal_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('materi_awal_id')
                                    ->hiddenLabel()
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_awal_type')::find($value)?->nama,
                                    )
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('halaman_awal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_awal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        Fieldset::make()
                            ->label('Materi Akhir')
                            ->schema([
                                ToggleButtons::make('materi_akhir_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->disabled()
                                    ->dehydrated(),

                                Select::make('materi_akhir_id')
                                    ->hiddenLabel()
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_akhir_type')::find($value)?->nama,
                                    )
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('halaman_akhir')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_akhir')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        TextInput::make('keterangan')
                            ->label('Detail Materi')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('link_rekaman')
                            ->label('Link Rekaman')
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ])
            ->bulkActions([
                BulkAction::make('updateMateriRekaman')
                    ->hidden(cant('ubah_materi_rekaman_jurnal::kelas') || isNotSuperAdmin())
                    ->label('Perbarui Materi & Rekaman')
                    ->color('secondary')
                    ->fillForm(function (Collection $records): array {
                       return [
                            'first_id' => $records->first()->id,
                            'materi_awal_type' => $records->first()->materi_awal_type,
                            'materi_akhir_type' => $records->first()->materi_akhir_type,
                            'materi_awal_id' => $records->first()->materi_awal_id,
                            'materi_akhir_id' => $records->first()->materi_akhir_id,
                            'halaman_awal' => $records->first()->halaman_awal,
                            'halaman_akhir' => $records->first()->halaman_akhir,
                            'ayat_awal' => $records->first()->ayat_awal,
                            'ayat_akhir' => $records->first()->ayat_akhir,
                            'link_rekaman' => $records->first()->link_rekaman,
                            'keterangan' =>  $records->first()->keterangan,
                       ];
                    })
                    ->form([
                        Hidden::make('first_id'),
                        Fieldset::make()
                            ->label('Materi Awal')
                            ->schema([
                                ToggleButtons::make('materi_awal_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->default(MateriSurat::class)
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('materi_awal_id', null);
                                    }),

                                Select::make('materi_awal_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') == null)
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_awal_type')::find($value)?->nama,
                                    )
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('halaman_awal', null);
                                        $set('ayat_awal', null);
                                    }),

                                TextInput::make('halaman_awal')
                                    ->numeric()
                                    ->minValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_awal ?? 1)
                                    ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_akhir ?? 1000)
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') == null || $get('materi_awal_id') == null)
                                    ->default(null)
                                    ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_awal')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->jumlah_ayat ?? 300)
                                    ->hidden(fn (Get $get) => $get('materi_awal_type') != MateriSurat::class || $get('materi_awal_id') == null)
                                    ->default(null),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        Fieldset::make()
                            ->label('Materi Akhir')
                            ->schema([
                                ToggleButtons::make('materi_akhir_type')
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options([
                                        MateriSurat::class => 'Al-Quran',
                                        MateriHimpunan::class => 'Himpunan',
                                        MateriTambahan::class => 'Lainnya',
                                    ])
                                    ->default(MateriSurat::class)
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('materi_akhir_id', null);
                                    }),

                                Select::make('materi_akhir_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') == null)
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                        $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                            ->limit(20)->pluck('nama', 'id')
                                            ->toArray(),
                                    )
                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                            $get('materi_akhir_type')::find($value)?->nama,
                                    )
                                    ->live()
                                    ->afterStateUpdated(function(Set $set) {
                                        $set('halaman_akhir', null);
                                        $set('ayat_akhir', null);
                                    }),

                                TextInput::make('halaman_akhir')
                                    ->numeric()
                                    ->minValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_awal ?? 1)
                                    ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_akhir ?? 1000)
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') == null || $get('materi_akhir_id') == null)
                                    ->default(null)
                                    ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                TextInput::make('ayat_akhir')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->jumlah_ayat ?? 300)
                                    ->hidden(fn (Get $get) => $get('materi_akhir_type') != MateriSurat::class || $get('materi_akhir_id') == null)
                                    ->default(null),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ])
                            ->columnSpanFull(),

                        TextInput::make('keterangan')
                            ->label('Detail Materi')
                            ->maxLength(255)
                            ->default(null),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_nama_rekaman')
                                ->label('Generate Nama Rekaman')
                                ->action(function (Forms\Get $get, Forms\Set $set){
                                    if (!filled($get('materi_awal_type')) || !filled($get('materi_akhir_type')) || !filled($get('materi_awal_id')) || !filled($get('materi_akhir_id'))
                                        || !filled($get('halaman_awal')) || !filled($get('halaman_akhir')) || ($get('materi_awal_type') == MateriSurat::class && (!filled($get('ayat_awal')) || !filled($get('ayat_akhir'))))){
                                        Notification::make()
                                            ->title('Isi semua data terlebih dahulu!')
                                            ->danger()
                                            ->send();
                                        $set('nama_berkas_rekaman', '');
                                    }
                                    else {
                                        $model = JurnalKelas::find($get('first_id'))->first();
                                        $model->fill([
                                            'materi_awal_type' => $get('materi_awal_type'),
                                            'materi_akhir_type' => $get('materi_akhir_type'),
                                            'materi_awal_id' => $get('materi_awal_id'),
                                            'materi_akhir_id' => $get('materi_akhir_id'),
                                            'halaman_awal' => $get('halaman_awal') ?? null,
                                            'halaman_akhir' => $get('halaman_akhir') ?? null,
                                            'ayat_awal' => $get('ayat_awal') ?? null,
                                            'ayat_akhir' => $get('ayat_akhir') ?? null,
                                            'link_rekaman' => $get('link_rekaman'),
                                            'keterangan' => $get('keterangan'),
                                        ]);
                                        $set('nama_berkas_rekaman', getRekamanFilename($model));
                                    }
                                })
                        ]),

                        TextInput::make('nama_berkas_rekaman')
                                ->disabled()
                                ->label('Nama Berkas Rekaman'),

                        TextInput::make('link_rekaman')
                            ->label('Link Rekaman')
                    ])
                    ->action(function (array $data, Collection $records): void {
                        $records->each(
                            fn (JurnalKelas $selectedRecord) => $selectedRecord->update([
                                'materi_awal_type' => $data['materi_awal_type'],
                                'materi_akhir_type' => $data['materi_akhir_type'],
                                'materi_awal_id' => $data['materi_awal_id'],
                                'materi_akhir_id' => $data['materi_akhir_id'],
                                'halaman_awal' => $data['halaman_awal'] ?? null,
                                'halaman_akhir' => $data['halaman_akhir'] ?? null,
                                'ayat_awal' => $data['ayat_awal'] ?? null,
                                'ayat_akhir' => $data['ayat_akhir'] ?? null,
                                'link_rekaman' => $data['link_rekaman'],
                                'keterangan' => $data['keterangan'],
                            ]),
                        );
                    })
                    ->deselectRecordsAfterCompletion(),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(isNotSuperAdmin()),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->hidden(isNotSuperAdmin()),
                    Tables\Actions\RestoreBulkAction::make()
                        ->hidden(isNotSuperAdmin()),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewJurnalKelas::class,
            Pages\ManageJurnalKelasPresensiKelas::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnalKelas::route('/'),
            'qr-code-create' => Pages\QRCodeCreateJurnalKelas::route('/qr-code/create'),
            'create' => Pages\CreateJurnalKelas::route('/create'),
            'view' => Pages\ViewJurnalKelas::route('/{record}'),
            'edit' => Pages\EditJurnalKelas::route('/{record}/edit'),
            'presensi' => Pages\ManageJurnalKelasPresensiKelas::route('/{record}/presensi'),

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'rekap_kelas_lain',
            'ubah_materi_rekaman',
        ];
    }
}
