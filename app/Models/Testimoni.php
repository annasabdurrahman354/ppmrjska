<?php

namespace App\Models;

use DateTimeInterface;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    use HasFactory;

    public $table = 'testimoni';

    protected $fillable = [
        'nama_alumni',
        'tahun_lulus',
        'isi',
        'highlight'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(config('project.datetime_format'));
    }

    public static function getForm()
    {
        return [
            TextInput::make('Nama Alumni')
                ->label('Nama Alumni')
                ->required(),
            TextInput::make('tahun_lulus')
                ->label('Tahun Lulus')
                ->numeric()
                ->required(),
            Textarea::make('isi')
                ->label('Isi Testimoni')
                ->required(),
            ToggleButtons::make('highlight')
                ->label('Highlight')
                ->helperText('Pilih "Ya" jika ingin menampilkan di landing page.')
                ->options([
                    true => 'Ya',
                    false => 'Tidak'
                ])
                ->default(false)
                ->inline()
                ->grouped(),
        ];
    }
}
