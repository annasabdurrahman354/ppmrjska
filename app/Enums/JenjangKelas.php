<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenjangKelas : string implements HasLabel, HasColor {
    case PEGON_BACAAN = 'pegon_bacaan';
    case LAMBATAN = 'lambatan';
    case CEPATAN = 'cepatan';
    case SARINGAN = 'saringan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PEGON_BACAAN => 'Pegon Bacaan',
            self::LAMBATAN => 'Lambatan',
            self::CEPATAN => 'Cepatan',
            self::SARINGAN => 'Saringan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PEGON_BACAAN => 'warning',
            self::LAMBATAN => 'info',
            self::CEPATAN => 'primary',
            self::SARINGAN => 'success',
        };
    }
}
