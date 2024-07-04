<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusBlog: string implements HasColor, HasIcon, HasLabel
{
    case TERTUNDA = 'tertunda';

    case TERJADWAL = 'terjadwal';
    case TERBIT = 'terbit';

    public function getColor(): string
    {
        return match ($this) {
            self::TERTUNDA => 'info',
            self::TERJADWAL => 'warning',
            self::TERBIT => 'success'
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::TERTUNDA => 'Tertunda',
            self::TERJADWAL => 'Terjadwal',
            self::TERBIT => 'Terbit'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TERTUNDA => 'heroicon-o-clock',
            self::TERJADWAL => 'heroicon-o-calendar-days',
            self::TERBIT => 'heroicon-o-check-badge',
        };
    }
}
