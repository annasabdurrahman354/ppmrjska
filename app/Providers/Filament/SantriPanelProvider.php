<?php

namespace App\Providers\Filament;

use App\Filament\Pages\FormulirPresensi\FormulirPresensi;
use App\Filament\Pages\KetercapaianMateri\KetercapaianMateri;
use App\Filament\Pages\Munaqosah\Munaqosah;
use App\Filament\Pages\Profile;
use App\Filament\Pages\RekapKehadiran\RekapKehadiran;
use App\Filament\Resources\JadwalMunaqosahResource;
use App\Filament\Resources\JurnalKelasResource;
use App\Filament\Resources\KurikulumResource;
use App\Filament\Resources\MateriHafalanResource;
use App\Filament\Resources\MateriHimpunanResource;
use App\Filament\Resources\MateriJuzResource;
use App\Filament\Resources\MateriMunaqosahResource;
use App\Filament\Resources\MateriSuratResource;
use App\Filament\Resources\MateriTambahanResource;
use App\Filament\Resources\PenilaianMunaqosahResource;
use App\Settings\Admin\PengaturanWebsite;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\FontProviders\SpatieGoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class SantriPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('santri')
            ->path('santri')
            ->font('Inter', provider: SpatieGoogleFontProvider::class)
            ->favicon(asset('storage/sites/favicon.ico'))
            ->brandName(fn (PengaturanWebsite $settings) => $settings->brand_name)
            ->brandLogo(asset('storage/sites/logo.png'))
            ->brandLogoHeight(fn (PengaturanWebsite $settings) => $settings->brand_logoHeight)
            ->colors(fn (PengaturanWebsite $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->unsavedChangesAlerts()
            ->resources([
                JurnalKelasResource::class,
                MateriMunaqosahResource::class,
                JadwalMunaqosahResource::class,
                PenilaianMunaqosahResource::class,
                PenilaianMunaqosahResource::class,
                MateriHafalanResource::class,
                MateriHimpunanResource::class,
                MateriSuratResource::class,
                MateriJuzResource::class,
                MateriTambahanResource::class,
                KurikulumResource::class
            ])
            ->pages([
                Pages\Dashboard::class,
                FormulirPresensi::class,
                KetercapaianMateri::class,
                RekapKehadiran::class,
                Munaqosah::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    ->selectable()
                    ->editable()
                    ->timezone('Asia/Jakarta')
                    ->locale('id')
                    ->config(['initialView' => 'dayGridMonth',
                        'firstDay' => 1,
                        'headerToolbar' => [
                            'left' => 'prev,next,today',
                            'center' => 'title',
                            'right' => 'dayGridMonth,dayGridWeek,listWeek',
                        ]]),
                QuickCreatePlugin::make()
                    ->includes([
                        JurnalKelasResource::class
                    ]),
                BreezyCore::make()
                    ->myProfile(
                        navigationGroup: 'Pengaturan',
                        hasAvatars: true,
                        slug: 'profile'
                    )
                    ->customMyProfilePage(Profile::class),
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
    }
}
