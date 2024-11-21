<?php

namespace App\Filament\Pages\Setting;

use App\Settings\Admin\PengaturanWebsite;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use function Filament\Support\is_app_url;

class ManageWebsite extends SettingsPage
{
    use HasPageShield;
    protected static string $settings = PengaturanWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

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
                Section::make('Site')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->description(fn () => __('page.general_settings.sections.site.description'))
                    ->icon('fluentui-web-asset-24-o')
                    ->schema([
                        Grid::make()->schema([
                            TextInput::make('brand_name')
                                ->label(fn () => __('page.general_settings.fields.brand_name'))
                                ->required(),
                            Select::make('site_active')
                                ->label(fn () => __('page.general_settings.fields.site_active'))
                                ->options([
                                    0 => "Not Active",
                                    1 => "Active",
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                        Grid::make()->schema([
                            Grid::make()->schema([
                                TextInput::make('brand_logoHeight')
                                    ->label(fn () => __('page.general_settings.fields.brand_logoHeight'))
                                    ->required()
                                    ->columnSpan(2),
                                FileUpload::make('brand_logo')
                                    ->label(fn () => __('page.general_settings.fields.brand_logo'))
                                    ->directory('sites')
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => 'logo.png',
                                    )
                                    ->image()
                                    ->required()
                                    ->columnSpan(2),
                            ])
                            ->columnSpan(2),
                            FileUpload::make('site_favicon')
                                ->label(fn () => __('page.general_settings.fields.site_favicon'))
                                ->image()
                                ->directory('sites')
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file): string => 'favicon.ico',
                                )
                                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                                ->required(),
                        ])->columns(4),
                    ]),
                Section::make('Theme')
                    ->label(fn () => __('page.general_settings.sections.theme.title'))
                    ->description(fn () => __('page.general_settings.sections.theme.description'))
                    ->icon('fluentui-color-24-o')
                    ->schema([
                        ColorPicker::make('site_theme.primary')
                            ->label(fn () => __('page.general_settings.fields.primary'))->rgb(),
                        ColorPicker::make('site_theme.secondary')
                            ->label(fn () => __('page.general_settings.fields.secondary'))->rgb(),
                        ColorPicker::make('site_theme.gray')
                            ->label(fn () => __('page.general_settings.fields.gray'))->rgb(),
                        ColorPicker::make('site_theme.success')
                            ->label(fn () => __('page.general_settings.fields.success'))->rgb(),
                        ColorPicker::make('site_theme.danger')
                            ->label(fn () => __('page.general_settings.fields.danger'))->rgb(),
                        ColorPicker::make('site_theme.info')
                            ->label(fn () => __('page.general_settings.fields.info'))->rgb(),
                        ColorPicker::make('site_theme.warning')
                            ->label(fn () => __('page.general_settings.fields.warning'))->rgb(),
                    ])
                    ->columns(3),
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

            $data = $this->handleUpload($data);

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            Notification::make()
                ->title('Settings updated.')
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

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleUpload(array $data): array
    {
        $data['brand_logo'] = collect($data['brand_logo'])->first();
        if (!is_string($data['brand_logo'])) {
            $data['brand_logo'] = 'sites/logo.png';
        }

        $data['site_favicon'] = collect($data['site_favicon'])->first();
        if (!is_string($data['site_favicon'])) {
            $data['site_favicon'] = 'sites/favicon.ico';
        }

        return $data;
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.settings");
    }

    public static function getNavigationLabel(): string
    {
        return 'Website';
    }

    public function getTitle(): string|Htmlable
    {
        return 'Pengaturan Website';
    }

    public function getHeading(): string|Htmlable
    {
        return 'Pengaturan Website';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Mengelola konfigurasi website.';
    }
}
