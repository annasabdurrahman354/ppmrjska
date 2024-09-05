<?php

namespace App\Filament\Pages\Setting;

use App\Settings\Admin\PengaturanInformasiPondok;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use function Filament\Support\is_app_url;

class ManageInformasiPondok extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = PengaturanInformasiPondok::class;

    protected static ?int $navigationSort = -2;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

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

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pondok')
                    ->label('Informasi Pondok')
                    ->description('Mengelola kontak informasi pondok.')
                    ->icon('fluentui-phone-24-o')
                    ->schema([
                        TextInput::make('alamat')
                            ->label('Alamat Pondok')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Pondok')
                            ->email()
                            ->required(),
                        TableRepeater::make('narahubung')
                            ->label('Narahubung')
                            ->headers([
                                Header::make('Nama'),
                                Header::make('Nomor Telepon'),
                            ])
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama')
                                    ->required(),
                                TextInput::make('nomor_telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required(),
                            ])
                            ->columns(2),
                        TableRepeater::make('penerima_tamu')
                            ->label('Penerima Tamu')
                            ->headers([
                                Header::make('Nama'),
                                Header::make('Nomor Telepon'),
                            ])
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama')
                                    ->required(),
                                TextInput::make('nomor_telepon')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->required(),
                            ])
                            ->columns(2)
                    ]),
            ])
            ->columns(3)
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

            Notification::make()
                ->title('Pengaturan telah diperbarui.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
            Notification::make()
                ->title('Gagal untuk memperbarui pengaturan!')
                ->danger()
                ->send();
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return 'Informasi Pondok';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Informasi Pondok';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pengaturan Informasi Pondok';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Mengelola konfigurasi informasi pondok.';
    }
}
