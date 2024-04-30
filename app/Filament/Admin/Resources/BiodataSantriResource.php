<?php

namespace App\Filament\Admin\Resources;

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
use App\Filament\Admin\Resources\BiodataSantriResource\Pages;
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
    protected static ?string $navigationLabel = 'Biodata Santri';
    protected static ?string $recordTitleAttribute = 'recordTitle';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 42;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pribadi')
                    ->schema([
                        //Placeholder::make('user_id')
                        //    ->label('Santri')
                        //    ->content(fn (BiodataSantri $record): string => $record->user->nama),
                        Select::make('user_id')
                            ->label('Pemilik Biodata')
                            ->required()
                            ->options(fn ($record): Collection =>
                                DB::table('users')
                                    ->whereNotIn('id', DB::table('biodata_santri')->select('user_id')->where('user_id', '!=',  $record->user_id ?? '')->get()->pluck('user_id'))
                                    ->get()
                                    ->pluck('nama', 'id')
                            )
                            ->disabledOn('edit')
                            ->searchable(),
                        TextInput::make('nik')
                            ->label('Nomor Induk Kewarganegaraan')
                            ->required()
                            ->type('number')
                            ->maxLength(16),
                        Select::make('kota_lahir_id')
                            ->label('Tempat Lahir')
                            ->required()
                            ->relationship('kotaLahir', 'nama')
                            ->searchable(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->required(),
                        TextInput::make('tahun_pendaftaran')
                            ->label('Tahun Pendaftaran')
                            ->required()
                            ->numeric(),
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
                TextColumn::make('id')
                    ->label('ID')
                    ->visible(isSuperAdmin())
                    ->searchable(),
                TextColumn::make('user.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kotaLahir.nama')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alamat')
                    ->label('Alamat Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelurahan.nama')
                    ->label('Alamat Kelurahan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kecamatan.nama')
                    ->label('Alamat Kecamatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kota.nama')
                    ->label('Alamat Kota')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('provinsi.nama')
                    ->label('Alamat Provinsi')
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
                TextColumn::make('kewarganegaraan')
                    ->label('Kewarganegaraan')
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
                TextColumn::make('anak_nomor')
                    ->label('Anak Ke-')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_saudara')
                    ->label('Jumlah Saudara')
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
            'index' => Pages\ListBiodataSantris::route('/'),
            'create' => Pages\CreateBiodataSantri::route('/create'),
            'edit' => Pages\EditBiodataSantri::route('/{record}/edit'),
        ];
    }
}
