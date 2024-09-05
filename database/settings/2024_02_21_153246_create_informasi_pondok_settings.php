<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_informasi_pondok.alamat', 'Jl. Porong No.17, Pucangsawit, Jebres, Kota Surakarta, Jawa Tengah');
        $this->migrator->add('admin_informasi_pondok.email', 'ppmrjska@gmail.com');
        $this->migrator->add('admin_informasi_pondok.narahubung', [
            [
                'nama' => 'Bp. H. Suharno S.Kar.',
                'nomor_telepon' => '+6287832382'
            ],
            [
                'nama' => 'Ust. Eko Prsetyo S.Pd.',
                'nomor_telepon' => '+62878332382'
            ],
        ]);
        $this->migrator->add('admin_informasi_pondok.penerima_tamu', [
            [
                'nama' => 'Muhammad Luthfi',
                'nomor_telepon' => '+6244832382'
            ],
            [
                'nama' => 'Eka Ningsih',
                'nomor_telepon' => '+6284423233'
            ],
        ]);
    }
};
