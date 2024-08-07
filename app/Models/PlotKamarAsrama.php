<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PlotKamarAsrama extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

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

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    protected function namaUser(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->nama
        );
    }

    protected static function booted(): void
    {
        parent::boot();
        static::created(function (PlotKamarAsrama $record) {
            TahunAjaran::firstOrCreate(
                ['tahun_ajaran' =>  $record->tahun_ajaran],
            );
        });
    }
}
