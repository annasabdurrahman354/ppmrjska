<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'pendaftaran';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'tahun',
        'jumlah_gelombang',
        'kontak_panitia',
        'kontak_pengurus',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tahun' => 'integer',
        'jumlah_gelombang' => 'integer',
        'kontak_panitia' => 'array',
        'kontak_pengurus' => 'array',
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function gelombangPendaftaran(): HasMany
    {
        return $this->hasMany(GelombangPendaftaran::class);
    }
}
