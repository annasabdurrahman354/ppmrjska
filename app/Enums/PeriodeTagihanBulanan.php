<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PeriodeTagihanBulanan : string implements HasLabel, HasColor {
    case JANUARI = 'januari';
    case FEBRUARI = 'februari';
    case MARET = 'maret';
    case APRIL = 'april';
    case MEI = 'mei';
    case JUNI = 'juni';
    case JULI = 'juli';
    case AGUSTUS = 'agustus';
    case SEPTEMBER = 'september';
    case OKTOBER = 'oktober';
    case NOVEMBER = 'november';
    case DESEMBER = 'desember';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::JANUARI => 'Januari',
            self::FEBRUARI => 'Februari',
            self::MARET => 'Maret',
            self::APRIL => 'April',
            self::MEI => 'Mei',
            self::JUNI => 'Juni',
            self::JULI => 'Juli',
            self::AGUSTUS => 'Agustus',
            self::SEPTEMBER => 'September',
            self::OKTOBER => 'Oktober',
            self::NOVEMBER => 'November',
            self::DESEMBER => 'Desember',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::JANUARI => 'primary',
            self::FEBRUARI => 'primary',
            self::MARET => 'primary',
            self::APRIL => 'primary',
            self::MEI => 'primary',
            self::JUNI => 'primary',
            self::JULI => 'primary',
            self::AGUSTUS => 'primary',
            self::SEPTEMBER => 'primary',
            self::OKTOBER => 'primary',
            self::NOVEMBER => 'primary',
            self::DESEMBER => 'primary',
        };
    }
}
