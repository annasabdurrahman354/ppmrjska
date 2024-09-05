<?php

namespace App\Models;

use App\Enums\SistemPresensi;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Role;

class JenisKegiatan extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'jenis_kegiatan';

    protected $fillable = [
        'nama',
        'sistem_presensi',
        'pemilik_proker_id',
    ];

    protected $casts = [
      'sistem_presensi' => SistemPresensi::class,
    ];

    public function pemilikProker()
    {
        return $this->belongsTo(Role::class, 'pemilik_proker_id');
    }

    public function plotKelompokKegiatanSemester()
    {
        return $this->hasMany(PlotKelompokKegiatanSemester::class, 'jenis_kegiatan_id');
    }

    public function jurnalKegiatan()
    {
        return $this->hasMany(JurnalKegiatan::class, 'jenis_kegiatan_id');
    }

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->label('Nama Kegiatan')
                ->required()
                ->unique(ignoreRecord: true),
            ToggleButtons::make('sistem_presensi')
                ->label('Sistem Presensi')
                ->options(SistemPresensi::class)
                ->inline()
                ->grouped()
                ->required(),
            Select::make('pemilik_proker_id')
                ->label('Pemilik Proker')
                ->relationship('pemilikProker')
                ->required()
        ];
    }
}
