<?php

namespace RAST\ActivityLog;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/activitylog.php' => config_path('activitylog.php'),
      ], 'activitylog-config');
    }



    // Load views from package
    $this->loadViewsFrom(__DIR__.'/resources/views', 'activity-log');

    // Register routes
    $this->registerRoutes();
  }

  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/activitylog.php', 'activitylog');

    $this->app->singleton('activitylog', function () {
      return new RastActivityLog();
    });
  }


  protected function registerRoutes()
  {
    Route::middleware('web')
      ->prefix('activity-log')
      ->group(__DIR__ . '/routes/web.php');
  }
}
