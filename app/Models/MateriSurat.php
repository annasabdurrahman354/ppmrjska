<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriSurat extends Model
{
    use HasFactory;

    protected $table = 'materi_surat';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor',
        'nama',
        'jumlah_ayat',
        'jumlah_halaman',
        'halaman_awal',
        'halaman_akhir',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nomor' => 'integer',
        'nama' => 'string',
        'jumlah_ayat' => 'integer',
        'jumlah_halaman' => 'integer',
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('nomor')
                ->required()
                ->numeric(),
            TextInput::make('nama')
                ->required()
                ->maxLength(96),
            TextInput::make('jumlah_ayat')
                ->required()
                ->numeric(),
            TextInput::make('jumlah_halaman')
                ->required()
                ->numeric(),
            TextInput::make('halaman_awal')
                ->required()
                ->numeric(),
            TextInput::make('halaman_akhir')
                ->required()
                ->numeric(),
        ];
    }

    protected static function booted(): void
    {
        parent::boot();
        static::deleted(function ($record) {
            JurnalKelas::where('materi_awal_type', $this::class)->where('materi_awal_id', $record->id)->update(['materi_awal_type' => null, 'materi_awal_id' => null]);
            JurnalKelas::where('materi_akhir_type', $this::class)->where('materi_akhir_id', $record->id)->update(['materi_akhir_type' => null, 'materi_akhir_id' => null]);
            PlotKurikulumMateri::where('materi_type', $this::class)->where('materi_id', $record->id)->delete();
        });
    }
}
