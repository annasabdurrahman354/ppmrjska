<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SumberMedia: string implements HasColor, HasLabel
{
    case PORTAL_BERITA = 'portal berita';
    case HALAMAN_WEB = 'halaman web';
    case INSTAGRAM = 'instagram';
    case TIKTOK = 'tiktok';
    case YOUTUBE = 'youtube';

    public function getColor(): string
    {
        return match ($this) {
            self::PORTAL_BERITA => 'info',
            self::HALAMAN_WEB => 'info',
            self::INSTAGRAM => 'success',
            self::TIKTOK => 'success',
            self::YOUTUBE => 'success'
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::PORTAL_BERITA => 'Portal Berita',
            self::HALAMAN_WEB => 'Halaman Web',
            self::INSTAGRAM => 'Instagram',
            self::TIKTOK => 'TikTok',
            self::YOUTUBE => 'Youtube'
        };
    }
}
