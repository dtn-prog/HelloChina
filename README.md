# Base Admin Platform

Nền tảng Admin cho hệ thống TINOTECH — dùng chung cho GoPlusAI, Ecoluck, GapMove, GoIMENU...

## Quick Start

### Yêu cầu

- PHP 8.2+
- MySQL 8.0+ / PostgreSQL
- Composer
- Node.js 18+

### Cài đặt

```bash
# Clone project
git clone <repo-url>
cd base_adminbase_v2

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Storage
php artisan storage:link

# Build frontend
npm run dev

# Start server
php artisan serve
```

### Default Login

```
Email: admin@tinotech.vn
Password: password
```

---

## Features

### ✅ Đã hoàn thành

| Module | Mô tả |
|--------|-------|
| **Authentication** | Login, Logout, 2FA, Session Management, Login History |
| **RBAC** | Roles, Permissions, 8 roles, 48+ permissions |
| **User Management** | CRUD, Bulk Actions, Impersonation, Export |
| **Audit Logging** | Auto log CRUD, before/after, IP tracking |
| **System Settings** | Dynamic settings, grouped, cached |
| **Feature Flags** | Enable/disable features, rollout percentage |
| **Media Library** | Upload, folders, search, storage abstraction |
| **Notifications** | In-app notifications, templates |
| **Dashboard Widgets** | KPI, Activity, User Stats, System Health, Login History |
| **Developer Tools** | API Keys, Webhooks, Queue Monitor, Cron Monitor |
| **Monitoring** | Health Check, Error Logs, Server Monitor |
| **Export/Import** | CSV, JSON export with queue support |
| **Multi-language** | Vietnamese & English translations |
| **API Platform** | Sanctum auth, RESTful endpoints |

### 🔲 Đang phát triển

| Module | Mô tả |
|--------|-------|
| **Blade CRUD Engine** | Reusable DataTable, Form Builder |

---

## Architecture

```
app/
├── Core/                    # Core modules
│   ├── Auth/               # Authentication
│   ├── User/               # User Management
│   ├── Role/               # Role Management
│   ├── Setting/            # System Settings
│   ├── FeatureFlag/        # Feature Flags
│   ├── Audit/              # Audit Logging
│   ├── Media/              # Media Library
│   ├── Notification/       # Notifications
│   ├── Dashboard/          # Dashboard Widgets
│   ├── Developer/          # API Keys, Webhooks
│   ├── Monitoring/         # Health, Errors
│   ├── Export/             # Export/Import
│   └── Language/           # Multi-language
├── Models/                 # Main models
├── Http/Middleware/         # Custom middleware
├── Support/                # Traits, Enums, Helpers
```

---

## Documentation

| Document | Mô tả |
|----------|-------|
| [Implementation Plan](docs/IMPLEMENTATION_PLAN.md) | Kế hoạch triển khai chi tiết |
| [API Guide](docs/API_GUIDE.md) | Hướng dẫn API & Developer |
| [Frontend Guide](docs/FRONTEND_GUIDE.md) | Hướng dẫn tích hợp Frontend |
| [Task Tracker](docs/TASK_TRACKER.md) | Theo dõi tiến độ |

---

## API Endpoints

### Authentication

```
POST   /api/auth/login          Login
POST   /api/auth/logout         Logout
POST   /api/auth/logout-all     Logout all sessions
GET    /api/auth/profile        Get profile
POST   /api/auth/change-password Change password
```

### Users

```
GET    /api/users               List users
POST   /api/users               Create user
GET    /api/users/{id}          Get user
PUT    /api/users/{id}          Update user
DELETE /api/users/{id}          Delete user
POST   /api/users/bulk-action   Bulk actions
GET    /api/users/export        Export users
```

### Settings

```
GET    /api/settings            Get public settings
```

### Media

```
POST   /api/media/upload        Upload file
GET    /api/media/search        Search media
GET    /api/media/picker        Media picker
```

---

## Default Roles

| Role | Description |
|------|-------------|
| `super_admin` | Full system access |
| `admin` | Administrative access |
| `manager` | Management access |
| `finance` | Finance operations |
| `support` | Customer support |
| `editor` | Content editing |
| `developer` | Developer access |
| `viewer` | Read-only access |

---

## Permission Format

```
resource.action
```

Examples:
- `users.view` — View users
- `users.create` — Create users
- `settings.update` — Update settings
- `audit_logs.export` — Export audit logs
- `developer.api_keys` — Manage API keys

---

## Helpers

```php
// Get setting
$value = setting('site_name');
$value = setting('timezone', 'UTC');

// Check feature flag
if (feature_flag('new_dashboard')) {
    // Show new feature
}

if (feature_flag('ai_features', $user)) {
    // Show for specific user
}
```

---

## Middleware

```php
// Auth middleware
Route::middleware(['auth'])->group(function () { ... });

// Admin middleware (check active status)
Route::middleware(['auth', 'admin'])->group(function () { ... });

// Role middleware
Route::middleware(['auth', 'role:admin,manager'])->group(function () { ... });

// Permission middleware
Route::middleware(['auth', 'permission:users.view'])->group(function () { ... });

// Maintenance middleware
Route::middleware(['maintenance'])->group(function () { ... });

// Developer middleware
Route::middleware(['role:super_admin,developer'])->group(function () { ... });
```

---

## Dashboard Widgets

```php
// Register custom widget in DashboardServiceProvider
$service->register('my_widget', MyWidget::class);

// Widget must implement WidgetContract
class MyWidget implements WidgetContract
{
    public function getId(): string { return 'my_widget'; }
    public function getName(): string { return 'My Widget'; }
    public function getData(): array { return [...]; }
    public function getView(): string { return 'components.my-widget'; }
    public function canAccess($user): bool { return true; }
}
```

---

## Adding New Modules

Xem [API Guide — Adding New Modules](docs/API_GUIDE.md#adding-new-modules)

---

## Default Login

```
Email: admin@tinotech.vn
Password: password
```

---

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest
```

---

## Troubleshooting

```bash
# Clear cache
php artisan cache:clear
php artisan permission:cache-reset

# Fresh start
php artisan migrate:fresh --seed

# Check routes
php artisan route:list
```

---

## License

MIT License
