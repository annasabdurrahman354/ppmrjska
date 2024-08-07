<?php

namespace App\Filament\Pages;

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
use Closure;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $slug = 'profile';
    protected static ?string $title = 'Profil Anda';
    protected static string $view = 'filament.pages.profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'editProfileForm',
            'editPasswordForm',
        ];
    }

    public function editProfileForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Umum')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->label('Avatar')
                        ->avatar()
                        ->collection('user_avatar')
                        ->conversion('thumb')
                        ->moveFiles()
                        ->image()
                        ->imageEditor()
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->maxLength(96),
                    TextInput::make('nama_panggilan')
                        ->label('Nama Panggilan')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->maxLength(36),
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->options(JenisKelamin::class),
                    TextInput::make('nis')
                        ->label('Nomor Induk Santri')
                        ->disabled()
                        ->dehydrated()
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
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Section::make('Data Kesiswaan')
                ->schema([
                    TextInput::make('angkatan_pondok')
                        ->label('Angkatan Pondok')
                        ->numeric()
                        ->required()
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create'),
                    Checkbox::make('is_takmili')
                        ->label('Apakah santri takmili?')
                        ->inline(false)
                        ->required()
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create'),
                    Select::make('status_pondok')
                        ->label('Status Pondok')
                        ->options(StatusPondok::class)
                        ->required()
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create'),
                    DatePicker::make('tanggal_lulus_pondok')
                        ->label('Tanggal Lulus Pondok')
                        ->visible(fn(Get $get) => $get('status_pondok') === StatusPondok::LULUS->value)
                        ->required(fn(Get $get) => $get('status_pondok') === StatusPondok::LULUS->value)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create'),
                    TextInput::make('alasan_keluar_pondok')
                        ->label('Alasan Keluar Pondok')
                        ->visible(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->required(fn(Get $get) => $get('status_pondok') === StatusPondok::KELUAR->value)
                        ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create'),
                ])
                ->columns([
                    'sm' => 1,
                    'md' => 2,
                ]),

            Forms\Components\Group::make([
                Section::make('Biodata Santri')
                    ->schema([
                        TextInput::make('tahun_pendaftaran')
                            ->label('Tahun Pendaftaran')
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
                            ->required()
                            ->numeric(),
                        TextInput::make('nik')
                            ->label('Nomor Induk Kewarganegaraan')
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
                            ->required()
                            ->type('number')
                            ->maxLength(16),
                        Select::make('tempat_lahir_id')
                            ->label('Tempat Lahir')
                            ->searchable()
                            ->options(fn (Get $get) => Kota::pluck('nama', 'id'))
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
                            ->required(),
                        DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
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
                                ->columnSpan(7)
                                ->autocapitalize('words')
                                ->datalist(getProgramStudiList()),
                        ])
                            ->label('Program Studi')
                            ->columns(8),

                        TextInput::make('universitas')
                            ->label('Universitas')
                            ->required()
                            ->autocapitalize('words')
                            ->datalist(getUniversitasList()),

                        TextInput::make('angkatan_kuliah')
                            ->label('Angkatan Kuliah')
                            ->required()
                            ->numeric()
                            ->minValue(2011),

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
                            ->searchable()
                            ->options(Provinsi::all()->pluck('nama', 'id'))
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kota_id', null);
                            }),
                        Select::make('kota_id')
                            ->label('Kota')
                            ->required()
                            ->searchable()
                            ->options(fn (Get $get) => Kota::where('provinsi_id', $get('provinsi_id'))->pluck('nama', 'id'))
                            ->hidden(fn (Get $get) => $get('provinsi_id') == null)
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kecamatan_id', null);
                            }),
                        Select::make('kecamatan_id')
                            ->label('Kecamatan')
                            ->required()
                            ->searchable()
                            ->options(fn (Get $get) => Kecamatan::where('kota_id', $get('kota_id'))->pluck('nama', 'id'))
                            ->hidden(fn (Get $get) => $get('kota_id') == null)
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('kelurahan_id', null);
                            }),
                        Select::make('kelurahan_id')
                            ->label('Kelurahan')
                            ->required()
                            ->searchable()
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

                Section::make('Orang Tua/Wali')
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->label('Nama Ayah')
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
                            ->required(),
                        TextInput::make('nomor_telepon_ayah')
                            ->label('Nomor Telepon Ayah')
                            ->tel()
                            ->maxLength(16),
                        TextInput::make('pekerjaan_ayah')
                            ->label('Pekerjaan Ayah'),
                        TextInput::make('dapukan_ayah')
                            ->label('Dapukan Ayah'),

                        TextInput::make('nama_ibu')
                            ->label('Nama Ibu')
                            ->disabled(fn (string $operation) => cant('ubah_data_kesiswaan_users') || $operation != 'create')
                            ->required(),
                        TextInput::make('nomor_telepon_ibu')
                            ->label('Nomor Telepon Ibu')
                            ->tel()
                            ->maxLength(16),
                        TextInput::make('pekerjaan_ibu')
                            ->label('Pekerjaan Ibu'),
                        TextInput::make('dapukan_ibu')
                            ->label('Dapukan Ibu'),

                        TextInput::make('nama_wali')
                            ->label('Nama Wali'),
                        TextInput::make('nomor_telepon_wali')
                            ->label('Nomor Telepon Wali')
                            ->tel()
                            ->maxLength(16),
                        TextInput::make('pekerjaan_wali')
                            ->label('Pekerjaan Wali'),
                        TextInput::make('dapukan_wali')
                            ->label('Dapukan Wali'),
                        Select::make('hubungan_wali')
                            ->label('Hubungan Wali')
                            ->options(HubunganWali::class),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ]),
                ])
                ->relationship('biodataSantri')


        ])
        ->model($this->getUser())
        ->statePath('profileData');
    }

    public function editPasswordForm(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Perbarui Password')
                ->schema([
                    Forms\Components\TextInput::make('current_password')
                        ->label('Password Lama')
                        ->password()
                        ->required()
                        ->currentPassword()
                        ->rules([
                            fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                if (!Hash::check($value, auth()->user()->password)) {
                                    $fail('Password lama salah!');
                                }
                            },
                        ]),
                    Forms\Components\TextInput::make('password')
                        ->label('Password Baru')
                        ->password()
                        ->required()
                        ->rule(Password::default())
                        ->autocomplete('new-password')
                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                        ->live(debounce: 500)
                        ->same('password_confirmation'),
                    Forms\Components\TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password')
                        ->password()
                        ->required()
                        ->dehydrated(false),
                ]),
        ])
            ->model($this->getUser())
            ->statePath('passwordData');
    }

    protected function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();
        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }
        return $user;
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();
        $this->editProfileForm->fill($data);
        $this->editPasswordForm->fill();
    }

    protected function getUpdateProfileFormActions(): array
    {
        return [
            Action::make('updateProfileAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editProfileForm'),
        ];
    }

    protected function getUpdatePasswordFormActions(): array
    {
        return [
            Action::make('updatePasswordAction')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->submit('editPasswordForm'),
        ];
    }

    public function updateProfile(): void
    {
        $data = $this->editProfileForm->getState();
        if (isset($data['is_takmili'])){
            if ($data['is_takmili']) {
                $data['kelas'] = 'takmili';
            } else {
                $data['kelas'] = (string) $data['angkatan_pondok'];
            }
        }
        $this->handleRecordUpdate($this->getUser(), $data);
        $this->sendSuccessNotification();
    }

    public function updatePassword(): void
    {
        $data = $this->editPasswordForm->getState();
        $this->handleRecordUpdate($this->getUser(), $data);
        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put(['password_hash_' . Filament::getAuthGuard() => $data['password']]);
        }
        $this->editPasswordForm->fill();
        $this->sendSuccessNotification();
    }

    private function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        return $record;
    }

    private function sendSuccessNotification()
    {
        Notification::make()
            ->success()
            ->title(__('filament-panels::pages/auth/edit-profile.notifications.saved.title'))
            ->send();
    }
}
