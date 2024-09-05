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

class SantriImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->guess(['Nama', 'Nama Lengkap'])
                ->rules(['required', 'max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nama_panggilan')
                ->requiredMapping()
                ->guess(['Nama Panggilan'])
                ->rules(['required', 'max:64'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(JenisKelamin::cases(), 'value')),
            ImportColumn::make('nis')
                ->requiredMapping()
                ->guess(['Nomor Induk Santri'])
                ->rules(['required', 'max:9']),
            ImportColumn::make('nomor_telepon')
                ->requiredMapping()
                ->rules(['required', 'max:16']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255','unique:users,email']),
            ImportColumn::make('angkatan_pondok')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->examples([2021,2022,2023]),
            ImportColumn::make('status_pondok')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusPondok::cases(), 'value')),
            ImportColumn::make('tanggal_lulus_pondok')
                ->rules(['date']),
            ImportColumn::make('tanggal_keluar_pondok')
                ->rules(['date']),
            ImportColumn::make('alasan_keluar_pondok')
                ->rules(['max:255']),
            ImportColumn::make('tahun_pendaftaran')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->examples([2021, 2022, 2023]),
            ImportColumn::make('nik')
                ->requiredMapping()
                ->guess(['Nomor Induk Kependudukan'])
                ->rules(['required', 'max:16','unique:biodata_santri,nik']),
            ImportColumn::make('tempat_lahir_id')
                ->requiredMapping()
                ->guess(['Tempat Lahir', 'Kota Lahir']),
            ImportColumn::make('tanggal_lahir')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('kewarganegaraan')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(Kewarganegaraan::cases(), 'value')),
            ImportColumn::make('golongan_darah')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(GolonganDarah::cases(), 'value')),
            ImportColumn::make('ukuran_baju')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(UkuranBaju::cases(), 'value')),
            ImportColumn::make('pendidikan_terakhir')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(PendidikanTerakhir::cases(), 'value')),
            ImportColumn::make('program_studi')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['S1 Kedokteran', 'D3 Teknik Informatika', 'D4 Teknik Sipil','STRATA SPASI NAMA PRODI'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('universitas')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['Universitas Sebelas Maret', 'Universitas Muhammadiyah Surakarta', 'JANGAN DISIKNGKAT'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('angkatan_kuliah')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->examples([2021,2022,2023]),
            ImportColumn::make('status_kuliah')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusKuliah::cases(), 'value')),
            ImportColumn::make('tanggal_lulus_kuliah')
                ->rules(['date']),
            ImportColumn::make('alamat')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('provinsi_id')
                ->guess(['Provinsi']),
            ImportColumn::make('kota_id')
                ->guess(['Kota']),
            ImportColumn::make('kecamatan_id')
                ->guess(['Kecamatan']),
            ImportColumn::make('kelurahan_id')
                ->guess(['Kelurahan']),
            ImportColumn::make('asal_kelompok')
                ->requiredMapping()
                ->rules(['required', 'max:96'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('asal_desa')
                ->requiredMapping()
                ->rules(['required', 'max:96'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('asal_daerah')
                ->requiredMapping()
                ->rules(['required', 'max:96'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('mulai_mengaji')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(MulaiMengaji::cases(), 'value')),
            ImportColumn::make('bahasa_makna')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(BahasaMakna::cases(), 'value')),
            ImportColumn::make('status_pernikahan')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusPernikahan::cases(), 'value')),
            ImportColumn::make('status_tinggal')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusTinggal::cases(), 'value')),
            ImportColumn::make('status_orangtua')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(array_column(StatusOrangTua::cases(), 'value')),
            ImportColumn::make('jumlah_saudara')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('anak_nomor')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('nama_ayah')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nomor_telepon_ayah')
                ->rules(['max:16']),
            ImportColumn::make('pekerjaan_ayah')
                ->rules(['max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('dapukan_ayah')
                ->rules(['max:255'])
                ->examples(['Rukyah', 'Kiai Kelompok', 'Bendahara Kelompok', 'Mubaligh Daerah'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nama_ibu')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nomor_telepon_ibu')
                ->rules(['max:16']),
            ImportColumn::make('pekerjaan_ibu')
                ->rules(['max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('dapukan_ibu')
                ->rules(['max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nama_wali')
                ->rules(['max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('nomor_telepon_wali')
                ->rules(['max:16']),
            ImportColumn::make('pekerjaan_wali')
                ->rules(['max:255'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('dapukan_wali')
                ->rules(['max:255'])
                ->examples(['Rukyah', 'Kiai Kelompok', 'Bendahara Kelompok', 'Mubaligh Daerah'])
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) {
                        return null;
                    }
                    return ucwords($state);
                }),
            ImportColumn::make('hubungan_wali')
                ->rules(['max:255'])
                ->examples(array_column(HubunganWali::cases(), 'value')),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = new User();
        $this->data['password'] = Hash::make($this->data['nik']);
        $this->data['tempat_lahir_id'] = Kota::hydrate(Searchy::kota('nama')->query($this->data['tempat_lahir_id'])->get())->first()->id ?? null;
        $this->data['provinsi_id'] = Provinsi::hydrate(Searchy::provinsi('nama')->query($this->data['provinsi_id'])->get())->first()->id ?? null;
        $this->data['kota_id'] = Kota::hydrate(Searchy::kota('nama')->query($this->data['kota_id'])->get())->first()->id ?? null;
        $this->data['kecamatan_id'] = Kecamatan::hydrate(Searchy::kecamatan('nama')->query($this->data['kecamatan_id'])->get())->first()->id ?? null;
        $this->data['kelurahan_id'] = Kelurahan::hydrate(Searchy::kelurahan('nama')->query($this->data['kelurahan_id'])->get())->first()->id ?? null;
        $user->fill($this->data)->save();
        $user->biodataSantri()->create($this->data);
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
