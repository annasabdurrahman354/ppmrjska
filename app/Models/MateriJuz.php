<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriJuz extends Model
{
    use HasFactory;

    protected $table = 'materi_juz';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'halaman_awal',
        'halaman_akhir',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nama' => 'string',
        'halaman_awal' => 'integer',
        'halaman_akhir' => 'integer',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->required()
                ->maxLength(6),
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
            PlotKurikulumMateri::where('materi_type', get_class($record))->where('materi_id', $record->id)->delete();
        });
    }
}
