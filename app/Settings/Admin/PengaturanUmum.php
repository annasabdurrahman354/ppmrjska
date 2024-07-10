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
    public string $site_alamat;

    public string $site_email;
    public array $site_narahubung;
    public array $site_penerima_tamu;
    public array $site_theme;

    public static function group(): string
    {
        return 'admin_umum';
    }
}
