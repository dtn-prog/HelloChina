# Base Admin Platform

Nền tảng Admin cho hệ thống TINOTECH — dùng chung cho GoPlusAI, Ecoluck, GapMove, GoIMENU...

## Quick Start

### Yêu cầu

- PHP 8.2+
- PostgreSQL
- Composer
- Node.js 18+

### Cài đặt

```bash
# Clone project
git clone <repo-url>
cd helloChina

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

php artisan import:hanzi-full # import bộ từ điển hán tự
php artisan import:dictionary # import từ điển trung việt

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

## Documentation
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
