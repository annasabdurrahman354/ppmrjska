<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\Semester;
use App\Enums\SistemPresensi;
use App\Enums\StatusPondok;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PlotKelompokKegiatanSemester extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'plot_kelompok_kegiatan_semester';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'jenis_kegiatan_id',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }

    public function kelompokKegiatan()
    {
        return $this->hasMany(KelompokKegiatan::class, 'plot_kegiatan_semester_id');
    }

    public static function getForm()
    {
        return [
            Select::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->options(TahunAjaran::all()->pluck('tahun_ajaran', 'tahun_ajaran'))
                ->searchable()
                ->preload()
                ->createOptionForm(TahunAjaran::getForm())
                ->required(),
            ToggleButtons::make('semester')
                ->label('Semester')
                ->options(Semester::class)
                ->inline()
                ->grouped()
                ->required(),
            Select::make('jenis_kegiatan_id')
                ->label('Pemilik Proker')
                ->relationship(
                    name: 'jenisKegiatan',
                    titleAttribute: 'nama',
                    modifyQueryUsing: fn (Builder $query) => $query->where('sistem_presensi', SistemPresensi::KELOMPOK),
                )
                ->searchable()
                ->preload(),
            Repeater::make('kelompokKegiatan')
                ->label('Kelompok Kegiatan')
                ->relationship('kelompokKegiatan')
                ->required()
                ->schema([
                    ToggleButtons::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(JenisKelamin::class)
                        ->inline()
                        ->grouped()
                        ->live(),
                    Select::make('ketua_kelompok_id')
                        ->label('Ketua Kelompok')
                        ->relationship(
                            'ketuaKelompok',
                            'nama',
                            modifyQueryUsing: fn (Builder $query, Get $get) =>
                            $query->where('jenis_kelamin', $get('jenis_kelamin'))
                                ->whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
                                ->whereNull('tanggal_lulus_pondok')
                        )
                        ->searchable()
                        ->visible(fn (Get $get) => filled($get('jenis_kelamin')))
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (?string $state, ?string $old, Get $get, Set $set) {
                            $anggotaKelompokKegiatan = filled($get('anggotaKelompokKegiatan'))
                                ? (array) $get('anggotaKelompokKegiatan')
                                : [];
                            $anggotaKelompokKegiatan[] = [
                                'user_id' => $state
                            ];
                            $set('anggotaKelompokKegiatan', $anggotaKelompokKegiatan);
                        }),
                    TableRepeater::make('anggotaKelompokKegiatan')
                        ->label('Anggota Kelompok')
                        ->relationship('anggotaKelompokKegiatan')
                        ->visible(fn (Get $get) => filled($get('ketua_kelompok_id')))
                        ->required()
                        ->headers([
                            Header::make('Nama'),
                            Header::make('Kelas')
                        ])
                        ->schema([
                            Select::make('user_id')
                                ->relationship(
                                    'user',
                                    'nama',
                                    modifyQueryUsing: fn (Builder $query, Get $get) =>
                                        $query->where('jenis_kelamin', $get('../../jenis_kelamin'))
                                            ->whereNotIn('status_pondok', [StatusPondok::KELUAR, StatusPondok::LULUS])
                                            ->whereNull('tanggal_lulus_pondok')
                                )
                                ->searchable()
                                ->required()
                                ->live(),
                            Placeholder::make('kelas')
                                ->content(fn (Get $get) => User::find($get('user_id'))->kelas)
                        ])
                ])
        ];
    }
}
