<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class JurnalKegiatan extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'jurnal_kegiatan';

    protected $fillable = [
        'jenis_kegiatan_id',
        'tanggal',
        'jenis_kelamin',
        'grup_type',
        'grup',
        'perekap_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jenis_kelamin' => JenisKelamin::class
    ];

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }

    public function grup(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'grup_type', 'grup_id');
    }

    public function perekap()
    {
        return $this->belongsTo(User::class, 'perekap_id');
    }

    public function presensiKegiatan()
    {
        return $this->hasMany(PresensiKegiatan::class, 'jurnal_kegiatan_id');
    }

    public static function getForm()
    {
        return [
            Section::make('Informasi Kegiatan')
                ->schema([
                    DatePicker::make('tanggal')
                        ->label('Tanggal Kegiatan')
                        ->required()
                        ->default(now()),

                    Select::make('kelas')
                        ->label('Kelas')
                        ->multiple()
                        ->required()
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
                        ->disabled(cant('rekap_kelas_lain_jurnal::kelas'))->dehydrated()
                        ->options(JenisKelamin::class)
                        ->default(auth()->user()->jenis_kelamin)
                        ->live()
                        ->afterStateUpdated(function(Get $get, Set $set, $state) {
                            $users = User::whereKelasIn($get('kelas'))
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
                        ->extraAttributes(['class' => 'p-0'])
                        ->deletable(false)
                        ->addable(false)
                        ->live()
                        ->default(function(Get $get) {
                            $users = User::whereKelas($get('kelas'))
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
        ];
    }

}
