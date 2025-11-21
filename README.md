# **RAST Activity Log**

A lightweight Laravel package for logging model activities (create, update, delete) **into log files** with a built-in viewer UI.

[![Issues](https://img.shields.io/github/issues/raihanafroz/activitylog?style=flat-square)](https://github.com/raihanafroz/activitylog/issues)
[![Forks](https://img.shields.io/github/forks/raihanafroz/activitylog?style=flat-square)](https://github.com/raihanafroz/activitylog/network/members)
[![Stars](https://img.shields.io/github/stars/raihanafroz/activitylog?style=flat-square)](https://github.com/raihanafroz/activitylog/stargazers)
[![Total Downloads](https://img.shields.io/packagist/dt/rast/activity-log?style=flat-square)](https://packagist.org/packages/rast/activity-log)
[![License](https://poser.pugx.org/rast/activitylog/license.svg)](https://packagist.org/packages/rast/activitylog)
[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue?style=for-the-badge)]()
[![Laravel](https://img.shields.io/badge/Laravel-12+-red?style=for-the-badge)]()
[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge)]()
[![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)]()

---

# 📦 Features

* Logs **created**, **updated**, and **deleted** events.
* Detects **changed attributes only** (for update).
* Logs to dedicated **storage/logs/activity-YYYY-MM-DD.log**.
* Includes Laravel Blade **log viewer page**.
* Supports **filters** (date, action, search).
* Plug-and-play trait `HasRastActivityLog`.

---

# 🚀 Installation

```bash
composer require rast/activity-log
```

Laravel will automatically discover the service provider.

---

# ⚙️ Configuration (Optional)

Publish config:

```bash
php artisan vendor:publish --tag=activitylog-config
```

This publishes:

```
config/activitylog.php
```

Inside the file:

```php
return [
    'enabled' => true,
    'channel' => 'activity',
];
```

---

# 🧩 Usage

## 1. Add the Trait to Any Model

```php
use RAST\ActivityLog\Traits\HasRastActivityLog;

class Post extends Model
{
    use HasRastActivityLog;

    protected $fillable = ['title', 'content'];

    protected $logAttributes = ['title', 'content'];
}
```

For users:

```php
class User extends Authenticatable
{
    use HasRastActivityLog;

    protected $logAttributes = ['name', 'email'];
}
```

---

# 🧪 What Gets Logged?

### 1. **Created Event**

Writes full data.

### 2. **Updated Event**

Writes only **changed fields**.

### 3. **Deleted Event**

Writes full data.

Example log file:

```
storage/logs/activity-2025-11-21.log
```

---

# 🔍 View Logs in Browser

Visit:

```
/activity-log
```

Includes filters:

* Date
* Action (create/update/delete)
* Search (model name, user ID, record ID)

---

# 📁 Log Structure

Each log entry example:

```json
{
  "action": "updated",
  "model": "User",
  "id": 3,
  "changes": {
    "email": {
      "old": "old@mail.com",
      "new": "new@mail.com"
    }
  },
  "user_id": 1
}
```

---

# 📂 File Logging Channel

The package auto-registers a custom channel:

```php
'activity' => [
    'driver' => 'single',
    'path' => storage_path('logs/activity-' . date('Y-m-d') . '.log'),
    'level' => 'info',
],
```
---

# 📜 License

This package is open-sourced under the **MIT License**.
