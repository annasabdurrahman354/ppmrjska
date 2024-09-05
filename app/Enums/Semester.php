<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Semester : string implements HasLabel, HasColor {
    case GANJIL = 'ganjil';
    case GENAP = 'genap';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::GANJIL => 'Ganjil',
            self::GENAP => 'Genap',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::GANJIL =>'primary',
            self::GENAP => 'secondary',
        };
    }
}
