<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StatusPenerimaan : string implements HasLabel, HasColor {
    case DITERIMA = 'diterima';
    case DITOLAK = 'ditolak';
    case WAITING_LIST = 'waiting list';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DITERIMA => 'Diterima',
            self::DITOLAK => 'Ditolak',
            self::WAITING_LIST => 'Waiting List',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::DITERIMA => 'success',
            self::DITOLAK => 'danger',
            self::WAITING_LIST => 'info',
        };
    }
}
