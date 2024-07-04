<?php

namespace App\Models;

use App\Enums\JenisAdministrasi;
use App\Enums\JenisTagihan;
use App\Enums\PeriodeTagihan;
use App\Enums\StatusTagihan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'jenis_administrasi',
        'nama_administrasi',
        'jenis_tagihan',
        'periode_tagihan',
        'sasaran',
        'nominal_tagihan',
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
        'sasaran' => 'array',
        'batas_awal_pembayaran' => 'date',
        'batas_akhir_pembayaran' => 'date',
        'nominal_tagihan' => 'integer',
        'jenis_administrasi' => JenisAdministrasi::class,
        'jenis_tagihan' => JenisTagihan::class,
        //'periode_tagihan' => PeriodeTagihan::class,
    ];

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nama_administrasi.' Kelas '.implode(",", $this->sasaran),
        );
    }

    public function rekening(): BelongsTo
    {
        return $this->belongsTo(Rekening::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    public function tagihanAdministrasi(): HasMany
    {
        return $this->hasMany(TagihanAdministrasi::class, 'administrasi_id');
    }

    public function pembayaranAdministrasi(): HasManyThrough
    {
        return $this->hasManyThrough(PembayaranAdministrasi::class, TagihanAdministrasi::class, 'administrasi_id', 'tagihan_administrasi_id');
    }

    public function tagihanLunas()
    {
        return $this->tagihanAdministrasi()->where('status_tagihan', StatusTagihan::LUNAS->value)->get();
    }

    public function tagihanBelumLunas()
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

    protected static function booted(): void
    {
        static::created(function (Administrasi $record) {
            TahunAjaran::firstOrCreate(
                ['tahun_ajaran' =>  $record->tahun_ajaran],
            );
        });
    }
}
