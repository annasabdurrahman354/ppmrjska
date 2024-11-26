<?php

namespace App\Models;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriTambahan extends Model
{
    use HasFactory;

    protected $table = 'materi_tambahan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'jumlah_halaman',
        'halaman_awal',
        'halaman_akhir',
        'link_materi',
    ];

    protected $casts = [
        'nama' => 'string',
        'jumlah_halaman' => 'integer',
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
        'link_materi' => 'string',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->required()
                ->maxLength(96),
            TextInput::make('jumlah_halaman')
                ->numeric(),
            TextInput::make('halaman_awal')
                ->numeric(),
            TextInput::make('halaman_akhir')
                ->numeric(),
            Textarea::make('link_materi')
                ->columnSpanFull(),
        ];
    }

    protected static function booted(): void
    {
        parent::boot();
        static::deleted(function ($record) {
            JurnalKelas::where('materi_awal_type', get_class($record))->where('materi_awal_id', $record->id)->update(['materi_awal_type' => null, 'materi_awal_id' => null]);
            JurnalKelas::where('materi_akhir_type', get_class($record))->where('materi_akhir_id', $record->id)->update(['materi_akhir_type' => null, 'materi_akhir_id' => null]);
            PlotKurikulumMateri::where('materi_type', get_class($record))->where('materi_id', $record->id)->delete();
        });
    }
}
