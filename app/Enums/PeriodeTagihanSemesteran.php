<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PeriodeTagihanSemesteran : string implements HasLabel, HasColor {
    case GANJIL = 'semester ganjil';
    case GENAP = 'semester genap';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::GANJIL => 'Semester Ganjil',
            self::GENAP => 'Semester Genap',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::GANJIL, self::GENAP => 'secondary',
        };
    }
}
