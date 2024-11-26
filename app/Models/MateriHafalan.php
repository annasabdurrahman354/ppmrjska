<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriHafalan extends Model
{
    use HasFactory;

    protected $table = 'materi_hafalan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
    ];

    protected $casts = [
        'nama' => 'string',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('nama')
                ->required()
                ->maxLength(96),
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
