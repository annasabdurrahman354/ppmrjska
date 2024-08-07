<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusPerwalian: string implements HasLabel, HasColor{
    case AYAH = 'ayah';
    case IBU = 'ibu';
    case ORANG_LAIN = 'orang lain';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AYAH => 'Ayah',
            self::IBU => 'Ibu',
            self::ORANG_LAIN => 'Orang Lain',

        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::AYAH => 'primary',
            self::IBU => 'primary',
            self::ORANG_LAIN => 'secondary',
        };
    }
}
