<?php

namespace App\Models;

use DateTimeInterface;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KotakSaran extends Model
{
    use HasFactory;

    public $table = 'kotak_saran';

    protected $fillable = [
        'pengirim',
        'nomor_telepon',
        'isi',
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
            TextInput::make('pengirim')
                ->label('Pengirim'),
            TextInput::make('nomor_telepon')
                ->label('Nomor Telepon')
                ->tel(),
            Textarea::make('isi')
                ->label('Isi Saran/Kritik')
                ->required()
        ];
    }
}
