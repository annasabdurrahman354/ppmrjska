<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusPembayaran : string implements HasLabel, HasColor {
    case CICILAN = 'cicilan';
    case LUNAS = 'lunas';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CICILAN => 'Cicilan',
            self::LUNAS => 'Lunas',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::CICILAN => 'warning',
            self::LUNAS => 'success',
        };
    }
}
