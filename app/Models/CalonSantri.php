<?php

namespace App\Models;

use App\Enums\GolonganDarah;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class CalonSantri extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'calon_santri';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'gelombang_pendaftaran_id',
        'nama',
        'nik',
        'nomor_telepon',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'ukuran_baju',
        'kewarganegaraan',
        'status_menikah',
        'status_orangtua',
        'status_tinggal',
        'anak_ke',
        'jumlah_saudara',
        'pendidikan_terakhir',
        'prodi',
        'universitas',
        'angkatan_kuliah',
        'alamat',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'kelurahan_id',
        'daerah',
        'desa',
        'kelompok',
        'mulai_mengaji',
        'nama_ayah',
        'alamat_ayah',
        'nomor_telepon_ayah',
        'pekerjaan_ayah',
        'dapukan_ayah',
        'nama_ibu',
        'alamat_ibu',
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
        'gelombang_pendaftaran_id' => 'integer',
        'nama' => 'string',
        'nik' => 'integer',
        'nomor_telepon' => 'string',
        'email' => 'string',
        'jenis_kelamin' => 'boolean',
        'tempat_lahir' => 'string',
        'tanggal_lahir' => 'date',
        'golongan_darah' => GolonganDarah::class,
        'ukuran_baju' => UkuranBaju::class,
        'kewarganegaraan' => Kewarganegaraan::class,
        'status_menikah' => StatusPernikahan::class,
        'status_orangtua' => StatusOrangTua::class,
        'status_tinggal' => StatusTinggal::class,
        'anak_ke' => 'integer',
        'jumlah_saudara' => 'integer',
        'pendidikan_terakhir' => PendidikanTerakhir::class,
        'prodi' => 'string',
        'universitas' => 'string',
        'angkatan_kuliah' => 'integer',
        'alamat' => 'string',
        'daerah' => 'string',
        'desa' => 'string',
        'kelompok' => 'string',
        'mulai_mengaji' => MulaiMengaji::class,
        'nama_ayah' => 'string',
        'alamat_ayah' => 'string',
        'nomor_telepon_ayah' => 'string',
        'pekerjaan_ayah' => 'string',
        'dapukan_ayah' => 'string',
        'nama_ibu' => 'string',
        'alamat_ibu' => 'string',
        'nomor_telepon_ibu' => 'string',
        'pekerjaan_ibu' => 'string',
        'dapukan_ibu' => 'string',
    ];

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class);
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}
