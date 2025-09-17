<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Navigation\NavigationGroup;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use App\Filament\Widgets\RckInfoWidget;
use Illuminate\Support\Facades\Schema;

class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        $brandName = config('app.name');
        $brandLogo = null;
        $favicon = null;
        if (!app()->runningInConsole() && Schema::hasTable('site_settings')) {
            if ($settings = SiteSetting::first()) {
                $brandName = $settings->site_name ?? $brandName;
                $brandLogo = $settings->logo_path
                    ? Storage::url($settings->logo_path)
                    : null;
                $favicon = $settings->favicon_path
                    ? Storage::url($settings->favicon_path)
                    : null;
            }
        }
        return $panel
            ->authGuard('web')
            ->brandName($brandName)
            ->brandLogo($brandLogo)
            ->favicon($favicon)
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,   // “Welcome / Sign out” widget
                RckInfoWidget::class,   // widget custom pengganti Filament info
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
            ->spa()
            ->profile()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Post')
                    ->collapsed(false),

                NavigationGroup::make()
                    ->label('Pengaturan')
                    ->collapsed(false),

                NavigationGroup::make()
                    ->label('Akun')
                    ->collapsed(false),
            ]);
        ;

    }
}
