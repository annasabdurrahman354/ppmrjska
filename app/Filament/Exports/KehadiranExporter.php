<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class KehadiranExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nama')
                ->label('Nama'),
            ExportColumn::make('angkatanPondok.kelas')
                ->label('Kelas'),
            ExportColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->state(function ($record) {
                    return $record->jenis_kelamin->getLabel();
                }),
            ExportColumn::make('hadir_count')
                ->label('Jumlah Hadir')
                ->state(function ($record) {
                    return $record->hadir_count;
                }),
            ExportColumn::make('telat_count')
                ->label('Jumlah Telat')
                ->state(function ($record) {
                    return $record->telat_count;
                }),
            ExportColumn::make('izin_count')
                ->label('Jumlah Izin')
                ->state(function ($record) {
                    return $record->izin_count;
                }),
            ExportColumn::make('sakit_count')
                ->label('Jumlah Sakit')
                ->state(function ($record) {
                    return $record->sakit_count;
                }),
            ExportColumn::make('alpa_count')
                ->label('Jumlah Alpa')
                ->state(function ($record) {
                    return $record->alpa_count;
                }),
            ExportColumn::make('total_presensi')
                ->label('Total Jumlah Presensi')
                ->state(function ($record) {
                    return $record->total_presensi ?: 0;
                }),
            ExportColumn::make('hadir_percentage')
                ->label('Persentase Hadir')
                ->state(function ($record) {
                    return $record->total_presensi ? round(($record->hadir_count / $record->total_presensi) * 100, 2): 0;
                }),
            ExportColumn::make('telat_percentage')
                ->label('Persentase Telat')
                ->state(function ($record) {
                    return $record->total_presensi ? round(($record->telat_count / $record->total_presensi) * 100, 2): 0;
                }),
            ExportColumn::make('izin_percentage')
                ->label('Persentase Izin')
                ->state(function ($record) {
                    return $record->total_presensi ? round(($record->izin_count / $record->total_presensi) * 100, 2): 0;
                }),
            ExportColumn::make('sakit_percentage')
                ->label('Persentase Sakit')
                ->state(function ($record) {
                    return $record->total_presensi ? round(($record->sakit_count / $record->total_presensi) * 100, 2): 0;
                }),
            ExportColumn::make('alpa_percentage')
                ->label('Persentase Alpa')
                ->state(function ($record) {
                    return $record->total_presensi ? round(($record->alpa_count / $record->total_presensi) * 100, 2): 0;
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Proses export data kehadiran santri telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' data telah ter-eskport.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal ter-export.';
        }

        return $body;
    }
}
