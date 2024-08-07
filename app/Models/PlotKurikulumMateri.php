<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotKurikulumMateri extends Model
{
    use HasFactory;

    protected $table = 'plot_kurikulum_materi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plot_kurikulum_id',
        'materi_id',
        'materi_type',
        'status_tercapai'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_tercapai' => 'boolean',
    ];

    public function plotKurikulum(): BelongsTo
    {
        return $this->belongsTo(PlotKurikulum::class, 'plot_kurikulum_id');
    }

    public function materi(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_type', 'materi_id');
    }
}
