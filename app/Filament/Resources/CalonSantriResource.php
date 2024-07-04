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
use App\Models\Provinsi;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;

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
            ->schema([
                Section::make('Data Pribadi')
                    ->schema([
                        Select::make('gelombang_pendaftaran_id')
                            ->label('Gelombang Pendaftaran')
                            ->required()
                            ->options(fn ($record): Collection =>
                                GelombangPendaftaran::all()->pluck('name', 'id')
                            )
                            ->disabledOn('edit')
                            ->searchable(),
                        TextInput::make('nama')
                            ->label('Nama')
                            ->required(),
                        TextInput::make('nama_panggilan')
                            ->label('Nama Panggilan')
                            ->required(),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options(JenisKelamin::class)
                            ->required(),
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('nik')
                            ->label('Nomor Induk Kewarganegaraan')
                            ->required()
                            ->type('number')
                            ->maxLength(16),
                        Select::make('tempat_lahir_id')
                            ->label('Tempat Lahir')
                            ->required()
                            ->relationship('tempatLahir', 'nama')
                            ->searchable(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        ToggleButtons::make('status_mubaligh')
                            ->label('Apakah sudah pernah mendapat ijazah mubaligh?')
                            ->options([
                                true => 'Sudah',
                                false => 'Belum',
                            ])
                            ->required(),
                        Select::make('kewarganegaraan')
                            ->label('Kewarganegaraan')
                            ->required()
                            ->options(Kewarganegaraan::class),
                        Select::make('golongan_darah')
                            ->label('Golongan Darah')
                            ->required()
                            ->options(GolonganDarah::class),
                        Select::make('ukuran_baju')
                            ->label('Ukuran Baju')
                            ->required()
                            ->options(UkuranBaju::class),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                Section::make('Pendidikan')
                    ->schema([
                        Select::make('pendidikan_terakhir')
                            ->label('Pendidikan Terkahir')
                            ->required()
                            ->options(PendidikanTerakhir::class),

                        Cluster::make([
                            Select::make('program_studi_jenjang')
                                ->options([
                                    'S1' => 'S1',
                                    'S2' => 'S2',
                                    'S3' => 'S3',
                                    'D3' => 'D3',
                                    'D4' => 'D4',
                                    'Profesi' => 'Profesi',
                                ])
                                ->default('S1')
                                ->required(),
                            TextInput::make('program_studi')
                                ->label('Program Studi')
                                ->required()
                                ->maxLength(96)
                                ->columnSpan(7)
                                ->autocapitalize('words')
                                ->datalist(getProgramStudiList()),
                        ])
                            ->label('Program Studi')
                            ->columns(8),

                        TextInput::make('universitas')
                            ->label('Universitas')
                            ->required()
                            ->maxLength(96)
                            ->autocapitalize('words')
                            ->datalist(getUniversitasList()),

                        TextInput::make('angkatan_kuliah')
                            ->label('Angkatan Kuliah')
                            ->required()
                            ->numeric()
                            ->minValue(2015),

                        Select::make('status_kuliah')
                            ->label('Status Kuliah')
                            ->required()
                            ->options(StatusKuliah::class),

                        DatePicker::make('tanggal_lulus_kuliah')
                            ->label('Tanggal Lulus Kuliah'),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                Section::make('Alamat Rumah')
                    ->schema([
                        TextInput::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(
                                ['md' => 2]
                            )
                            ->autocapitalize('words'),
                        Select::make('provinsi_id')
                            ->label('Provinsi')
                            ->required()
                            ->options(Provinsi::all()->pluck('nama', 'id'))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('kota_id', null);
                            }),
                        Select::make('kota_id')
                            ->label('Kota')
                            ->required()
                            ->options(fn (Get $get) => Kota::where('provinsi_id', $get('provinsi_id'))->pluck('nama', 'id'))
                            ->hidden(fn (Get $get) => $get('provinsi_id') == null)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('kecamatan_id', null);
                            }),
                        Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->required()
                            ->options(fn (Get $get) => Kecamatan::where('kota_id', $get('kota_id'))->pluck('nama', 'id'))
                            ->hidden(fn (Get $get) => $get('kota_id') == null)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                $set('kelurahan_id', null);
                            }),
                        Select::make('kelurahan_id')
                            ->label('Kelurahan')
                            ->required()
                            ->options(fn (Get $get) => Kelurahan::where('kecamatan_id', $get('kecamatan_id'))->pluck('nama', 'id'))
                            ->hidden(fn (Get $get) => $get('kecamatan_id') == null)
                            ->live()
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                Section::make('Informasi Sambung')
                    ->schema([
                        TextInput::make('asal_kelompok')
                            ->label('Asal Kelompok')
                            ->required()
                            ->maxLength(96)
                            ->autocapitalize('words'),
                        TextInput::make('asal_desa')
                            ->label('Asal Desa')
                            ->required()
                            ->maxLength(96)
                            ->autocapitalize('words'),
                        TextInput::make('asal_daerah')
                            ->label('Asal Daerah')
                            ->required()
                            ->maxLength(96)
                            ->autocapitalize('words'),
                        Select::make('mulai_mengaji')
                            ->label('Mulai Mengaji Sejak')
                            ->required()
                            ->options(MulaiMengaji::class),
                        Select::make('bahasa_makna')
                            ->label('Bahasa Dalam Makna')
                            ->required()
                            ->options(BahasaMakna::class),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 3,
                    ]),

                // Family Section
                Section::make('Keluarga')
                    ->schema([
                        Select::make('status_pernikahan')
                            ->label('Status Pernikahan')
                            ->required()
                            ->options(StatusPernikahan::class),
                        Select::make('status_tinggal')
                            ->label('Status Tinggal')
                            ->required()
                            ->options(StatusTinggal::class),
                        Select::make('status_orangtua')
                            ->label('Status Orang Tua')
                            ->required()
                            ->options(StatusOrangTua::class),
                        TextInput::make('jumlah_saudara')
                            ->label('Jumlah Saudara')
                            ->required()
                            ->numeric(),
                        TextInput::make('anak_nomor')
                            ->label('Anak Nomor')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                // Parent Section
                Section::make('Orang Tua')
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('nomor_telepon_ayah')
                            ->label('Nomor Telepon Ayah')
                            ->tel()
                            ->required()
                            ->maxLength(13),
                        TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('dapukan_ayah')
                            ->label('Dapukan Ayah')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('nomor_telepon_ibu')
                            ->label('Nomor Telepon Ibu')
                            ->tel()
                            ->required()
                            ->maxLength(13),
                        TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('dapukan_ibu')
                            ->label('Dapukan Ibu')
                            ->required()
                            ->maxLength(96),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),
            ]);
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
                Tables\Actions\EditAction::make()
                    ->visible(isSuperAdmin()),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('isiPenilaian')
                    ->label('Penilaian')
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
            'index' => ListCalonSantri::route('/'),
            'create' => CreateCalonSantri::route('/create'),
            'edit' => EditCalonSantri::route('/{record}/edit'),
            'view' => ViewCalonSantri::route('/{record}'),
        ];
    }
}
