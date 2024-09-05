<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\Sesi;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class JurnalKelas extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'jurnal_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas',
        'jenis_kelamin',
        'tanggal',
        'sesi',
        'materi_awal_type',
        'materi_akhir_type',
        'materi_awal_id',
        'materi_akhir_id',
        'dewan_guru_type',
        'dewan_guru_id',
        'halaman_awal',
        'halaman_akhir',
        'ayat_awal',
        'ayat_akhir',
        'link_rekaman',
        'keterangan',
        'perekap_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kelas' => 'array',
        'jenis_kelamin' => JenisKelamin::class,
        'tanggal' => 'date',
        'sesi' => Sesi::class,
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
        'ayat_awal' => 'integer',
        'ayat_akhir' => 'integer',
        'link_rekaman' => 'string',
        'keterangan' => 'string',
    ];

    public function statusKehadiranSaya(): Attribute
    {
        return Attribute::make(
            get: function (){
                if ($this->presensikelas()->where('user_id', auth()->user()->id)->exists()){
                    return match ($this->presensikelas()->where('user_id', auth()->user()->id)->first()->status_kehadiran->value) {
                        'hadir' => 'Hadir',
                        'telat' => 'Telat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa',
                        'Bukan Kelas' => 'Bukan Kelas',
                    };
                }
                else{
                    return 'Bukan Kelas';
                }
            }
        );
    }

    public function dewanGuru(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'dewan_guru_type', 'dewan_guru_id');
    }

    public function materiAwal(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_awal_type', 'materi_awal_id');
    }

    public function materiAkhir(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_akhir_type', 'materi_akhir_id');
    }

    public function perekap(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function presensiKelas(): HasMany
    {
        return $this->hasMany(PresensiKelas::class);
    }

    public function deleteAllPresensi()
    {
        return $this->presensiKelas()->delete();
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Jurnal Kelas ['.implode(",", $this->kelas).']: '.$this->tanggal->format('j F, Y').' ('.$this->sesi->getLabel().')',
        );
    }

    protected function namaPerekap(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->perekap->nama ?? '',
        );
    }

    protected function kbmKelas(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal->format('j F, Y'). ' ('.$this->sesi->getLabel().')',
        );
    }

    protected function hadirCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->presensiKelas()->where('status_kehadiran', StatusKehadiran::HADIR)->count(),
        );
    }

    protected function telatCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->presensiKelas()->where('status_kehadiran', StatusKehadiran::TELAT)->count(),
        );
    }

    protected function izinCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->presensiKelas()->where('status_kehadiran', StatusKehadiran::IZIN)->count(),
        );
    }

    protected function sakitCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->presensiKelas()->where('status_kehadiran', StatusKehadiran::SAKIT)->count(),
        );
    }

    protected function alpaCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->presensiKelas()->where('status_kehadiran', StatusKehadiran::ALPA)->count(),
        );
    }

    public static function getForm($useQRCodeScanner)
    {
       return [
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
                                       ->options(Sesi::class)
                                       ->live()
                                       ->afterStateUpdated( $useQRCodeScanner ? function ($state, Set $set){
                                           $set('waktu_terlambat', match ($state) {
                                               Sesi::SUBUH->value => '05:00',
                                               Sesi::PAGI_1->value => '08:45',
                                               Sesi::PAGI_2->value => '10:15',
                                               Sesi::SIANG->value => '13:45',
                                               Sesi::MALAM->value => '20:00',
                                           });
                                       } : function () {

                                       }),

                                   $useQRCodeScanner ? TimePicker::make('waktu_terlambat')
                                       ->seconds(false)
                                       ->default(fn (Get $get ) =>
                                       match ($get('sesi')) {
                                           Sesi::SUBUH->value => '05:15',
                                           Sesi::PAGI_1->value => '08:45',
                                           Sesi::PAGI_2->value => '10:15',
                                           Sesi::SIANG->value => '13:45',
                                           Sesi::MALAM->value => '20:00',
                                           default => null
                                       }

                                       ) : Hidden::make('waktu_terlambat'),

                                   Select::make('kelas')
                                       ->label('Kelas')
                                       ->multiple()
                                       ->required()
                                       ->maxItems(fn () => cant('rekap_kelas_lain_jurnal::kelas') ? 1 : 6)
                                       ->disabledOn('edit')
                                       ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                                       ->options(
                                           AngkatanPondok::whereHas('users', function ($query) {
                                                   $query->whereIn('status_pondok', [StatusPondok::AKTIF, StatusPondok::KEPERLUAN_AKADEMIK, StatusPondok::SAMBANG, StatusPondok::NONAKTIF]);
                                               })
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
                                           $users = User::whereKelasIn($state)
                                               ->where('jenis_kelamin', $get('jenis_kelamin'))
                                               ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
                                               ->whereNull('tanggal_lulus_pondok')
                                               ->orderBy('angkatan_pondok')
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
                                       ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                                       ->options(JenisKelamin::class)
                                       ->default(auth()->user()->jenis_kelamin)
                                       ->live()
                                       ->afterStateUpdated(function(Get $get, Set $set, $state) {
                                           $users = User::whereKelasIn($get('kelas'))
                                               ->where('jenis_kelamin', $state)
                                               ->where('status_pondok', StatusPondok::AKTIF->value)
                                               ->whereNull('tanggal_lulus_pondok')
                                               ->orderBy('angkatan_pondok')
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
                                       ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
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
                                                           ->whereKelas('takmili')
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

                                   $useQRCodeScanner ? ViewField::make('qr-code')
                                       ->hiddenLabel()
                                       ->view('filament.fields.qr-code-scanner') : Hidden::make('qr-code'),

                                   Repeater::make('presensiKelas')
                                       ->hiddenLabel()
                                       ->relationship('presensiKelas')
                                       ->extraAttributes(['class' => 'p-0'])
                                       ->deletable(false)
                                       ->addable(false)
                                       ->live()
                                       ->default(function(Get $get) {
                                           $users = User::whereKelasIn($get('kelas'))
                                               ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
                                               ->whereNull('tanggal_lulus_pondok')
                                               ->where('jenis_kelamin', $get('jenis_kelamin'))
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
                                               ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                                               ->searchable()
                                               ->preload()
                                               ->getSearchResultsUsing(fn (string $search, Get $get): array =>
                                                   User::where('nama', 'like', "%{$search}%")
                                                       ->where('jenis_kelamin', $get('../../jenis_kelamin'))
                                                       ->whereKelasIn($get('../../kelas'))
                                                       ->whereNotIn('status_pondok', [StatusPondok::NONAKTIF, StatusPondok::KELUAR, StatusPondok::LULUS])
                                                       ->whereNull('tanggal_lulus_pondok')
                                                       ->limit(20)
                                                       ->pluck('nama', 'id')
                                                       ->toArray()
                                               )
                                               ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                               ->columnSpan(4)
                                               ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

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
                       ->schema(JurnalKelas::getJurnalKelasMateriForm()),
               ]),
       ];
    }

    public static function getJurnalKelasMateriForm(){
        return [
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
                        ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas...')
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
                        ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas...')
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

            Actions::make([
                Action::make('generate_nama_rekaman')
                    ->label('Generate Nama Rekaman')
                    ->action(function (Get $get, Set $set){
                        if (!filled($get('materi_awal_type')) || !filled($get('materi_akhir_type')) || !filled($get('materi_awal_id')) || !filled($get('materi_akhir_id'))
                            || !filled($get('halaman_awal')) || !filled($get('halaman_akhir')) || ($get('materi_awal_type') == MateriSurat::class && (!filled($get('ayat_awal')) || !filled($get('ayat_akhir'))))){
                            Notification::make()
                                ->title('Isi semua data terlebih dahulu!')
                                ->danger()
                                ->send();
                            $set('nama_berkas_rekaman', '');
                        }
                        else {
                            $model = new JurnalKelas();
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
        ];
    }
}
