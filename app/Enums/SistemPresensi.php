<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SistemPresensi : string implements HasLabel, HasColor {
    case KELAS = 'kelas';
    case KELOMPOK = 'kelompok';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::KELAS => 'Kelas',
            self::KELOMPOK => 'Kelompok',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::KELAS =>'primary',
            self::KELOMPOK => 'secondary',
        };
    }
}
