<?php

namespace App\Filament\Imports;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\HubunganWali;
use App\Enums\JenisKelamin;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusPondok;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use HighSolutions\LaravelSearchy\Facades\Searchy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SantriImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->requiredMapping()
                ->rules(['required'])
                ->examples(['ISI DENGAN URUTAN ROW SAJA']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->guess(['Nama', 'Nama Lengkap'])
                ->rules(['required', 'max:255'])
                ->castStateUsing(function ($state): ?string {
                    return ucwords($state);
                }),
            ImportColumn::make('nama_panggilan')
                ->requiredMapping()
                ->guess(['Nama Panggilan'])
                ->rules(['required', 'max:64'])
                ->castStateUsing(function ($state): ?string {
                    return ucwords($state);
                }),
            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(JenisKelamin::cases(), 'value')),
            ImportColumn::make('nis')
                ->requiredMapping()
                ->guess(['Nomor Induk Santri'])
                ->rules(['required', 'unique:users,nis', 'max:9']),
            ImportColumn::make('nomor_telepon')
                ->requiredMapping()
                ->rules(['required', 'unique:users,nomor_telepon', 'max:16']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['email', 'max:255','unique:users,email']),
            ImportColumn::make('angkatan_pondok')
                ->requiredMapping()
                ->rules(['required', 'integer'])
                ->examples([2021,2022,2023]),
            ImportColumn::make('status_pondok')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusPondok::cases(), 'value')),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['required'])
                ->examples(['ISI DENGAN NIK'])
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = User::create([
            'id' => Str::ulid(),
            'nama' => $this->data['nama'],
            'nama_panggilan' => $this->data['nama_panggilan'],
            'jenis_kelamin' => $this->data['jenis_kelamin'],
            'nis' => $this->data['nis'],
            'nomor_telepon' => $this->data['nomor_telepon'],
            'email' => $this->data['email'],
            'angkatan_pondok' => $this->data['angkatan_pondok'],
            'status_pondok' => $this->data['status_pondok'],
            'password' => Hash::make($this->data['password']),
        ]);
        info(json_encode($user));
        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Proses import data santri telah selesai dan ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' data telah ter-import.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal ter-import.';
        }

        return $body;
    }
}
