<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role : string implements HasLabel {
    case SUPERADMIN = 'superadmin';
    case DMC_PASUS_KEILMUAN = 'dmc pasus keilmuan';
    case TIM_KEILMUAN = 'tim keilmuan';
    case DMC_PASUS_KEDISIPLINAN = 'dmc pasus kedisiplinan';
    case DMC_PASUS_SEKRETARIS = 'dmc pasus sekretaris';
    case DMC_PASUS_KOORDINATOR = 'dmc pasus koordinator';
    case KETUA_KELAS = 'ketua kelas';
    case SANTRI = 'santri';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DMC_PASUS_KEILMUAN => 'DMC-Pasus Keilmuan',
            self::TIM_KEILMUAN => 'Tim Keilmuan',
            self::DMC_PASUS_KEDISIPLINAN => 'DMC-Pasus Kedisiplinan',
            self::DMC_PASUS_SEKRETARIS => 'DMC-Pasus Sekretaris',
            self::DMC_PASUS_KOORDINATOR => 'DMC-Pasus Koordinator',
            self::KETUA_KELAS => 'Ketua Kelas',
            self::SANTRI => 'Santri',
        };
    }
}
