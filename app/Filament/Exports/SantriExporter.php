<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SantriExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nama')->label('Nama Lengkap'),
            ExportColumn::make('nama_panggilan')->label('Nama Panggilan'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin')
                ->state(function (User $record) {
                    return $record->jenis_kelamin ? $record->jenis_kelamin->getLabel() : '';
                }),
            ExportColumn::make('nis')->label('Nomor Induk Santri (NIS)'),
            ExportColumn::make('nomor_telepon')->label('Nomor Telepon'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('angkatan_pondok')->label('Angkatan Pondok'),
            ExportColumn::make('angkatanPondok.kelas')->label('Angkatan Pondok'),
            ExportColumn::make('status_pondok')->label('Status Pondok')
                ->state(function (User $record) {
                    return $record->status_pondok ? $record->status_pondok->getLabel() : '';
                }),
            ExportColumn::make('tanggal_lulus_pondok')->label('Tanggal Lulus Pondok'),
            ExportColumn::make('tanggal_keluar_pondok')->label('Tanggal Keluar Pondok'),
            ExportColumn::make('alasan_keluar_pondok')->label('Alasan Keluar Pondok'),
            ExportColumn::make('biodataSantri.tahun_pendaftaran')->label('Tahun Pendaftaran'),
            ExportColumn::make('biodataSantri.nik')->label('Nomor Induk Kependudukan (NIK)'),
            ExportColumn::make('biodataSantri.tempat_lahir.nama')->label('Tempat Lahir'),
            ExportColumn::make('biodataSantri.tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('biodataSantri.kewarganegaraan')->label('Kewarganegaraan')
                ->state(function (User $record) {
                    return $record->biodataSantri->kewarganegaraan ? $record->biodataSantri->kewarganegaraan->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.golongan_darah')->label('Golongan Darah')
                ->state(function (User $record) {
                    return $record->biodataSantri->golongan_darah ? $record->biodataSantri->golongan_darah->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.ukuran_baju')->label('Ukuran Baju')
                ->state(function (User $record) {
                    return $record->biodataSantri->ukuran_baju ? $record->biodataSantri->ukuran_baju->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.pendidikan_terakhir')->label('Pendidikan Terakhir')
                ->state(function (User $record) {
                    return $record->biodataSantri->pendidikan_terakhir ? $record->biodataSantri->pendidikan_terakhir->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.program_studi')->label('Program Studi'),
            ExportColumn::make('biodataSantri.universitas')->label('Universitas'),
            ExportColumn::make('biodataSantri.angkatan_kuliah')->label('Angkatan Kuliah'),
            ExportColumn::make('biodataSantri.status_kuliah')->label('Status Kuliah')
                ->state(function (User $record) {
                    return $record->biodataSantri->status_kuliah ? $record->biodataSantri->status_kuliah->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.tanggal_lulus_kuliah')->label('Tanggal Lulus Kuliah'),
            ExportColumn::make('biodataSantri.alamat')->label('Alamat'),
            ExportColumn::make('biodataSantri.provinsi.nama')->label('Provinsi'),
            ExportColumn::make('biodataSantri.kota.nama')->label('Kota'),
            ExportColumn::make('biodataSantri.kecamatan.nama')->label('Kecamatan'),
            ExportColumn::make('biodataSantri.kelurahan.nama')->label('Kelurahan'),
            ExportColumn::make('biodataSantri.asal_kelompok')->label('Asal Kelompok'),
            ExportColumn::make('biodataSantri.asal_desa')->label('Asal Desa'),
            ExportColumn::make('biodataSantri.asal_daerah')->label('Asal Daerah'),
            ExportColumn::make('biodataSantri.mulai_mengaji')->label('Mulai Mengaji')
                ->state(function (User $record) {
                    return $record->biodataSantri->mulai_mengaji ? $record->biodataSantri->mulai_mengaji->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.bahasa_makna')->label('Bahasa Makna')
                ->state(function (User $record) {
                    return $record->biodataSantri->bahasa_makna ? $record->biodataSantri->bahasa_makna->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.status_pernikahan')->label('Status Pernikahan')
                ->state(function (User $record) {
                    return $record->biodataSantri->status_pernikahan ? $record->biodataSantri->status_pernikahan->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.status_tinggal')->label('Status Tinggal')
                ->state(function (User $record) {
                    return $record->biodataSantri->status_tinggal ? $record->biodataSantri->status_tinggal->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.status_orangtua')->label('Status Orang Tua')
                ->state(function (User $record) {
                    return $record->biodataSantri->status_orangtua ? $record->biodataSantri->status_orangtua->getLabel() : '';
                }),
            ExportColumn::make('biodataSantri.jumlah_saudara')->label('Jumlah Saudara'),
            ExportColumn::make('biodataSantri.anak_nomor')->label('Anak Ke-'),
            ExportColumn::make('biodataSantri.nama_ayah')->label('Nama Ayah'),
            ExportColumn::make('biodataSantri.nomor_telepon_ayah')->label('Nomor Telepon Ayah'),
            ExportColumn::make('biodataSantri.pekerjaan_ayah')->label('Pekerjaan Ayah'),
            ExportColumn::make('biodataSantri.dapukan_ayah')->label('Dapukan Ayah'),
            ExportColumn::make('biodataSantri.nama_ibu')->label('Nama Ibu'),
            ExportColumn::make('biodataSantri.nomor_telepon_ibu')->label('Nomor Telepon Ibu'),
            ExportColumn::make('biodataSantri.pekerjaan_ibu')->label('Pekerjaan Ibu'),
            ExportColumn::make('biodataSantri.dapukan_ibu')->label('Dapukan Ibu'),
            ExportColumn::make('biodataSantri.nama_wali')->label('Nama Wali'),
            ExportColumn::make('biodataSantri.nomor_telepon_wali')->label('Nomor Telepon Wali'),
            ExportColumn::make('biodataSantri.pekerjaan_wali')->label('Pekerjaan Wali'),
            ExportColumn::make('biodataSantri.dapukan_wali')->label('Dapukan Wali'),
            ExportColumn::make('biodataSantri.hubungan_wali')->label('Hubungan dengan Wali')
                ->state(function (User $record) {
                    return $record->biodataSantri->hubungan_wali ? $record->biodataSantri->hubungan_wali->getLabel() : '';
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Proses export data santri telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' data telah ter-eskport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal ter-export.';
        }

        return $body;
    }
}
