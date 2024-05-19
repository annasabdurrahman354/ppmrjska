<?php

namespace App\Models;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiodataSantri extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'biodata_santri';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tahun_pendaftaran',
        'nik',
        'tempat_lahir_id',
        'tanggal_lahir',
        'golongan_darah',
        'ukuran_baju',
        'pendidikan_terakhir',
        'program_studi',
        'universitas',
        'angkatan_kuliah',
        'status_kuliah',
        'tanggal_lulus_kuliah',
        'alamat',
        'kelurahan_id',
        'kecamatan_id',
        'kota_id',
        'provinsi_id',
        'asal_kelompok',
        'asal_desa',
        'asal_daerah',
        'mulai_mengaji',
        'bahasa_makna',
        'kewarganegaraan',
        'status_pernikahan',
        'status_tinggal',
        'status_orangtua',
        'anak_nomor',
        'jumlah_saudara',
        'nama_ayah',
        'nomor_telepon_ayah',
        'pekerjaan_ayah',
        'dapukan_ayah',
        'nama_ibu',
        'nomor_telepon_ibu',
        'pekerjaan_ibu',
        'dapukan_ibu',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tahun_pendaftaran' => 'integer',
        'tanggal_lahir' => 'date',
        'golongan_darah' => GolonganDarah::class,
        'ukuran_baju' => UkuranBaju::class,
        'pendidikan_terakhir' => PendidikanTerakhir::class,
        'angkatan_kuliah' => 'integer',
        'status_kuliah' => StatusKuliah::class,
        'tanggal_lulus_kuliah' => 'date',
        'kelurahan_id' => 'integer',
        'kecamatan_id' => 'integer',
        'kota_id' => 'integer',
        'provinsi_id' => 'integer',
        'mulai_mengaji' => MulaiMengaji::class,
        'bahasa_makna' => BahasaMakna::class,
        'kewarganegaraan' => Kewarganegaraan::class,
        'status_pernikahan' => StatusPernikahan::class,
        'status_tinggal' => StatusTinggal::class,
        'status_orangtua' => StatusOrangTua::class,
        'anak_nomor' => 'integer',
        'jumlah_saudara' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tempatLahir(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'tempat_lahir_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Biodata '.$this->user->nama_panggilan.'('.$this->user->angkatan_pondok.')',
        );
    }

}
