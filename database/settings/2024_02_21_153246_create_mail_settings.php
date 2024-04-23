<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_mail.from_address', 'no-reply@ppmrjska.web.id');
        $this->migrator->add('admin_mail.from_name', 'PPM Rodulotul Jannah Surakarta');
        $this->migrator->add('admin_mail.driver', 'smtp');
        $this->migrator->add('admin_mail.host', null);
        $this->migrator->add('admin_mail.port', 587);
        $this->migrator->add('admin_mail.encryption', 'tls');
        $this->migrator->addEncrypted('admin_mail.username', null);
        $this->migrator->addEncrypted('admin_mail.password', null);
        $this->migrator->add('admin_mail.timeout', null);
        $this->migrator->add('admin_mail.local_domain', null);
    }
};
