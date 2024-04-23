<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Kewarganegaraan : string implements HasLabel, HasColor {
    case WNI = 'wni';
    case WNA = 'wna';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WNI => 'WNI (Warga Negara Indonesia)',
            self::WNA => 'WNA (Warga Negara Asing)',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::WNI => 'success',
            self::WNA => 'danger',
        };
    }
}
