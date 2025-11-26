<?php

namespace RAST\ActivityLog;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider {
  public function boot() {
    $this->cleanupOldLogs();

    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/activitylog.php' => config_path('activitylog.php'),
      ], 'activitylog-config');
    }


    // Load views from package
    $this->loadViewsFrom(__DIR__ . '/resources/views', 'activity-log');

    // Register routes
    $this->registerRoutes();
  }

  public function register() {
    $this->mergeConfigFrom(__DIR__ . '/../config/activitylog.php', 'activitylog');

    $this->app->singleton('activitylog', function () {
      return new RastActivityLog();
    });
  }


  protected function registerRoutes(): void {
    Route::middleware('web')
      ->prefix('activity-log')
      ->group(__DIR__ . '/routes/web.php');
  }

  protected function cleanupOldLogs():void {
    $days = config('activitylog.days', 30);

    //Log::debug("Cleaning up old logs older than $days days");

    // Get the log folder dynamically
    $logPath = config('logging.channels.activity.path');

    //Log::debug("Log path: $logPath");
    $directory = dirname($logPath);

    //Log::debug("Directory: $directory");

    if (!is_dir($directory)) {
      //Log::debug("Directory does not exist: $directory");
      return;
    }
    // Extract prefix from file name (activity.log → activity)
    $filename = basename($logPath);
    //Log::debug("Filename: $filename");
    $prefix = pathinfo($filename, PATHINFO_FILENAME);

    //Log::debug("Prefix: $prefix");

    foreach (glob($directory . '/' . $prefix . '-*.log') as $file) {
      //Log::debug("Checking file: $file");
      $modified = filemtime($file);

      if ($modified < now()->subDays($days)->timestamp) {
        @unlink($file);
      }
    }
  }


}
