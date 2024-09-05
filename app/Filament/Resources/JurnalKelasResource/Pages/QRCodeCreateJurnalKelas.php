<?php

namespace App\Filament\Resources\JurnalKelasResource\Pages;

use App\Enums\JenisKelamin;
use App\Enums\Sesi;
use App\Enums\StatusKehadiran;
use App\Enums\StatusPondok;
use App\Filament\Resources\JurnalKelasResource;
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

    protected static string $view = 'filament.resources.jurnal-kelas-resource.pages.qr-code';

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
            ->schema(JurnalKelas::getForm(false))
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
            $scannedUser = User::where('nis', $nis)->first();

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
