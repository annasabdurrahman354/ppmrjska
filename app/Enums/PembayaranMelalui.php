<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PembayaranMelalui : string implements HasLabel, HasColor {
    case BENDAHARA_DMC_PASUS = 'bendahara dmc pasus';
    case BENDAHARA_PPM = 'bendahara ppm';
    case TRANSFER_BANK = 'transfer bank';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BENDAHARA_DMC_PASUS => 'Bendahara DMC-Pasus',
            self::BENDAHARA_PPM => 'Bendahara PPM',
            self::TRANSFER_BANK => 'Transfer Bank',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::BENDAHARA_DMC_PASUS => 'primary',
            self::BENDAHARA_PPM => 'secondary',
            self::TRANSFER_BANK => 'info',
        };
    }
}
