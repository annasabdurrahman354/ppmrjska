<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PresensiKegiatan extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'presensi_kegiatan';

    protected $fillable = [
        'jurnal_kegiatan_id',
        'user_id',
        'status_kehadiran',
    ];

    protected $casts = [
        'status_kehadiran' => 'boolean',
    ];

    public function jurnalKegiatan()
    {
        return $this->belongsTo(JurnalKegiatan::class, 'jurnal_kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
