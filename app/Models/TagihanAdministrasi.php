<?php

namespace App\Models;

use App\Enums\StatusTagihan;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagihanAdministrasi extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'tagihan_administrasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'administrasi_id',
        'user_id',
        'jumlah_tagihan_administrasi',
        'jumlah_tagihan_asrama',
        'jumlah_tagihan_tambahan',
        'jumlah_tagihan_total',
        'status_tagihan'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'jumlah_tagihan_administrasi' => 'integer',
        'jumlah_tagihan_asrama' => 'integer',
        'jumlah_tagihan_tambahan' => 'integer',
        'jumlah_tagihan_total' => 'integer',
        'status_tagihan' => StatusTagihan::class,
    ];

    public function administrasi(): BelongsTo
    {
        return $this->belongsTo(Administrasi::class, 'administrasi_id');
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
