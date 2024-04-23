<?php

namespace App\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class PengaturanUmum extends Settings
{
    public string $brand_name;
    public string $brand_logo;
    public string $brand_logoHeight;
    public bool $site_active;
    public string $site_favicon;
    public array $site_theme;

    public static function group(): string
    {
        return 'admin_umum';
    }
}
