<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalMunaqosah extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'jadwal_munaqosah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'materi_munaqosah_id',
        'waktu',
        'maksimal_pendaftar',
        'batas_awal_pendaftaran',
        'batas_akhir_pendaftaran',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'waktu' => 'timestamp:d-m-Y H:00',
        'maksimal_pendaftar' => 'integer',
        'batas_awal_pendaftaran' => 'timestamp:d-m-Y H:00',
        'batas_akhir_pendaftaran' => 'timestamp:d-m-Y H:00',
    ];

    public function materiMunaqosah(): BelongsTo
    {
        return $this->belongsTo(MateriMunaqosah::class);
    }

    public function plotJadwalMunaqosah(): HasMany
    {
        return $this->hasMany(PlotJadwalMunaqosah::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Jadwal Munaqosah '.$this->materiMunaqosah->kelas. ' (Semester '.$this->materiMunaqosah->semester.'): '.$this->materiMunaqosah->jenis_materi->getLabel().' ('.(string) $this->waktu.')',
        );
    }

    protected function recordTitleCalendar(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->materiMunaqosah->kelas. ': '.$this->materiMunaqosah->jenis_materi->getLabel().' ('.(string) $this->waktu.')',
        );
    }
}
