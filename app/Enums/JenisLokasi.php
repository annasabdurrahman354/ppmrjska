<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisLokasi : string implements HasLabel, HasColor {
    case AULA = 'aula';
    case GERBANG = 'gerbang';
    case MASJID = 'masjid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AULA => 'Aula',
            self::GERBANG => 'Gerbang',
            self::MASJID => 'Masjid',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::AULA => 'primary',
            self::GERBANG => 'secondary',
            self::MASJID => 'success',
        };
    }
}
