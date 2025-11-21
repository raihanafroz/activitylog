<?php

namespace RAST\ActivityLog\Traits;

use RAST\ActivityLog\Facades\ActivityLog;

trait LogsActivity
{
  public function logActivity(string $action, array $extra = [])
  {
    $message = sprintf("%s performed %s on %s", auth()->user()->name ?? 'System', $action, get_class($this));
    ActivityLog::log($message, $extra);
  }
}
