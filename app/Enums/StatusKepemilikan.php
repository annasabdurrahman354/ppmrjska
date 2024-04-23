<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusKepemilikan : string implements HasLabel, HasColor {
    case PPM = 'ppm';
    case KELOMPOK = 'kelompok';
    case PERORANGAN = 'perorangan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PPM => 'PPM',
            self::KELOMPOK => 'Kelompok',
            self::PERORANGAN => 'Perorangan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PPM => 'success',
            self::KELOMPOK => 'primary',
            self::PERORANGAN => 'secondary',
        };
    }
}
