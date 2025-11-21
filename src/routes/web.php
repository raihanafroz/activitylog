<?php

use Illuminate\Support\Facades\Route;
use RAST\ActivityLog\http\Controllers\ActivityLogViewerController;

Route::get('/', [ActivityLogViewerController::class, 'index'])
  ->name('rast.activitylog.index');
