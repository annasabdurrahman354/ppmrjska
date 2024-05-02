<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusTagihan : string implements HasLabel, HasColor {
    case BELUM_BAYAR = 'belum bayar';
    case BELUM_LUNAS = 'belum lunas';
    case LUNAS = 'lunas';
    case KELEBIHAN = 'kelebihan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BELUM_BAYAR => 'Belum Bayar',
            self::BELUM_LUNAS => 'Belum Lunas',
            self::LUNAS => 'Lunas',
            self::KELEBIHAN => 'Kelebihan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::BELUM_BAYAR => 'info',
            self::BELUM_LUNAS => 'warning',
            self::LUNAS => 'success',
            self::KELEBIHAN => 'danger',
        };
    }
}
