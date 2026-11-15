<?php

namespace App\Providers;

use App\Services\Insights\InsightService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use ImranDevBD\LaravelAdminPanel\AdminPanel;
use ImranDevBD\LaravelAdminPanel\Navigation\NavigationGroup;
use ImranDevBD\LaravelAdminPanel\Navigation\NavigationItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::prependNamespace('admin-panel', resource_path('views/command'));

        View::composer('admin-panel::pages.dashboard', function ($view) {
            $view->with('stats', app(InsightService::class)->snapshot());
        });

        $this->app->booted(function () {
            if (! $this->app->bound(AdminPanel::class)) {
                return;
            }

            $panel = $this->app->make(AdminPanel::class)->getPanel('admin');
            $panel->brandName(config('factory.name'));
            $panel->theme('amber');
            $panel->navigation([
                NavigationItem::make('Command')->icon('layout-dashboard')->url($panel->url('/')),
                NavigationGroup::make('Factory')->items([
                    NavigationItem::make('Operations')->icon('factory')->url(url('/admin')),
                    NavigationItem::make('Shop floor')->icon('cog')->url(url('/admin/shop-floor')),
                    NavigationItem::make('Catalog')->icon('package')->url(url('/catalog')),
                    NavigationItem::make('Blueprint studio')->icon('layers')->url(url('/blueprint-studio')),
                    NavigationItem::make('Web shell')->icon('terminal')->url(url('/webshell')),
                ]),
            ]);
        });
    }
}
