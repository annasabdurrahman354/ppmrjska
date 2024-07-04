<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PembebananBiayaAsrama : string implements HasLabel, HasColor {
    case PERKAMAR = 'per kamar';
    case PERORANG = 'per orang';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PERKAMAR => 'Per Kamar',
            self::PERORANG => 'Per Orang',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PERKAMAR => 'secondary',
            self::PERORANG => 'primary',
        };
    }
}
