<?php

namespace App\Filament\Admin\Resources\JurnalKelasResource\Pages;

use App\Enums\JenisKelamin;
use App\Enums\Sesi;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Admin\Resources\JurnalKelasResource;
use App\Models\DewanGuru;
use App\Models\JurnalKelas;
use App\Models\MateriHimpunan;
use App\Models\MateriSurat;
use App\Models\MateriTambahan;
use App\Models\User;
use Awcodes\Shout\Components\Shout;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Str;


class QRCodeCreateJurnalKelas extends Page implements HasForms, HasActions
{
    protected static string $resource = JurnalKelasResource::class;

    protected static string $view = 'filament.resources.admin.jurnal-kelas-resource.pages.qr-code';

    public function getTitle(): string
    {
        return "Buat Jurnal Kelas dengan QR Code";
    }

    use InteractsWithForms;
    use HasUnsavedDataChangesAlert;

    public ?array $data = [];

    public $record;
    public $mode = 'create';

    public function mount(): void
    {
        $this->hasUnsavedDataChangesAlert();
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Manajemen Kelas')
                            ->icon('fluentui-people-list-24')
                            ->iconPosition(IconPosition::Before)
                            ->schema([
                                Section::make('Informasi KBM')
                                    ->schema([
                                        DatePicker::make('tanggal')
                                            ->label('Tanggal KBM')
                                            ->required()
                                            ->default(now()),

                                        Select::make('sesi')
                                            ->label('Sesi KBM')
                                            ->required()
                                            ->options(Sesi::class)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set){
                                                    $set('waktu_terlambat', match ($state) {
                                                        Sesi::SUBUH->value => '05:00',
                                                        Sesi::PAGI_1->value => '08:45',
                                                        Sesi::PAGI_2->value => '10:15',
                                                        Sesi::SIANG->value => '13:45',
                                                        Sesi::MALAM->value => '20:00',
                                                    });
                                                }
                                            ),

                                        Select::make('kelas')
                                            ->label('Kelas')
                                            ->multiple()
                                            ->disabledOn('edit')
                                            ->options(
                                                User::where('status_pondok', StatusPondok::AKTIF->value)
                                                ->where('tanggal_lulus_pondok', null)
                                                ->select('kelas')
                                                ->orderBy('kelas')
                                                ->distinct()
                                                ->get()
                                                ->pluck('kelas', 'kelas')
                                            )
                                            ->default(match (auth()->user()->kelas) {
                                                config('filament-shield.super_admin.name') => ['Takmili'],
                                                default => [auth()->user()->kelas]
                                            })
                                            ->live()
                                            ->afterStateUpdated(function(Get $get, Set $set, $state) {
                                                $users = User::whereIn('kelas', $state)
                                                    ->where('jenis_kelamin', $get('jenis_kelamin'))
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value,
                                                    ];
                                                }
                                                $set('presensiKelas', $result);
                                            }),

                                        ToggleButtons::make('jenis_kelamin')
                                            ->label('Santri')
                                            ->inline()
                                            ->grouped()
                                            ->required()
                                            ->disabledOn('edit')
                                            ->options(JenisKelamin::class)
                                            ->default(auth()->user()->jenis_kelamin)
                                            ->live()
                                            ->afterStateUpdated(function(Get $get, Set $set, $state) {
                                                $users = User::whereIn('kelas', $get('kelas'))
                                                    ->where('jenis_kelamin', $state)
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value,
                                                    ];
                                                }
                                                $set('presensiKelas', $result);
                                            }),

                                        Select::make('perekap_id')
                                            ->label('Perekap')
                                            ->required()
                                            ->disabledOn('edit')
                                            ->options(
                                                User::select('nama', 'id')
                                                    ->distinct()
                                                    ->get()
                                                    ->pluck('nama', 'id')
                                            )
                                            ->default(auth()->user()->id)
                                            ->preload()
                                            ->searchable(['nama'])
                                            ->columnSpanFull(),

                                        Fieldset::make()
                                            ->label('Dewan Guru')
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('dewan_guru_type')
                                                    ->hiddenLabel()
                                                    ->required()
                                                    ->inline()
                                                    ->grouped()
                                                    ->options([
                                                        DewanGuru::class => 'Dewan Guru',
                                                        User::class => 'Takmili',
                                                    ])
                                                    ->default(DewanGuru::class)
                                                    ->live()
                                                    ->afterStateUpdated(function(Set $set) {
                                                        $set('dewan_guru_id', null);
                                                    }),

                                                Select::make('dewan_guru_id')
                                                    ->required()
                                                    ->hiddenLabel()
                                                    ->placeholder('Pilih dewan guru/santri takmili...')
                                                    ->hidden(fn (Get $get) => $get('dewan_guru_type') == null)
                                                    ->searchable()
                                                    ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                        match ($get('dewan_guru_type')) {
                                                            DewanGuru::class =>
                                                                DewanGuru::where('nama', 'like', "%{$search}%")
                                                                    ->limit(20)->pluck('nama', 'id')
                                                                    ->toArray(),
                                                            User::class =>
                                                                User::where('nama', 'like', "%{$search}%")
                                                                    ->where('kelas', 'takmili')
                                                                    ->limit(20)->pluck('nama', 'id')
                                                                    ->toArray()
                                                        }

                                                    )
                                                    ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                        match ($get('dewan_guru_type')) {
                                                            DewanGuru::class =>
                                                                DewanGuru::find($value)?->nama,
                                                            User::class =>
                                                                User::find($value)?->nama
                                                        }

                                                    )
                                                    ->live(),
                                            ])
                                    ])->columns([
                                        'sm' => 1,
                                        'md' => 2
                                    ])
                                    ->columnSpanFull(),

                                Section::make('Presensi')
                                    ->schema([
                                        Shout::make('st-empty')
                                            ->content('Belum ada presensi santri!')
                                            ->type('info')
                                            ->color(Color::Yellow)
                                            ->visible(fn(Get $get) => !filled($get('presensiKelas'))),
                                        TimePicker::make('waktu_terlambat')
                                            ->seconds(false)
                                            ->default(fn (Get $get ) =>
                                                match ($get('sesi')) {
                                                    Sesi::SUBUH->value => '05:15',
                                                    Sesi::PAGI_1->value => '08:45',
                                                    Sesi::PAGI_2->value => '10:15',
                                                    Sesi::SIANG->value => '13:45',
                                                    Sesi::MALAM->value => '20:00',
                                                    default => null
                                                }

                                            ),

                                        ViewField::make('qr-code')
                                            ->hiddenLabel()
                                            ->view('forms.components.qr-code-scanner'),

                                        Repeater::make('presensiKelas')
                                            ->hiddenLabel()
                                            ->relationship('presensiKelas')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->live()
                                            ->default(function(Get $get) {
                                                $users = User::whereIn('kelas', $get('kelas'))
                                                    ->where('jenis_kelamin', $get('jenis_kelamin'))
                                                    ->where('status_pondok', StatusPondok::AKTIF->value)
                                                    ->where('tanggal_lulus_pondok', null)
                                                    ->orderBy('nama')
                                                    ->get();

                                                $result = [];
                                                foreach ($users as $user) {
                                                    $result[(string) Str::uuid()] = [
                                                        'user_id' => $user->id,
                                                        'status_kehadiran' => StatusKehadiran::ALPA->value
                                                    ];
                                                }
                                                return $result;
                                            })
                                            ->schema([
                                                Select::make('user_id')
                                                    ->hiddenLabel()
                                                    ->placeholder('Pilih santri sesuai kelas...')
                                                    ->required()
                                                    ->distinct()
                                                    ->disabledOn('edit')
                                                    ->searchable()
                                                    ->preload()
                                                    ->getSearchResultsUsing(fn (string $search, Get $get): array =>
                                                        User::where('nama', 'like', "%{$search}%")
                                                            ->where('jenis_kelamin', $get('../../jenis_kelamin'))
                                                            ->whereIn('kelas', $get('../../kelas'))
                                                            ->where('status_pondok',  StatusPondok::AKTIF->value)
                                                            ->where('tanggal_lulus_pondok', null)
                                                            ->limit(20)
                                                            ->pluck('nama', 'id')
                                                            ->toArray()
                                                    )
                                                    ->getOptionLabelUsing(fn ($value): ?string => User::find($value)?->nama)
                                                    ->columnSpan(4),

                                                ToggleButtons::make('status_kehadiran')
                                                    ->hiddenLabel()
                                                    ->inline()
                                                    ->grouped()
                                                    ->required()
                                                    ->options([
                                                        'hadir' => 'H',
                                                        'telat' => 'T',
                                                        'izin' => 'I',
                                                        'sakit' => 'S',
                                                        'alpa' => 'A',
                                                    ])
                                                    ->colors([
                                                        'hadir' => 'success',
                                                        'telat' => 'primary',
                                                        'izin' => 'warning',
                                                        'sakit' => 'secondary',
                                                        'alpa' => 'danger',
                                                    ])
                                                    ->default(StatusKehadiran::ALPA->value)
                                                    ->columnSpan(1),
                                            ])
                                            ->addActionLabel('Tambah +')
                                            ->columns([
                                                'sm' => 1,
                                                'md' => 5
                                            ])
                                            ->columnSpanFull(),
                                        ]),
                            ]),
                        Tab::make('Materi yang Disampaikan')
                            ->icon('fluentui-notebook-24')
                            ->iconPosition(IconPosition::Before)
                            ->schema([
                                Fieldset::make()
                                    ->label('Materi Awal')
                                    ->schema([
                                        ToggleButtons::make('materi_awal_type')
                                            ->hiddenLabel()
                                            ->inline()
                                            ->options([
                                                MateriSurat::class => 'Al-Quran',
                                                MateriHimpunan::class => 'Himpunan',
                                                MateriTambahan::class => 'Lainnya',
                                            ])
                                            ->default(MateriSurat::class)
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('materi_awal_id', null);
                                            }),

                                        Select::make('materi_awal_id')
                                            ->hiddenLabel()
                                            ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') == null)
                                            ->searchable()
                                            ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                $get('materi_awal_type')::where('nama', 'like', "%{$search}%")
                                                    ->limit(20)->pluck('nama', 'id')
                                                    ->toArray(),
                                            )
                                            ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                    $get('materi_awal_type')::find($value)?->nama,
                                            )
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('halaman_awal', null);
                                                $set('ayat_awal', null);
                                            }),

                                        TextInput::make('halaman_awal')
                                            ->numeric()
                                            ->minValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_awal ?? 1)
                                            ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->halaman_akhir ?? 1000)
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') == null || $get('materi_awal_id') == null)
                                            ->default(null)
                                            ->columnSpan(fn (Get $get) => ($get('materi_awal_type') != MateriSurat::class) ? 2 : 1),

                                        TextInput::make('ayat_awal')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(fn (Get $get) => $get('materi_awal_type')::where('id',  $get('materi_awal_id'))->first()->jumlah_ayat ?? 300)
                                            ->hidden(fn (Get $get) => $get('materi_awal_type') != MateriSurat::class || $get('materi_awal_id') == null)
                                            ->default(null),
                                    ])
                                    ->columns([
                                        'sm' => 1,
                                        'lg' => 2
                                    ])
                                    ->columnSpanFull(),

                                Fieldset::make()
                                    ->label('Materi Akhir')
                                    ->schema([
                                        ToggleButtons::make('materi_akhir_type')
                                            ->hiddenLabel()
                                            ->inline()
                                            ->options([
                                                MateriSurat::class => 'Al-Quran',
                                                MateriHimpunan::class => 'Himpunan',
                                                MateriTambahan::class => 'Lainnya',
                                            ])
                                            ->default(MateriSurat::class)
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('materi_akhir_id', null);
                                            }),

                                        Select::make('materi_akhir_id')
                                            ->hiddenLabel()
                                            ->placeholder('Pilih surat Al-Quran/himpunan/materi kelas/hafalan...')
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') == null)
                                            ->searchable()
                                            ->getSearchResultsUsing(fn (Get $get, string $search): array =>
                                                $get('materi_akhir_type')::where('nama', 'like', "%{$search}%")
                                                    ->limit(20)->pluck('nama', 'id')
                                                    ->toArray(),
                                            )
                                            ->getOptionLabelUsing(fn (Get $get, $value): ?string =>
                                                    $get('materi_akhir_type')::find($value)?->nama,
                                            )
                                            ->live()
                                            ->afterStateUpdated(function(Set $set) {
                                                $set('halaman_akhir', null);
                                                $set('ayat_akhir', null);
                                            }),

                                        TextInput::make('halaman_akhir')
                                            ->numeric()
                                            ->minValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_awal ?? 1)
                                            ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->halaman_akhir ?? 1000)
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') == null || $get('materi_akhir_id') == null)
                                            ->default(null)
                                            ->columnSpan(fn (Get $get) => ($get('materi_akhir_type') != MateriSurat::class) ? 2 : 1),

                                        TextInput::make('ayat_akhir')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(fn (Get $get) => $get('materi_akhir_type')::where('id',  $get('materi_akhir_id'))->first()->jumlah_ayat ?? 300)
                                            ->hidden(fn (Get $get) => $get('materi_akhir_type') != MateriSurat::class || $get('materi_akhir_id') == null)
                                            ->default(null),
                                    ])
                                    ->columns([
                                        'sm' => 1,
                                        'lg' => 2
                                    ])
                                    ->columnSpanFull(),

                                TextInput::make('keterangan')
                                    ->label('Keterangan Tambahan')
                                    ->maxLength(255),

                                TextInput::make('link_rekaman')
                                    ->label('Link Rekaman')

                            ]),
                        ]),
            ])
            ->statePath('data')
            ->model(JurnalKelas::class);
    }


    public function addScannedUser($nis): void
    {
        $currentTime = Carbon::now();
        $waktuTerlambat = Carbon::parse($this->data['waktu_terlambat'])->toDateTimeImmutable();

        if($this->data['kelas'] == null || $this->data['jenis_kelamin'] == null){
            Notification::make()
                    ->title('Pilih kelas dan gender santri terlebih dahulu!')
                    ->danger()
                    ->send();
        }
        else {
            $scannedUser = User::where('nis', $nis)->select('id', 'nis', 'nama', 'kelas', 'jenis_kelamin')->first();

            if(is_null($scannedUser)) {
                Notification::make()
                    ->title('Santri dengan NIS '.$nis.' tidak ditemukan!')
                    ->danger()
                    ->send();
            }
            elseif(in_array($scannedUser->kelas, $this->data['kelas']) && $scannedUser->jenis_kelamin == $this->data['jenis_kelamin']){
                foreach ($this->data['presensiKelas'] as $key => $presensi) {
                    if ($this->data['presensiKelas'][$key]['user_id'] == $scannedUser->id) {
                        if($this->data['waktu_terlambat'] == null){
                            $this->data['presensiKelas'][$key]['status_kehadiran'] = "hadir";
                        }
                        else{
                            match($currentTime < $waktuTerlambat){
                                true => $this->data['presensiKelas'][$key]['status_kehadiran'] = "hadir",
                                false => $this->data['presensiKelas'][$key]['status_kehadiran'] = "telat",
                                default =>  $this->data['presensiKelas'][$key]['status_kehadiran'] = "hadir",
                            };
                        }

                        break;
                    }
                }

                Notification::make()
                    ->title('Santri '.$scannedUser->nama.' ditambahkan dalam presensi!')
                    ->success()
                    ->send();
            }
            else{
                Notification::make()
                    ->title('Santri '.$scannedUser->nama.' bukan anggota kelas ini!')
                    ->danger()
                    ->send();
            }
        }
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label('Simpan')
            ->action('create')
            ->color('primary');
    }

    public function saveTemporarilyAction(): Action
    {
        return Action::make('saveTemporarily')
            ->label('Simpan Sementara')
            ->action('saveTemporarily')
            ->color('secondary');
    }
    public function cancelAction(): Action
    {
        $resources = static::getResource();
        return Action::make('cancel')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.cancel.label'))
            ->url($resources::getUrl('index'))
            ->color('danger');
    }

    public function saveTemporarily()
    {
        $this->form->validate();
        if($this->mode == 'create'){
            $jurnalKelas = JurnalKelas::create($this->form->getState());
            $this->form->model($jurnalKelas)->saveRelationships();
            $this->record = $jurnalKelas;
            $this->mode = 'edit';
            $this->rememberData();

            Notification::make()
                ->title('Jurnal kelas telah tersimpan!')
                ->success()
                ->send();
        }
        else{
            $this->record->update($this->form->getState());
            $this->record->deleteAllPresensi();
            $this->form->model($this->record)->saveRelationships();
            $this->rememberData();

            Notification::make()
                ->title('Jurnal kelas telah diperbarui!')
                ->success()
                ->send();
        }
    }

    public function create(): void
    {
        $this->form->validate();
        $resources = static::getResource();
        if($this->mode == 'create'){
            $jurnalKelas = JurnalKelas::create($this->form->getState());
            $this->form->model($jurnalKelas)->saveRelationships();
            $this->record = $jurnalKelas;
            $this->mode = 'edit';
            $this->rememberData();

            Notification::make()
                ->title('Jurnal kelas telah tersimpan!')
                ->success()
                ->send();

            $this->redirect($resources::getUrl('view', ['record' => $this->record->id]));
        }
        else{
            $this->record->update($this->form->getState());
            $this->record->deleteAllPresensi();
            $this->form->model($this->record)->saveRelationships();
            $this->rememberData();

            Notification::make()
                ->title('Jurnal kelas telah tersimpan!')
                ->success()
                ->send();

            $this->redirect($resources::getUrl('view', ['record' => $this->record->id]));
        }
    }
}
