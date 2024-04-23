<?php
namespace App\Enums;

use App\Models\MateriHafalan;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisMateriMunaqosah : string implements HasLabel, HasColor {
    case AL_QURAN = MateriSurat::class;
    case HIMPUNAN = MateriHimpunan::class;
    case HAFALAN = MateriHafalan::class;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AL_QURAN => 'Al-Quran',
            self::HIMPUNAN => 'Himpunan',
            self::HAFALAN => 'Hafalan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::AL_QURAN => 'primary',
            self::HIMPUNAN => 'secondary',
            self::HAFALAN => 'warning',
        };
    }
}
