<?php

namespace App\Models;

use App\Enums\JenisTagihan;
use App\Enums\PeriodeTagihan;
use App\Enums\StatusTagihan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Administrasi extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'administrasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tahun_ajaran',
        'jenis_tagihan',
        'periode_tagihan',
        'kelas',
        'biaya_administrasi',
        'biaya_tambahan',
        'deskripsi_biaya_tambahan',
        'batas_awal_pembayaran',
        'batas_akhir_pembayaran',
        'rekening_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'kelas' => 'array',
        'batas_awal_pembayaran' => 'date',
        'batas_akhir_pembayaran' => 'date',
        'biaya_administrasi' => 'integer',
        'biaya_tambahan' => 'integer',
        'jenis_tagihan' => JenisTagihan::class,
        'periode_tagihan' => PeriodeTagihan::class,
    ];

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Administrasi '.(string) $this->tahun_ajaran.' Kelas '.implode(",", $this->kelas),
        );
    }

    public function rekening(): HasOne
    {
        return $this->hasOne(Rekening::class);
    }

    public function tagihanAdministrasi(): HasMany
    {
        return $this->hasMany(TagihanAdministrasi::class, 'administrasi_id');
    }

    public function pembayaranAdministrasi(): HasManyThrough
    {
        return $this->hasManyThrough(PembayaranAdministrasi::class, TagihanAdministrasi::class, 'administrasi_id', 'tagihan_administrasi_id');
    }

    public function santriTagihanLunas()
    {
        return $this->tagihanAdministrasi()->where('status_tagihan', StatusTagihan::LUNAS->value)->get();
    }

    public function santriTagihanBelumLunas()
    {
        return $this->tagihanAdministrasi()->whereNot('status_tagihan', StatusTagihan::LUNAS->value)->get();
    }

    public function countTagihanLunas(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tagihanAdministrasi()->where('status_tagihan', StatusTagihan::LUNAS->value)->count(),
        );
    }

    public function countTagihanBelumLunas(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tagihanAdministrasi()->whereNot('status_tagihan', StatusTagihan::LUNAS->value)->count(),
        );
    }

    public function sumJumlahPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->pembayaranAdministrasi()->sum('jumlah_pembayaran'),
        );
    }
}
