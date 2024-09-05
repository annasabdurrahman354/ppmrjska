<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_website.brand_name', 'PPM Roudlotul Jannah Surakarta');
        $this->migrator->add('admin_website.brand_logo', 'sites/logo.png');
        $this->migrator->add('admin_website.brand_logoHeight', '3rem');
        $this->migrator->add('admin_website.site_active', true);
        $this->migrator->add('admin_website.site_favicon', 'sites/favicon.ico');
        $this->migrator->add('admin_website.site_theme', [
            "primary" => "rgb(20, 196, 53)",
            "secondary" => "rgb(230, 164, 82)",
            "gray" => "rgb(107, 114, 128)",
            "success" => "rgb(12, 195, 178)",
            "danger" => "rgb(199, 29, 81)",
            "info" => "rgb(12, 89, 194)",
            "warning" => "rgb(255, 186, 93)",
        ]);
    }
};
