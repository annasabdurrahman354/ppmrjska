<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class GelombangPendaftaran extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    use SoftCascadeTrait;

    protected $softCascade = ['calonSantri'];

    protected $table = 'gelombang_pendaftaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'pendaftaran_id',
        'nomor_gelombang',
        'batas_awal_pendaftaran',
        'batas_akhir_pendaftaran',
        'timeline',
        'link_grup',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nomor_gelombang' => 'integer',
        'batas_awal_pendaftaran' => 'date',
        'batas_akhir_pendaftaran' => 'date',
        'timeline' => 'array',
    ];

    public function calonSantri(): HasMany
    {
        return $this->hasMany(CalonSantri::class);
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Pendaftaran '.(string) $this->pendaftaran->tahun_pendaftaran. ' (Gel.'. (string) $this->nomor_gelombang.')',
        );
    }
}
