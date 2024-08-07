<?php

namespace App\Models;

use App\Enums\StatusKehadiran;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class KetercapaianMateri extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'ketercapaian_materi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'materi_type',
        'materi_id',
        'halaman_tercapai'
    ];

    protected $casts = [
        'halaman_tercapai' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materi(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_type', 'materi_id');
    }

    public function jumlahHalamanTercapai(): Attribute
    {
        return Attribute::make(
            get: fn () => count($this->halaman_tercapai),
        );
    }
}
