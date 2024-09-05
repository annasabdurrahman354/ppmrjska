<?php

namespace App\Filament\Resources;

use App\Enums\StatusPondok;
use App\Filament\Exports\SantriExporter;
use App\Filament\Imports\SantriImporter;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\AngkatanPondok;
use App\Models\BiodataSantri;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;
    protected static ?string $slug = 'santri';
    protected static ?string $modelLabel = 'Santri';
    protected static ?string $pluralModelLabel = 'Santri';
    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Santri';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Informasi Akun')
                            ->schema(
                                User::getForm()
                            ),
                        Tabs\Tab::make('Biodata Santri')
                            ->schema([
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
                                    ->schema(
                                        BiodataSantri::getForm()
                                    )
                                    ->columnSpanFull()
                            ]),
                    ])
                    ->columnSpanFull(),
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
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_panggilan')
                    ->label('Nama Panggilan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('angkatan_pondok')
                    ->label('Angkatan Pondok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('angkatanPondok.kelas')
                    ->label('Kelas')
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
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge()
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tanggal_keluar_pondok')
                    ->label('Tanggal Keluar Pondok')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('alasan_keluar_pondok')
                    ->label('Alasan Keluar Pondok')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.tahun_pendaftaran')
                    ->label('Tahun Mendaftar')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nik')
                    ->label('Nomor Induk Kewarganegaraan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.tempatLahir.nama')
                    ->label('Tempat Lahir')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.kewarganegaraan')
                    ->label('Kewarganegaraan')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.golongan_darah')
                    ->label('Golongan Darah')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.ukuran_baju')
                    ->label('Ukuran Baju')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('biodataSantri.pendidikan_terakhir')
                    ->label('Pendidikan Terkahir')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.program_studi')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.universitas')
                    ->label('Universitas')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.angkatan_kuliah')
                    ->label('Angkatan Kuliah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.status_kuliah')
                    ->label('Status Perkuliahan')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.tanggal_lulus_kuliah')
                    ->label('Tanggal Lulus Kuliah')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('biodataSantri.alamat')
                    ->label('Alamat Lengkap')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.provinsi.nama')
                    ->label('Alamat Provinsi')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.kota.nama')
                    ->label('Alamat Kota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.kecamatan.nama')
                    ->label('Alamat Kecamatan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.kelurahan.nama')
                    ->label('Alamat Kelurahan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('biodataSantri.asal_kelompok')
                    ->label('Asal Kelompok')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.asal_desa')
                    ->label('Asal Desa')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.asal_daerah')
                    ->label('Asal Daerah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.mulai_mengaji')
                    ->label('Mengaji Sejak')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.bahasa_makna')
                    ->label('Bahasa Untuk Makna')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('biodataSantri.status_pernikahan')
                    ->label('Status Pernikahan')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.status_tinggal')
                    ->label('Tempat Tinggal')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.status_orangtua')
                    ->label('Kondisi Orang Tua')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.jumlah_saudara')
                    ->label('Jumlah Saudara')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.anak_nomor')
                    ->label('Anak Ke-')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('biodataSantri.nama_ayah')
                    ->label('Nama Ayah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nomor_telepon_ayah')
                    ->label('Nomor Telepon Ayah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.pekerjaan_ayah')
                    ->label('Pekerjaan Ayah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.dapukan_ayah')
                    ->label('Dapukan Ayah')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nama_ibu')
                    ->label('Nama Ibu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nomor_telepon_ibu')
                    ->label('Nomor Telepon Ibu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.pekerjaan_ibu')
                    ->label('Pekerjaan Ibu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.dapukan_ibu')
                    ->label('Dapukan Ibu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nama_wali')
                    ->label('Nama Wali')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.nomor_telepon_wali')
                    ->label('Nomor Telepon Wali')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.pekerjaan_wali')
                    ->label('Pekerjaan Wali')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.dapukan_wali')
                    ->label('Dapukan Wali')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('biodataSantri.hubungan_wali')
                    ->label('Hubungan Wali')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make()
                    ->hidden(),
                SelectFilter::make('angkatan_pondok')
                    ->label('Angkatan Pondok')
                    ->multiple()
                    ->options(AngkatanPondok::orderBy('angkatan_pondok')
                        ->select('angkatan_pondok')
                        ->distinct()
                        ->get()
                        ->pluck('angkatan_pondok', 'angkatan_pondok')
                    )
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(2)
            ->headerActions([
                ImportAction::make()
                    ->importer(SantriImporter::class)
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('ubahStatusPondok')
                    ->label('Ubah Status Pondok')
                    ->visible(can('ubah_data_kesiswaan_user'))
                    ->fillForm(fn (User $record): array => [
                        'status_pondok' => $record->status_pondok,
                        'tanggal_lulus_pondok' => $record->tanggal_lulus_pondok,
                        'tanggal_keluar_pondok' => $record->tanggal_keluar_pondok,
                        'alasan_keluar_pondok' => $record->alasan_keluar_pondok,
                    ])
                    ->form([
                        Select::make('status_pondok')
                            ->label('Status Pondok')
                            ->options(StatusPondok::class)
                            ->required()
                            ->live(),
                        DatePicker::make('tanggal_lulus_pondok')
                            ->label('Tanggal Lulus Pondok')
                            ->visible(fn(Get $get) => $get('status_pondok') == StatusPondok::LULUS)
                            ->required(fn(Get $get) => $get('status_pondok') == StatusPondok::LULUS),
                        DatePicker::make('tanggal_keluar_pondok')
                            ->label('Tanggal Keluar Pondok')
                            ->visible(fn(Get $get) => $get('status_pondok') == StatusPondok::KELUAR)
                            ->required(fn(Get $get) => $get('status_pondok') == StatusPondok::KELUAR),
                        TextInput::make('alasan_keluar_pondok')
                            ->label('Alasan Keluar Pondok')
                            ->visible(fn(Get $get) => $get('status_pondok') == StatusPondok::KELUAR)
                            ->required(fn(Get $get) => $get('status_pondok') == StatusPondok::KELUAR),
                    ])
                    ->action(function (array $data, User $record): void {
                        $record->status_pondok = $data['status_pondok'];
                        if ($data['status_pondok'] == StatusPondok::LULUS) {
                            $record->tanggal_lulus_pondok = $data['tanggal_lulus_pondok'];
                        }
                        if ($data['status_pondok'] == StatusPondok::KELUAR) {
                            $record->alasan_keluar_pondok = $data['alasan_keluar_pondok'];
                        }
                        $record->save();
                    }),
                Tables\Actions\Action::make('ubahAngkatanPondok')
                    ->label('Ubah Angkatan')
                    ->visible(can('ubah_data_kesiswaan_user'))
                    ->fillForm(fn (User $record): array => [
                        'angkatan_pondok' => $record->angkatan_pondok,
                    ])
                    ->form([
                        Select::make('angkatan_pondok')
                            ->label('Angkatan Pondok')
                            ->options(AngkatanPondok::all()->pluck('angkatan_pondok', 'angkatan_pondok'))
                    ])
                    ->action(function (array $data, User $record): void {
                        $record->angkatan_pondok = $data['angkatan_pondok'];
                        $record->save();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\ExportBulkAction::make('exportSantri')
                    ->exporter(SantriExporter::class),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'ubah_data_kesiswaan',
        ];
    }
}
