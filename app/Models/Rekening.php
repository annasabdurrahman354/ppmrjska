<?php

namespace App\Models;

use App\Enums\KepemilikanRekening;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rekening extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'rekening';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nomor_rekening',
        'nama_bank',
        'nama_pemilik_rekening',
        'kepemilikan_rekening',
    ];

    protected $casts = [
        'kepemilikan_rekening' => KepemilikanRekening::class
    ];

    public function administrasi(): HasMany
    {
        return $this->hasMany(Administrasi::class);
    }

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rekening '.(string) $this->nama_pemilik_rekening.' ('.$this->nomor_rekening.')',
        );
    }

    public static function getForm()
    {
        return [
            TextInput::make('nomor_rekening')
                ->label('Nomor Rekening')
                ->numeric()
                ->unique(ignoreRecord: true)
                ->required(),
            TextInput::make('nama_pemilik_rekening')
                ->label('Nama Pemilik Rekening')
                ->required(),
            TextInput::make('nama_bank')
                ->label('Nama Bank')
                ->required(),
            Select::make('kepemilikan_rekening')
                ->label('Kepemilikan Rekening')
                ->options(KepemilikanRekening::class)
                ->required()
        ];
    }

    protected static function booted(): void
    {
        parent::boot();
        static::softDeleted(function($rekening) {
            $rekening->administrasi()->each(function($administrasi) {
                $administrasi->rekening()->dissociate();
                $administrasi->save();
            });
        });
    }
}
