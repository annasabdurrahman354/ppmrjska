<?php

namespace App\Settings\Admin;

use Spatie\LaravelSettings\Settings;

class PengaturanMail extends Settings
{
    public string $from_address;
    public string $from_name;
    public ?string $driver;
    public ?string $host;
    public int $port;
    public string $encryption;
    public ?string $username;
    public ?string $password;
    public ?int $timeout;
    public ?string $local_domain;

    public static function group(): string
    {
        return 'admin_mail';
    }

    public static function encrypted(): array
    {
        return [
            'username',
            'password',
        ];
    }
}
