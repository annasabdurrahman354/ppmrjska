<?php

namespace App\Filament\Resources;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPernikahan;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use App\Filament\Resources\BiodataSantriResource\Pages\CreateBiodataSantri;
use App\Filament\Resources\BiodataSantriResource\Pages\EditBiodataSantri;
use App\Filament\Resources\BiodataSantriResource\Pages\ListBiodataSantris;
use App\Models\BiodataSantri;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BiodataSantriResource extends Resource
{
    protected static ?string $model = BiodataSantri::class;
    protected static ?string $slug = 'biodata-santri';
    protected static ?string $modelLabel = 'Biodata Santri';
    protected static ?string $pluralModelLabel = 'Biodata Santri';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Biodata Santri';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                BiodataSantri::getForm()
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('user.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahun_pendaftaran')
                    ->label('Tahun Pendaftaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nik')
                    ->label('Nomor Induk Kewarganegaraan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tempatLahir.nama')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kewarganegaraan')
                    ->label('Kewarganegaraan')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ukuran_baju')
                    ->label('Ukuran Baju')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pendidikan_terakhir')
                    ->label('Pendidikan Terkahir')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('program_studi')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('universitas')
                    ->label('Universitas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('angkatan_kuliah')
                    ->label('Angkatan Kuliah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_kuliah')
                    ->label('Status Perkuliahan')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_lulus_kuliah')
                    ->label('Tanggal Lulus Kuliah')
                    ->date()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alamat')
                    ->label('Alamat Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provinsi.nama')
                    ->label('Alamat Provinsi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kota.nama')
                    ->label('Alamat Kota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kecamatan.nama')
                    ->label('Alamat Kecamatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelurahan.nama')
                    ->label('Alamat Kelurahan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('asal_kelompok')
                    ->label('Asal Kelompok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asal_desa')
                    ->label('Asal Desa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asal_daerah')
                    ->label('Asal Daerah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mulai_mengaji')
                    ->label('Mengaji Sejak')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bahasa_makna')
                    ->label('Bahasa Untuk Makna')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status_pernikahan')
                    ->label('Status Pernikahan')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_tinggal')
                    ->label('Tempat Tinggal')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_orangtua')
                    ->label('Kondisi Orang Tua')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_saudara')
                    ->label('Jumlah Saudara')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('anak_nomor')
                    ->label('Anak Ke-')
                    ->numeric()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_telepon_ayah')
                    ->label('Nomor Telepon Ayah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pekerjaan_ayah')
                    ->label('Pekerjaan Ayah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dapukan_ayah')
                    ->label('Dapukan Ayah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_telepon_ibu')
                    ->label('Nomor Telepon Ibu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pekerjaan_ibu')
                    ->label('Pekerjaan Ibu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dapukan_ibu')
                    ->label('Dapukan Ibu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBiodataSantris::route('/'),
            'create' => CreateBiodataSantri::route('/create'),
            'edit' => EditBiodataSantri::route('/{record}/edit'),
        ];
    }
}
