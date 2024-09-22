<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use App\FredOS\Services\SeedsConfig;
use App\FredOS\Services\CacheService;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
    $this->app->singleton(SeedsConfig::class, function ($app) {
      return new SeedsConfig();
    });

    $this->app->bind('CacheService', CacheService::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
    CacheService::initializeContentModelsCache();
    FilamentAsset::register([
      Css::make('seeds-stylesheet', resource_path('css/seeds.css')),
    ]);
  }
}
