<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusOrangTua : string implements HasLabel, HasColor {
    case LENGKAP = 'lengkap';
    case YATIM = 'yatim';
    case PIATU = 'piatu';
    case YATIM_PIATU = 'yatim_piatu';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LENGKAP => 'Lengkap',
            self::YATIM => 'Yatim',
            self::PIATU => 'Piatu',
            self::YATIM_PIATU => 'Yatim Piatu',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::LENGKAP => 'primary',
            self::YATIM => 'info',
            self::PIATU => 'info',
            self::YATIM_PIATU => 'warning',
        };
    }
}
