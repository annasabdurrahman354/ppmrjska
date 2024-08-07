<?php

namespace App\Filament\Resources;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
use App\Enums\JenisKelamin;
use App\Enums\Kewarganegaraan;
use App\Enums\MulaiMengaji;
use App\Enums\PendidikanTerakhir;
use App\Enums\StatusKuliah;
use App\Enums\StatusOrangTua;
use App\Enums\StatusPenerimaan;
use App\Enums\StatusPernikahan;
use App\Enums\StatusTinggal;
use App\Enums\UkuranBaju;
use App\Filament\Resources\CalonSantriResource\Pages\CreateCalonSantri;
use App\Filament\Resources\CalonSantriResource\Pages\EditCalonSantri;
use App\Filament\Resources\CalonSantriResource\Pages\ListCalonSantri;
use App\Filament\Resources\CalonSantriResource\Pages\ViewCalonSantri;
use App\Models\CalonSantri;
use App\Models\GelombangPendaftaran;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\PenilaianCalonSantri;
use App\Models\Provinsi;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;
use Mokhosh\FilamentRating\Components\Rating;

class CalonSantriResource extends Resource
{
    protected static ?string $model = CalonSantri::class;
    protected static ?string $slug = 'calon-santri';
    protected static ?string $modelLabel = 'Calon Santri';
    protected static ?string $pluralModelLabel = 'Calon Santri';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationLabel = 'Calon Santri';
    protected static ?string $navigationGroup = 'Manajemen Pendaftaran';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CalonSantri::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('gelombangPendaftaran.pendaftaran.tahun_pendaftaran')
                    ->label('Tahun Pendaftaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gelombangPendaftaran.nomor_gelombang')
                    ->label('Tahun Pendaftaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_panggilan')
                    ->label('Nama Panggilan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
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
                ToggleColumn::make('status_mubaligh')
                    ->label('Status Mubaligh')
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
                TextColumn::make('nama_wali')
                    ->label('Nama Wali')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nomor_telepon_wali')
                    ->label('Nomor Telepon Wali')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pekerjaan_wali')
                    ->label('Pekerjaan Wali')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dapukan_wali')
                    ->label('Dapukan Wali')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hubungan_wali')
                    ->label('Hubungan Wali')
                    ->badge()
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
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('buatPenilaian')
                    ->label('Nilai')
                    ->fillForm(function (CalonSantri $record) {
                        if ($record->penilaianCalonSantri){
                            return $record->penilaianCalonSantri->toArray();
                        }
                        $indikator_penilaian =  $record->gelombangPendaftaran->pendaftaran->indikator_penilaian;
                        $nilaiTesArray = array_map(function($indikator) {
                            return [
                                'indikator' => $indikator,
                                'nilai' => 0,
                            ];
                        }, $indikator_penilaian);
                        return [
                            'nilai_tes' => $nilaiTesArray,
                        ];
                    })
                    ->form([
                        TableRepeater::make('nilai_tes')
                            ->label('Nilai Tes')
                            ->headers([
                                Header::make('Indikator Penilaian'),
                                Header::make('Nilai Tes')
                            ])
                            ->schema([
                                TextInput::make('indikator')
                                    ->label('Indikator Penilaian'),
                                TextInput::make('nilai')
                                    ->label('Nilai Tes')
                                    ->numeric()
                                    ->helperText('Masukkan nilai akhir untuk setiap indikator penilaian.')
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->cloneable(false)
                            ->collapsible(false)
                            ->columnSpanFull(),
                        TextInput::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->required(),
                        Textarea::make('catatan_penguji')
                            ->label('Catatan Penguji')
                            ->required(),
                        Rating::make('rekomendasi_penguji')
                            ->label('Rekomendasi Penguji')
                            ->stars(5)
                            ->required(),
                        ToggleButtons::make('status_penerimaan')
                            ->label('Status Penerimaan')
                            ->inline()
                            ->grouped()
                            ->options(StatusPenerimaan::class)
                            ->default(StatusPenerimaan::BELUM_DITENTUKAN->value)
                            ->required(),
                    ])
                    ->action(function (array $data, CalonSantri $record): void {
                        $data['penguji_id'] = auth()->user()->id;
                        PenilaianCalonSantri::updateOrCreate(
                            ['calon_santri_id' =>  $record->id],
                            $data
                        );
                    }),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->selectCurrentPageOnly();
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
            'index' => ListCalonSantri::route('/'),
            'create' => CreateCalonSantri::route('/create'),
            'edit' => EditCalonSantri::route('/{record}/edit'),
            'view' => ViewCalonSantri::route('/{record}'),
        ];
    }
}
