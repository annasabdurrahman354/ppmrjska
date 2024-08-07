<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotJadwalMunaqosah extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'plot_jadwal_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jadwal_munaqosah_id',
        'user_id',
        'status_terlaksana'
    ];

    protected $casts = [
        'status_terlaksana' => 'boolean',
    ];

    public function jadwalMunaqosah(): BelongsTo
    {
        return $this->belongsTo(JadwalMunaqosah::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
