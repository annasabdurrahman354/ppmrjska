<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlotKamarAsrama extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'plot_kamar_asrama';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_ajaran',
        'kamar_asrama_id',
        'user_id',
    ];

    public function kamarAsrama(): BelongsTo
    {
        return $this->belongsTo(KamarAsrama::class, 'kamar_asrama_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
