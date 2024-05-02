<?php

namespace App\Models;

use App\Enums\StatusPenerimaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PenilaianCalonSantri extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'penilaian_calon_santri';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'calon_santri_id',
        'penguji_id',
        'nilai_tes',
        'nilai_akhir',
        'catatan_penguji',
        'rekomendasi_penguji',
        'status_penerimaan',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $casts = [
        'nilai_tes' => 'array',
        'nilai_akhir' => 'integer',
        'catatan_penguji' => 'string',
        'rekomendasi_penguji' => 'integer',
        'status_penerimaan' => StatusPenerimaan::class,
    ];

    public function calonSantri(): BelongsTo
    {
        return $this->belongsTo(CalonSantri::class, 'calon_santri_id');
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }
}
