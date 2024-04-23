<?php

namespace App\Models;

use App\Enums\StatusKehadiran;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiKelas extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'presensi_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jurnal_kelas_id',
        'user_id',
        'status_kehadiran',
    ];

    protected $casts = [
        'status_kehadiran' => StatusKehadiran::class,
    ];


    public function jurnalKelas(): BelongsTo
    {
        return $this->belongsTo(\App\Models\JurnalKelas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
