<?php

namespace App\Filament\Pages\Setting;

use App\Enums\Semester;
use App\Models\TahunAjaran;
use App\Settings\Admin\PengaturanKurikulum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use function Filament\Support\is_app_url;

class ManageKurikulum extends SettingsPage
{
    use HasPageShield;

    protected static string $settings = PengaturanKurikulum::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        // dd($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Kurikulum')
                            ->label('Kurikulum')
                            ->icon('fluentui-calendar-settings-32-o')
                            ->schema([
                                Select::make('tahun_ajaran')
                                    ->label('Tahun Ajaran')
                                    ->options(TahunAjaran::all()->pluck('tahun_ajaran', 'tahun_ajaran'))
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm(TahunAjaran::getForm())
                                    ->required(),
                                ToggleButtons::make('semester')
                                    ->label('Semester Saat Ini')
                                    ->options(Semester::class)
                                    ->inline()
                                    ->grouped()
                                    ->grouped()
                            ])
                            ->columnSpanFull()
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            $this->sendSuccessNotification('Pengaturan telah diperbarui.');

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
            $this->sendErrorNotification('Gagal untuk memperbarui pengaturan!. '.$th->getMessage());
        }
    }

    public function sendSuccessNotification($title)
    {
        Notification::make()
                ->title($title)
                ->success()
                ->send();
    }

    public function sendErrorNotification($title)
    {
        Notification::make()
                ->title($title)
                ->error()
                ->send();
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return 'Kurikulum';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pengaturan Kurikulum';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pengaturan Kurikulum';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Mengelola konfigurasi kurikulum.';
    }
}
