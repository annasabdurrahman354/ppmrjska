<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PlotKurikulum extends Model
{
    use HasFactory;

    protected $table = 'plot_kurikulum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kurikulum_id',
        'materi_type',
        'materi_id',
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

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function materi(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_type', 'materi_id');
    }
}
