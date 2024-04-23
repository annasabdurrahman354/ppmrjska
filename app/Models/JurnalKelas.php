<?php

namespace App\Models;

use App\Enums\JenisKelamin;
use App\Enums\Sesi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JurnalKelas extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'jurnal_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas',
        'jenis_kelamin',
        'tanggal',
        'sesi',
        'materi_awal_type',
        'materi_akhir_type',
        'materi_awal_id',
        'materi_akhir_id',
        'dewan_guru_type',
        'dewan_guru_id',
        'halaman_awal',
        'halaman_akhir',
        'ayat_awal',
        'ayat_akhir',
        'link_rekaman',
        'keterangan',
        'perekap_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kelas' => 'array',
        'jenis_kelamin' => JenisKelamin::class,
        'tanggal' => 'date',
        'sesi' => Sesi::class,
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
        'ayat_awal' => 'integer',
        'ayat_akhir' => 'integer',
        'link_rekaman' => 'string',
        'keterangan' => 'string',
    ];

    public function dewanGuru(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'dewan_guru_type', 'dewan_guru_id');
    }

    public function materiAwal(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_awal_type', 'materi_awal_id');
    }

    public function materiAkhir(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_akhir_type', 'materi_akhir_id');
    }

    public function perekap(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function presensiKelas(): HasMany
    {
        return $this->hasMany(PresensiKelas::class);
    }

    public function deleteAllPresensi()
    {
        return $this->presensiKelas()->delete(); // This line is corrected
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Jurnal Kelas ['.implode(",", $this->kelas).']: '.$this->tanggal->format('j F, Y').' ('.$this->sesi->getLabel().')',
        );
    }

    protected function namaPerekap(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->perekap->nama ?? '',
        );
    }

    protected function kbmKelas(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal->format('j F, Y'). ' ('.$this->sesi->getLabel().')',
        );
    }
}
