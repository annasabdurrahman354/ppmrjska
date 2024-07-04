<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisTagihan : string implements HasLabel, HasColor {
    case BULANAN = 'bulanan';
    case SEMESTERAN = 'semesteran';
    case TAHUNAN = 'tahunan';
    case SEKALI = 'sekali';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BULANAN => 'Bulanan',
            self::SEMESTERAN => 'Semesteran',
            self::TAHUNAN => 'Tahunan',
            self::SEKALI => 'Sekali',
        };
    }

    public static function getDeskripsi($value): ?string
    {
        return match ($value) {
            self::BULANAN => ' per bulan',
            self::SEMESTERAN => ' per semester',
            self::TAHUNAN => ' per tahun',
            default => '',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::BULANAN => 'primary',
            self::SEMESTERAN => 'secondary',
            self::TAHUNAN => 'success',
            self::SEKALI => 'warning',
        };
    }
}
