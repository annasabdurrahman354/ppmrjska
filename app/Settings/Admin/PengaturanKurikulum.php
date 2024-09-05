<?php

namespace App\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class PengaturanKurikulum extends Settings
{
    public string $tahun_ajaran;
    public string $semester;

    public static function group(): string
    {
        return 'admin_kurikulum';
    }
}
