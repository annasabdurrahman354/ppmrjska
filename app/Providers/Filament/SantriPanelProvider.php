<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\JadwalMunaqosahResource;
use App\Filament\Resources\JurnalKelasResource;
use App\Filament\Resources\MateriMunaqosahResource;
use App\Filament\Resources\PendaftaranResource;
use App\Settings\Admin\PengaturanUmum;
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

class SantriPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('santri')
            ->path('santri')
            ->login(Login::class)
            ->font('Inter', provider: SpatieGoogleFontProvider::class)
            ->favicon(asset('storage/sites/favicon.ico'))
            ->brandName(fn (PengaturanUmum $settings) => $settings->brand_name)
            ->brandLogo(asset('storage/sites/logo.png'))
            ->brandLogoHeight(fn (PengaturanUmum $settings) => $settings->brand_logoHeight)
            ->colors(fn (PengaturanUmum $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->resources([
                JurnalKelasResource::class,
                MateriMunaqosahResource::class,
                JadwalMunaqosahResource::class,
                PendaftaranResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
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
                QuickCreatePlugin::make()
                    ->includes([
                        JurnalKelasResource::class
                    ]),
                BreezyCore::make()
                    ->myProfile(
                        navigationGroup: 'Pengaturan',
                        hasAvatars: true,
                        slug: 'profile'
                    ),
                    //->myProfileComponents([
                    //    'personal_info' => Profile::class,
                    //]),
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
            ])
            ->unsavedChangesAlerts();
    }
}
