<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GolonganDarah : string implements HasLabel, HasColor {
    case A = 'a';
    case B = 'b';
    case AB = 'ab';
    case O = 'o';
    case BELUM_DIKETAHUI = 'belum diketahui';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::A => 'A',
            self::B => 'B',
            self::AB => 'AB',
            self::O => 'O',
            self::BELUM_DIKETAHUI => 'Belum Diketahui',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::A => 'primary',
            self::B => 'secondary',
            self::AB => 'warning',
            self::O => 'info',
            self::BELUM_DIKETAHUI => 'danger',
        };
    }
}
