<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_umum.brand_name', 'PPM Roudlotul Jannah Surakarta');
        $this->migrator->add('admin_umum.brand_logo', 'sites/logo.png');
        $this->migrator->add('admin_umum.brand_logoHeight', '3rem');
        $this->migrator->add('admin_umum.site_active', true);
        $this->migrator->add('admin_umum.site_favicon', 'sites/favicon.ico');
        $this->migrator->add('admin_umum.site_theme', [
            "primary" => "rgb(19, 83, 196)",
            "secondary" => "rgb(255, 137, 84)",
            "gray" => "rgb(107, 114, 128)",
            "success" => "rgb(12, 195, 178)",
            "danger" => "rgb(199, 29, 81)",
            "info" => "rgb(113, 12, 195)",
            "warning" => "rgb(255, 186, 93)",
        ]);
    }
};
