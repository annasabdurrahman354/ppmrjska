<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class KelompokKegiatan extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'kelompok_kegiatan';

    protected $fillable = [
        'plot_kegiatan_semester_id',
        'jenis_kelamin',
        'ketua_kelompok_id',
    ];

    protected $casts = [
        'jenis_kelamin' => JenisKelamin::class,
    ];

    public function plotKelompokKegiatanSemester()
    {
        return $this->belongsTo(PlotKelompokKegiatanSemester::class, 'plot_kegiatan_semester_id');
    }

    public function ketuaKelompok()
    {
        return $this->belongsTo(User::class, 'ketua_kelompok_id');
    }

    public function anggotaKelompokKegiatan()
    {
        return $this->hasMany(AnggotaKelompokKegiatan::class, 'kelompok_kegiatan_id');
    }
}
