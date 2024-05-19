<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisTagihanAdministrasi : string implements HasLabel, HasColor {
    case BULANAN = 'bulanan';
    case SEMESTERAN = 'semesteran';
    case TAHUNAN = 'tahunan';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BULANAN => 'Bulanan',
            self::SEMESTERAN => 'Semesteran',
            self::TAHUNAN => 'Tahunan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::BULANAN => 'primary',
            self::SEMESTERAN => 'secondary',
            self::TAHUNAN => 'success',
        };
    }
}
