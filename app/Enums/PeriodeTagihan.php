<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Stringy\Stringy;

enum PeriodeTagihan : string implements HasLabel, HasColor {
    case JANUARI = 'januari';
    case FEBRUARY = 'februari';
    case MARET = 'maret';
    case APRIL = 'april';
    case MEI = 'mei';
    case JUNI = 'juni';
    case JULI = 'juli';
    case AGUSTUS = 'agustus';
    case SEPTEMBER = 'september';
    case OKTOBER = 'oktober';
    case NOVEMBER = 'november';
    case DECEMBER = 'desember';
    case GANJIL = 'semester ganjil';
    case GENAP = 'semester genap';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::JANUARI => 'Januari',
            self::FEBRUARY => 'Februari',
            self::MARET => 'Maret',
            self::APRIL => 'April',
            self::MEI => 'Mei',
            self::JUNI => 'Juni',
            self::JULI => 'Juli',
            self::AGUSTUS => 'Agustus',
            self::SEPTEMBER => 'September',
            self::OKTOBER => 'Oktober',
            self::NOVEMBER => 'November',
            self::DECEMBER => 'Desember',
            self::GANJIL => 'Semester Ganjil',
            self::GENAP => 'Semester Genap',
            default => Stringy::create($this->value)->toTitleCase()
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::JANUARI => 'primary',
            self::FEBRUARY => 'primary',
            self::MARET => 'primary',
            self::APRIL => 'primary',
            self::MEI => 'primary',
            self::JUNI => 'primary',
            self::JULI => 'primary',
            self::AGUSTUS => 'primary',
            self::SEPTEMBER => 'primary',
            self::OKTOBER => 'primary',
            self::NOVEMBER => 'primary',
            self::DECEMBER => 'primary',
            self::GANJIL => 'secondary',
            self::GENAP => 'secondary',
            default => 'info'
        };
    }
}
