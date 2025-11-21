<?php

namespace RAST\ActivityLog;

use Illuminate\Support\Facades\Log;

class RastActivityLog
{
  /**
   * Log an activity message to the configured channel
   *
   * @param string $message
   * @param array $context
   * @return void
   */
  public static function log(string $message, array $context = []): void
  {
    // Only log if enabled in config
    if (config('activitylog.enabled', false)) {
      $channel = config('activitylog.channel', 'activity');
      Log::channel($channel)->info($message, $context);
    }
  }
}
