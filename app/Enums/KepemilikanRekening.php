<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Stringy\Stringy;

enum KepemilikanRekening : string implements HasLabel, HasColor {
    case BENDAHARA_DMC_PASUS = 'bendahara dmc pasus';
    case BENDAHARA_PPM = 'bendahara ppm';
    case PANITIA = 'panitia';
    case PERORANGAN = 'peorangan';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BENDAHARA_DMC_PASUS => 'Bendahara DMC-Pasus',
            self::BENDAHARA_PPM => 'Bendahara PPM',
            self::PANITIA => 'Panitia Kegiatan',
            self::PERORANGAN => 'Perorangan',
            default => Stringy::create($this->value)->toTitleCase()
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::BENDAHARA_DMC_PASUS => 'primary',
            self::BENDAHARA_PPM => 'secondary',
            self::PANITIA => 'warning',
            self::PERORANGAN => 'danger',
            default => 'success'
        };
    }
}
