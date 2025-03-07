<?php

namespace App\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KamarAsrama extends Model
{
    use HasFactory, HasUlids, SoftDeletes;
    use SoftCascadeTrait;

    protected $softCascade = ['plotKamarAsrama'];

    protected $table = 'kamar_asrama';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asrama_id',
        'lantai',
        'nomor_kamar',
        'status_ketersediaan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'lantai' => 'integer',
        'status_ketersediaan' => 'boolean',
    ];

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function plotKamarAsrama(): HasMany
    {
        return $this->hasMany(PlotKamarAsrama::class);
    }
}
