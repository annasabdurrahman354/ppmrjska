<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\KepemilikanGedung;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaAsrama extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'biaya_asrama';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_ajaran',
        'asrama_id',
        'biaya_kamar_tahunan',
        'dibayar_ke_bendahara',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'biaya_kamar_tahunan' => 'integer',
        'dibayar_ke_bendahara' => 'boolean',
    ];

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }
}
