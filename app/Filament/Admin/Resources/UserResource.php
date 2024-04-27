<?php

namespace App\Filament\Admin\Resources;

use App\Enums\BahasaMakna;
use App\Enums\GolonganDarah;
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
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $slug = 'santri';
    protected static ?string $modelLabel = 'Santri';
    protected static ?string $pluralModelLabel = 'Santri';
    protected static ?string $navigationLabel = 'Santri';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Akun')
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(96),
                        TextInput::make('nama_panggilan')
                            ->label('Nama Panggilan')
                            ->required()
                            ->maxLength(36),
                        Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->required()
                            ->options(JenisKelamin::class),
                        TextInput::make('nis')
                            ->label('Nomor Induk Santri')
                            ->numeric()
                            ->required()
                            ->length(9),
                        TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(13),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(64),
                        Select::make('roles')->label('Role')
                            ->relationship(name: 'roles', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                                return $query->whereNotIn('name', ['filament_user']);
                            })
                            ->options(fn () => DB::table('roles')->pluck('name', 'id'))
                            ->multiple()
                            ->native(false),
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Terverifikasi Pada'),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                Section::make('Data Kesiswaan')
                    ->schema([
                        TextInput::make('angkatan_pondok')
                            ->label('Angkatan Pondok')
                            ->required()
                            ->numeric(),
                        Checkbox::make('is_takmili')
                            ->label('Apakah santri takmili?')
                            ->inline(false),
                        Select::make('status_pondok')
                            ->label('Status Pondok')
                            ->required()
                            ->options(StatusPondok::class),
                        DatePicker::make('tanggal_lulus_pondok'),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),

                Group::make()
                    ->relationship('biodataSantri')
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data) {
                        if(matchPatternProgramStudi($data['program_studi'])){
                            $jenjang = getJenjangProgramStudi($data['program_studi']);
                            $prodi = getProgramStudi($data['program_studi']);
                            $data['program_studi_jenjang'] = $jenjang;
                            $data['program_studi'] = $prodi;
                        }
                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data) {
                        if(matchPatternProgramStudi($data['program_studi'])){
                            $data['program_studi'] = getProgramStudi($data['program_studi']);
                            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
                        }
                        else{
                            $data['program_studi'] = $data['program_studi_jenjang'].'-'.$data['program_studi'];
                        }

                        return $data;
                    })
                    ->schema([
                        Section::make('Data Pribadi')
                        ->schema([
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
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_panggilan')
                    ->label('Nama Panggilan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nis')
                    ->label('Nomor Induk Santri')
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
                TextColumn::make('angkatan_pondok')
                    ->label('Angkatan Pondok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_pondok')
                    ->label('Status Pondok')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tanggal_lulus_pondok')
                    ->label('Tanggal Lulus Pondok')
                    ->date()
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
