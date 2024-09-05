<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\CalonSantriResource;
use App\Filament\Resources\PendaftaranResource;
use App\Filament\Resources\PenilaianCalonSantriResource;
use App\Settings\Admin\PengaturanWebsite;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
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

class PPSBPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ppsb')
            ->path('ppsb')
            ->font('Inter', provider: SpatieGoogleFontProvider::class)
            ->favicon(asset('storage/sites/favicon.ico'))
            ->brandName(fn (PengaturanWebsite $settings) => $settings->brand_name)
            ->brandLogo(asset('storage/sites/logo.png'))
            ->brandLogoHeight(fn (PengaturanWebsite $settings) => $settings->brand_logoHeight)
            ->colors(fn (PengaturanWebsite $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->resources([
                PendaftaranResource::class,
                CalonSantriResource::class,
                PenilaianCalonSantriResource::class
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
                        PendaftaranResource::class
                    ]),
            ])
            ->unsavedChangesAlerts();
    }
}
