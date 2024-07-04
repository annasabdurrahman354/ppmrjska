<?php

namespace App\Livewire\Panel\Pages;

use App\Models\BiodataSantri;
use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use function Filament\Support\is_app_url;

class Profile extends MyProfileComponent
{
    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public $user;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->form->fill($data);
    }

    public function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function form(Form $form): Form
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
                    ]),
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function submit()
    {
        try {
            $data = $this->form->getState();

            if (isset($data['is_takmili'])){
                if ($data['is_takmili']) {
                    $data['kelas'] = 'takmili';
                } else {
                    $data['kelas'] = (string) $data['angkatan_pondok'];
                }
            }

            $this->handleRecordUpdate($this->getUser(), $data);

            Notification::make()
                ->title('Profile updated')
                ->success()
                ->send();

            $this->redirect('profile', navigate: FilamentView::hasSpaMode() && is_app_url('profile'));
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed to update.')
                ->danger()
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }

    public function render(): View
    {
        return view("filament.pages.profile");
    }
}
