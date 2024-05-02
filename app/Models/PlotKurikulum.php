<?php

namespace App\Models;

use App\Enums\JenjangKelas;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotKurikulum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'plot_kurikulum';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kurikulum_id',
        'jenjang_kelas',
        'semester'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'jenjang_kelas' => JenjangKelas::class,
        'semester' => 'integer'
    ];

    public function kurikulum(): BelongsTo
    {
       return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function plotKurikulumMateri(): HasMany
    {
        return $this->hasMany(PlotKurikulumMateri::class);
    }
}
