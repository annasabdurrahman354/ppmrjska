<?php

namespace App\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class PengaturanInformasiPondok extends Settings
{
    public string $alamat;
    public string $email;
    public array $narahubung;
    public array $penerima_tamu;

    public static function group(): string
    {
        return 'admin_informasi_pondok';
    }
}
