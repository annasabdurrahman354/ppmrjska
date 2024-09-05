<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_kurikulum.tahun_ajaran', date("Y").'/'.(string)(date('Y')+1));
        $this->migrator->add('admin_kurikulum.semester', 'genap');
    }
};
