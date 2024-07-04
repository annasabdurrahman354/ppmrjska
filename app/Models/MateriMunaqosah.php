<?php

namespace App\Models;

use App\Enums\JenisMateriMunaqosah;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JenisMateriMunaqosahEnum;

class MateriMunaqosah extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'materi_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas',
        'semester',
        'tahun_ajaran',
        'jenis_materi',
        'materi',
        'detail',
        'hafalan',
        'indikator_materi',
        'indikator_hafalan',
        'dewan_guru_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kelas' => 'string',
        'tahun_ajaran' => 'string',
        'jenis_materi' => JenisMateriMunaqosah::class,
        'materi' => 'array',
        'detail' => 'string',
        'semester' => 'integer',
        'hafalan' => 'array',
        'indikator_materi' => 'array',
        'indikator_hafalan' => 'array',
    ];

    public function dewanGuru(): BelongsTo
    {
        return $this->belongsTo(DewanGuru::class);
    }

    public function jadwalMunaqosah(): HasMany
    {
        return $this->hasMany(JadwalMunaqosah::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran', 'tahun_ajaran');
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Materi Munaqosah Kelas '.$this->kelas. ' (Semester '.$this->semester.'): '.$this->jenis_materi->getLabel(),
        );
    }

    protected static function booted(): void
    {
        static::created(function (MateriMunaqosah $record) {
            TahunAjaran::firstOrCreate(
                ['tahun_ajaran' =>  $record->tahun_ajaran],
            );
        });
    }
}
