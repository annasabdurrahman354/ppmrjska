<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class GelombangPendaftaran extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'gelombang_pendaftaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'pendaftaran_id',
        'nomor_gelombang',
        'tanggal_awal_pendaftaran',
        'tanggal_akhir_pendaftaran',
        'tanggal_tes',
        'tanggal_pengumuman',
        'link_grup',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nomor_gelombang' => 'integer',
        'tanggal_awal_pendaftaran' => 'date',
        'tanggal_akhir_pendaftaran' => 'date',
        'tanggal_tes' => 'date',
        'tanggal_pengumuman' => 'date',
        'link_grup' => 'string',
    ];

    public function calonSantri(): HasMany
    {
        return $this->hasMany(CalonSantri::class);
    }
}
