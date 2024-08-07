<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kota';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'provinsi_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function semuaKecamatan(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }

    public static function getForm()
    {
        return [
            Select::make('provinsi_id')
                ->relationship('provinsi', 'nama')
                ->searchable()
                ->required(),
            TextInput::make('nama')
                ->required()
                ->maxLength(48),
        ];
    }
}
