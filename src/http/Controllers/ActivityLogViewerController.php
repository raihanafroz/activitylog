<?php

namespace RAST\ActivityLog\http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class ActivityLogViewerController extends Controller
{
  public function index(Request $request) {
    $date = $request->get('date', date('Y-m-d'));
    $search = $request->get('search', null);
    $action = $request->get('action', null);

    $path = storage_path("logs/activity/activity-{$date}.log");
    $logs = [];

    if (File::exists($path)) {
      foreach (File::lines($path) as $line) {
        if (empty(trim($line))) continue;
        preg_match('/\{.*\}/', $line, $matches);
        if (isset($matches[0])) {
          $entry = json_decode($matches[0], true);

          // Filter by search term
          if ($search) {
            $found = false;
            foreach (['action', 'model', 'user_id', 'id'] as $key) {
              if (isset($entry[$key]) && str_contains(strtolower((string)$entry[$key]), strtolower($search))) {
                $found = true;
                break;
              }
            }
            if (!$found) continue;
          }

          // Filter by action type
          if ($action && isset($entry['action']) && strtolower($entry['action']) !== strtolower($action)) {
            continue;
          }

          $logs[] = $entry;
        }
      }
    }

    return view('activity-log::index', compact('logs', 'date', 'search', 'action'));
  }
}
