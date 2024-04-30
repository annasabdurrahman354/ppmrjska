<?php

namespace App\Models;

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
        'nilai_bacaan',
        'nilai_pegon',
        'nilai_pengetahuan',
        'nilai_wawasan',
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
        'nilai_bacaan' => 'integer',
        'nilai_pegon' => 'integer',
        'nilai_pengetahuan' => 'integer',
        'nilai_wawasan' => 'integer',
        'catatan_penguji' => 'string',
        'rekomendasi_penguji' => 'string',
        'status_penerimaan' => 'string',
    ];

    public function calonSantri(): BelongsTo
    {
        return $this->belongsTo(CalonSantri::class);
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
