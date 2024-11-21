<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Role : string implements HasLabel {
    case DMC_PASUS_KEILMUAN = 'DMC Pasus Keilmuan';
    case TIM_KEILMUAN = 'Tim Keilmuan';
    case DMC_PASUS_KEDISIPLINAN = 'DMC Pasus Kedisiplinan';
    case DMC_PASUS_SEKRETARIS = 'DMC Pasus Sekretaris';
    case DMC_PASUS_KOORDINATOR = 'DMC Pasus Koordinator';
    case KETUA_KELAS = 'Ketua Kelas';
    case SANTRI = 'Santri';

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
