<?php

namespace App\Providers;

use App\Settings\Admin\PengaturanWebsite;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Carbon\Carbon;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Table;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch->modalHeading('Panel');
            $panelSwitch->canSwitchPanels(true);
           // $panelSwitch->canSwitchPanels(fn (): bool => auth()->user()?->can('switch_panels'));
            $panelSwitch->renderHook('panels::global-search.after');
        });

        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading(__('panel.empty'))
                ->striped()
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->defaultSort('created_at', 'desc');
        });

        View::share('pengaturan_umum', app(PengaturanWebsite::class));

        FilamentAsset::register([
            //Js::make('html5-qrcode', 'https://unpkg.com/html5-qrcode')->loadedOnRequest(),
            //Js::make('qrcode-form', __DIR__ . '/../../resources/js/qrcode-form.js')->loadedOnRequest(),
        ]);
    }
}
