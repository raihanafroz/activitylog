<?php

namespace RAST\ActivityLog\Traits;

use RAST\ActivityLog\RastActivityLog;

trait HasRastActivityLog {
  public static function bootHasRastActivityLog() {
    // CREATED → log full record
    static::created(function ($model) {
      RastActivityLog::log("Created: " . class_basename($model), [
        'action' => 'created',
        'model' => class_basename($model),
        'id' => $model->id,
        'data' => $model->toArray(),
        'user_id' => auth()->id() ?? null,
      ]);
    });

    // UPDATED → log only tracked + changed fields
    static::updated(function ($model) {
      $tracked = property_exists($model, 'logAttributes')
        ? $model->logAttributes
        : array_keys($model->getAttributes());

      $changes = [];
      foreach ($model->getDirty() as $field => $newValue) {
        if (!in_array($field, $tracked)) continue;
        $original = $model->getOriginal()[$field] ?? null;
        if ($newValue === $original) continue;

        $changes[$field] = ['old' => $original, 'new' => $newValue];
      }

      if (!empty($changes)) {
        RastActivityLog::log("Updated: " . class_basename($model), [
          'action' => 'updated',
          'model' => class_basename($model),
          'id' => $model->id,
          'changes' => $changes,
          'user_id' => auth()->id() ?? null,
        ]);
      }
    });

    // DELETING → log full record
    static::deleting(function ($model) {
      RastActivityLog::log("Deleted: " . class_basename($model), [
        'action' => 'deleted',
        'model' => class_basename($model),
        'id' => $model->id,
        'data' => $model->toArray(),
        'user_id' => auth()->id() ?? null,
      ]);
    });
  }
}
