<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum JenisAdministrasi : string implements HasLabel, HasColor {
    case ASRAMA = 'asrama';
    case WAJIB = 'wajib';
    case KESANGGUPAN = 'kesanggupan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ASRAMA => 'Asrama',
            self::WAJIB => 'Wajib',
            self::KESANGGUPAN => 'Kesanggupan',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ASRAMA => 'success',
            self::WAJIB => 'primary',
            self::KESANGGUPAN => 'secondary',
        };
    }
}
