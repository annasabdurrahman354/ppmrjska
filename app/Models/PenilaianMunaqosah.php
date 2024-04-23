<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenilaianMunaqosah extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'penilaian_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'materi_munaqosah_id',
        'nilai_materi',
        'nilai_hafalan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nilai_materi' => 'array',
        'nilai_hafalan' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materiMunaqosah(): BelongsTo
    {
        return $this->belongsTo(MateriMunaqosah::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Penilaian Munaqosah '.$this->user->nama.': '. $this->materiMunaqosah->jenis_materi->getLabel(). ' ('.$this->materiMunaqosah->tahun_ajaran.')',
        );
    }
}
