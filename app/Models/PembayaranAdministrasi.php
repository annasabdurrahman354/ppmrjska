<?php

namespace App\Models;

use App\Enums\PembayaranMelalui;
use App\Enums\StatusPembayaran;
use App\Enums\StatusVerifikasi;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranAdministrasi extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'pembayaran_administrasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tagihan_administrasi_id',
        'tanggal',
        'jumlah_pembayaran',
        'pembayaran_melalui',
        'catatan_bendahara',
        'status_pembayaran',
        'status_verifikasi',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_pembayaran' => 'integer',
        'pembayaran_melalui' => PembayaranMelalui::class,
        'status_pembayaran' => StatusPembayaran::class,
        'status_verifikasi' => StatusVerifikasi::class,
    ];
   

    public function tagihanAdministrasi(): BelongsTo
    {
        return $this->belongsTo(TagihanAdministrasi::class, 'tagihan_administrasi_id');
    }
}
