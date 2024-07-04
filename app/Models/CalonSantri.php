<?php

namespace App\Models;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CalonSantri extends Model implements HasMedia
{
    use InteractsWithMedia, HasUlids, SoftDeletes;

    protected $table = 'calon_santri';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'gelombang_pendaftaran_id',
        'nama',
        'nama_panggilan',
        'jenis_kelamin',
        'nomor_telepon',
        'email',
        'nik',
        'status_mubaligh',
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
        'status_mubaligh' => 'boolean',
        'jenis_kelamin' => JenisKelamin::class,
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

    public function gelombangPendaftaran(): BelongsTo
    {
        return $this->belongsTo(GelombangPendaftaran::class, 'gelombang_pendaftaran_id');
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

    public function tempatLahir(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'tempat_lahir_id');
    }
}
