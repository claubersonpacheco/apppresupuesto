<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\SpatieLaravelTranslatablePlugin;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Facades\Filament;
use App\Models\Setting;

class DashboardPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        // Obtém o logo dinâmico da tabela Settings
        $setting = Setting::first();

        // Verifica se o logo existe
        $logo = null;
        if ($setting && $setting->logo) {
            $path = storage_path('app/public/' . $setting->logo);

            if (file_exists($path)) {
                $base64 = base64_encode(file_get_contents($path));
                $logo = 'data:image/png;base64,' . $base64;
            }
        }


        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::hex('#014bde'),
            ])
            ->brandLogo($logo)
            ->font('Nunito')
            ->viteTheme('resources/css/filament/dashboard/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
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
            ->renderHook('panels::head.start', function () {
                if (app()->isLocal()) {
                    // Em desenvolvimento, usa o hot file para o HMR (Hot Module Replacement)
                    return Vite::useHotFile('http://localhost:5173/hot')
                        ->useBuildDirectory('build/')
                        ->withEntryPoints(['resources/js/app.js', 'resources/css/app.css'])
                        ->toHtml();
                }

                // Em produção, usa o arquivo de manifesto
                return Vite::useManifestFile(public_path('build/.vite/manifest.json'))
                    ->useBuildDirectory('build/')
                    ->withEntryPoints(['resources/js/app.js', 'resources/css/app.css'])
                    ->toHtml();
            })
            ->plugin(SpatieLaravelTranslatablePlugin::make()
                ->defaultLocales(['en', 'es']));
    }


}
