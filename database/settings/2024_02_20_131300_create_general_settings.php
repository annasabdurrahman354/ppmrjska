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
        $this->migrator->add('admin_umum.site_alamat', 'Jl. Porong No.17, Pucangsawit, Jebres, Kota Surakarta, Jawa Tengah');
        $this->migrator->add('admin_umum.site_email', 'ppmrjska@gmail.com');
        $this->migrator->add('admin_umum.site_narahubung', [
            [
                'nama' => 'Bp. H. Suharno S.Kar.',
                'nomor_telepon' => '+6287832382'
            ],
            [
                'nama' => 'Ust. Eko Prsetyo S.Pd.',
                'nomor_telepon' => '+62878332382'
            ],
        ]);
        $this->migrator->add('admin_umum.site_penerima_tamu', [
            [
                'nama' => 'Muhammad Luthfi',
                'nomor_telepon' => '+6244832382'
            ],
            [
                'nama' => 'Eka Ningsih',
                'nomor_telepon' => '+6284423233'
            ],
        ]);
        $this->migrator->add('admin_umum.site_theme', [
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
