<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusPembayaran : string implements HasLabel, HasColor {
    case CICILAN = 'cicilan';
    case PELUNASAN = 'pelunasan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CICILAN => 'Cicilan',
            self::PELUNASAN => 'Pelunasan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::CICILAN => 'info',
            self::PELUNASAN => 'success',
        };
    }
}
