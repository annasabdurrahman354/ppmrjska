<?php

namespace App\Models;

use App\Enums\KepemilikanRekening;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rekening '.(string) $this->nama_pemilik_rekening.' ('.$this->nomor_rekening.')',
        );
    }
}
