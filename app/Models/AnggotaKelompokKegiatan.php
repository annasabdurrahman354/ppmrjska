<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnggotaKelompokKegiatan extends Model
{
    use HasFactory, SoftDeletes, HasUlids;

    protected $table = 'anggota_kelompok_kegiatan';

    protected $fillable = [
        'kelompok_kegiatan_id',
        'user_id',
    ];

    public function kelompokKegiatan()
    {
        return $this->belongsTo(KelompokKegiatan::class, 'kelompok_kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
