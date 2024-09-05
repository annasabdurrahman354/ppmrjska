<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AngkatanPondok extends Model
{
    use HasFactory;

    protected $table = 'angkatan_pondok';

    protected $fillable = [
        'angkatan_pondok',
        'kelas',
        'tanggal_masuk_takmili',
    ];

    protected $casts = [
        'angkatan_pondok' => 'integer',
        'tanggal_masuk_takmili' => 'date'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'angkatan_pondok', 'angkatan_pondok');
    }

    public static function getForm()
    {
        return [
            TextInput::make('angkatan_pondok')
                ->label('Angkatan Pondok')
                ->numeric()
                ->minValue(2011)
                ->unique(ignoreRecord: true)
                ->required(),
            ToggleButtons::make('is_takmili')
                ->label('Masuk kelas Takmili?')
                ->boolean('Ya',  'Tidak')
                ->grouped()
                ->inline()
                ->default(false)
                ->required()
                ->afterStateUpdated(function ($state, Get $get, Set $set){
                    if ($state) {
                        $set('kelas', 'Takmili');
                    }
                    else {
                        $set('kelas', (string) $get('angkatan_pondok'));
                    }
                })
                ->live(),
            Hidden::make('kelas'),
            DatePicker::make('tanggal_masuk_takmili')
                ->label('Tanggal Masuk Takmili')
                ->visible(fn (Get $get) => $get('is_takmili') == true)
                ->required(fn (Get $get) => $get('is_takmili') == true)
        ];
    }
}
