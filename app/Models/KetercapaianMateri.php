<?php

namespace App\Models;

use App\Enums\StatusKehadiran;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
        'ketercapaian_materi'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materi(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'materi_type', 'materi_id');
    }

    public static function getFormKetercapaianMateriAction()
    {
        return [
            Hidden::make('user_id'),
            Hidden::make('materi_type'),
            Hidden::make('materi_id'),
            TextInput::make('ketercapaian_materi')
                ->label('Persen Ketercapaian')
                ->numeric()
                ->maxValue(100)
                ->minValue(0)
                ->step(1)
                ->required()
        ];
    }
}
