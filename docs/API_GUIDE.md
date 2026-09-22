# Base Admin Platform — API & Developer Guide

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Authentication](#authentication)
4. [RBAC (Role & Permission)](#rbac)
5. [API Endpoints](#api-endpoints)
6. [Helpers](#helpers)
7. [Middleware](#middleware)
8. [Services](#services)
9. [Models](#models)
10. [Adding New Modules](#adding-new-modules)
11. [Frontend Integration](#frontend-integration)
12. [Deployment](#deployment)

---

## Overview

Base Admin Platform là hệ điều hành Backend cho hệ thống TINOTECH. Cung cấp:

- Authentication (Login, 2FA, Session Management)
- RBAC (Role-Based Access Control)
- Audit Logging
- System Settings
- Feature Flags
- Media Library
- Notifications
- API Platform
- Security Monitoring

---

## Architecture

```
app/
├── Core/                    # Core modules
│   ├── Auth/               # Authentication
│   ├── User/               # User Management
│   ├── Role/               # Role Management
│   ├── Permission/         # Permission Management
│   ├── Audit/              # Audit Logging
│   ├── Setting/            # System Settings
│   ├── Media/              # Media Library
│   ├── Notification/       # Notifications
│   ├── FeatureFlag/        # Feature Flags
│   ├── Security/           # Security Monitoring
│   ├── Developer/          # API Keys, Webhooks
│   ├── Monitoring/         # Error Logs, Health
│   └── Dashboard/          # Dashboard
├── Models/                 # Main models (User)
├── Http/
│   ├── Controllers/        # Base controllers
│   └── Middleware/          # Custom middleware
├── Support/
│   ├── Traits/             # Reusable traits
│   ├── Enums/              # Enums
│   └── helpers.php         # Global helpers
```

---

## Authentication

### Login

```php
// POST /signin
// Request:
{
    "email": "admin@tinotech.vn",
    "password": "password",
    "remember": true
}

// Response:
{
    "success": true,
    "data": {
        "user": { ... },
        "token": "1|abc123..."
    }
}
```

### API Authentication (Sanctum)

```php
// POST /api/auth/login
// Request:
{
    "email": "admin@tinotech.vn",
    "password": "password"
}

// Response:
{
    "success": true,
    "data": {
        "user": { ... },
        "token": "1|abc123..."
    }
}

// Protected API calls:
Authorization: Bearer {token}
```

### Change Password

```php
// POST /change-password (Web) or POST /api/auth/change-password (API)
// Request:
{
    "current_password": "old-password",
    "password": "new-password",
    "password_confirmation": "new-password"
}
```

### Two-Factor Authentication

```php
// Enable 2FA
POST /2fa/enable
// Response: { "secret": "XXXXXX", "qr_code": "data:image/..." }

// Confirm 2FA
POST /2fa/confirm
{ "code": "123456" }

// Disable 2FA
POST /2fa/disable
```

### Session Management

```php
// List sessions
GET /sessions

// Revoke a session
DELETE /sessions/{sessionId}

// Logout all sessions
POST /logout-all
```

---

## RBAC

### Default Roles

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

### Permission Format

```
resource.action
```

Examples:
- `users.view` — View users
- `users.create` — Create users
- `users.update` — Update users
- `users.delete` — Delete users
- `settings.view` — View settings
- `settings.update` — Update settings
- `audit_logs.view` — View audit logs
- `audit_logs.export` — Export audit logs

### Check Permissions in Code

```php
// Check single permission
$user->can('users.create');

// Check multiple permissions
$user->can(['users.create', 'users.update']);

// Check role
$user->hasRole('admin');
$user->hasAnyRole(['admin', 'manager']);
$user->hasAllRoles(['admin', 'finance']);

// Check via Gate
Gate::allows('users.create');

// In Blade
@can('users.create')
    <button>Create User</button>
@endcan

@role('admin')
    <div>Admin only content</div>
@endrole
```

### Assign Roles & Permissions

```php
// Assign role
$user->assignRole('admin');
$user->assignRole(['admin', 'manager']);

// Remove role
$user->removeRole('admin');

// Sync roles
$user->syncRoles(['admin', 'manager']);

// Give permission
$user->givePermissionTo('users.create');
$user->givePermissionTo(['users.create', 'users.update']);

// Revoke permission
$user->revokePermissionTo('users.create');
```

### Create New Permission

```php
use Spatie\Permission\Models\Permission;

Permission::create(['name' => 'products.view', 'guard_name' => 'web']);
Permission::create(['name' => 'products.create', 'guard_name' => 'web']);
Permission::create(['name' => 'products.update', 'guard_name' => 'web']);
Permission::create(['name' => 'products.delete', 'guard_name' => 'web']);
```

---

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout |
| POST | `/api/auth/logout-all` | Logout all sessions |
| GET | `/api/auth/profile` | Get profile |
| POST | `/api/auth/change-password` | Change password |

### Users

| Method | Endpoint | Description | Permission |
|--------|----------|-------------|------------|
| GET | `/api/users` | List users | `users.view` |
| POST | `/api/users` | Create user | `users.create` |
| GET | `/api/users/{id}` | Get user | `users.view` |
| PUT | `/api/users/{id}` | Update user | `users.update` |
| DELETE | `/api/users/{id}` | Delete user | `users.delete` |
| POST | `/api/users/bulk-action` | Bulk actions | `users.update` |
| GET | `/api/users/export` | Export users | `users.export` |

### Settings

| Method | Endpoint | Description | Permission |
|--------|----------|-------------|------------|
| GET | `/api/settings` | Get public settings | Public |

### Media

| Method | Endpoint | Description | Permission |
|--------|----------|-------------|------------|
| POST | `/api/media/upload` | Upload file | `media.create` |
| GET | `/api/media/search` | Search media | `media.view` |
| GET | `/api/media/picker` | Media picker | `media.view` |

---

## Helpers

### setting()

```php
// Get setting value
$value = setting('site_name');
$value = setting('timezone', 'Asia/Ho_Chi_Minh'); // with default

// Set setting value
setting()->set('site_name', 'My App');
```

### feature_flag()

```php
// Check feature flag
if (feature_flag('new_dashboard')) {
    // Show new dashboard
}

// Check for specific user
if (feature_flag('ai_features', $user)) {
    // Show AI features
}
```

---

## Middleware

| Middleware | Description |
|------------|-------------|
| `auth` | Laravel default auth |
| `admin` | Check user is active |
| `role:admin,manager` | Check user has role |
| `permission:users.create` | Check user has permission |
| `audit` | Log audit trail |
| `request-id` | Add Request ID to response |
| `maintenance` | Check maintenance mode |

### Usage in Routes

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

Route::middleware(['auth', 'permission:users.view'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});

Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/admin-only', [AdminController::class, 'index']);
});
```

---

## Services

### AuthService

```php
use App\Core\Auth\Services\AuthService;

$auth = app(AuthService::class);

// Login
$result = $auth->login($credentials, $ip, $userAgent);

// Logout
$auth->logout($user);
$auth->logoutAllSessions($user);

// Password
$auth->changePassword($user, $currentPassword, $newPassword);
$auth->resetPassword($user, $newPassword);

// 2FA
$result = $auth->enableTwoFactor($user);
$auth->disableTwoFactor($user);
$isValid = $auth->verifyTwoFactor($user, $code);
```

### SettingService

```php
use App\Core\Setting\Services\SettingService;

$settings = app(SettingService::class);

$value = $settings->get('site_name');
$settings->set('site_name', 'New Name');
$groupSettings = $settings->getGroup('general');
$settings->setGroup('security', ['max_login_attempts' => 5]);
$settings->clearCache();
```

### FeatureFlagService

```php
use App\Core\FeatureFlag\Services\FeatureFlagService;

$flags = app(FeatureFlagService::class);

$isEnabled = $flags->isEnabled('new_dashboard');
$isEnabled = $flags->isEnabled('ai_features', $user);
$flags->enable('new_dashboard');
$flags->disable('ai_features');
$flags->setRollout('new_booking_flow', 50);
```

### AuditService

```php
use App\Core\Audit\Services\AuditService;

$audit = app(AuditService::class);

$audit->log('User exported', null, 'default', ['event' => 'export']);
$audit->logCreate($user, $user->toArray());
$audit->logUpdate($user, $old, $new);
$audit->logDelete($user);
$audit->logPermissionChange($user, 'granted', ['permission' => 'users.create']);
$audit->logRoleChange($user, 'assigned', ['role' => 'admin']);

$activities = $audit->getRecentActivities(50);
$activities = $audit->getActivitiesForSubject($user);
$activities = $audit->getActivitiesByUser($userId);
```

### MediaService

```php
use App\Core\Media\Services\MediaService;

$media = app(MediaService::class);

$result = $media->upload($file, $folderId, $userId);
$result = $media->uploadImage($file, $folderId, $userId, [300, 600, 1200]);
$media->delete($mediaModel);
$url = $media->getUrl($mediaModel);
$url = $media->getSignedUrl($mediaModel, 60);
$contents = $media->getFolderContents($folderId);
$folder = $media->createFolder('Images', $parentId, $userId);
$results = $media->search('logo', 'image');
```

### NotificationService

```php
use App\Core\Notification\Services\NotificationService;

$notification = app(NotificationService::class);

$notification->sendInApp($user, 'Title', 'Body', '/url');
$notification->sendFromTemplate('order_created', $user, $data);
$notification->sendBulkInApp($userIds, 'Title', 'Body');
$count = $notification->getUnreadCount($user);
$notification->markAsRead($user, $notificationId);
$notification->markAllAsRead($user);
```

### SecurityService

```php
use App\Core\Security\Services\SecurityService;

$security = app(SecurityService::class);

$failedLogins = $security->getFailedLogins(24);
$security->blockIP('1.2.3.4', 60);
$security->unblockIP('1.2.3.4');
$isBlocked = $security->isIPBlocked('1.2.3.4');
$suspicious = $security->getSuspiciousActivity();
$summary = $security->getSecuritySummary();
```

---

## Models

### User

```php
// Fields
$user->id;
$user->name;
$user->email;
$user->phone;
$user->avatar;
$user->status; // active, inactive, suspended
$user->two_factor_enabled;
$user->last_login_at;
$user->last_login_ip;
$user->created_at;
$user->updated_at;
$user->deleted_at; // soft delete

// Relationships
$user->roles;
$user->permissions;
$user->loginHistory;
$user->apiKeys;
$user->notifications;

// Methods
$user->isActive();
$user->isSuspended();
$user->avatar_url; // accessor
```

### Setting

```php
$setting->id;
$setting->key;
$setting->value;
$setting->group; // general, email, sms, security, maintenance
$setting->type; // text, boolean, integer, float, json
$setting->description;
$setting->is_public;
```

### FeatureFlag

```php
$flag->id;
$flag->name;
$flag->key;
$flag->description;
$flag->enabled;
$flag->rollout_percentage; // 0-100
$flag->conditions;

$flag->isEnabled();
$flag->isAvailableFor($user);
```

### Media

```php
$media->id;
$media->name;
$media->file_name;
$media->mime_type;
$media->file_size;
$media->path;
$media->disk; // public, s3, local
$media->folder_id;
$media->meta;

$media->url; // accessor
$media->size_formatted; // accessor
$media->isImage();
$media->isVideo();
$media->isDocument();
```

---

## Adding New Modules

### 1. Create Migration

```php
// database/migrations/2026_09_15_000000_create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    $table->softDeletes();
});
```

### 2. Create Model

```php
// app/Core/Product/Models/Product.php
namespace App\Core\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\Traits\HasAudit;

class Product extends Model
{
    use SoftDeletes, HasAudit;

    protected $fillable = ['name', 'description', 'price', 'status'];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
```

### 3. Create Controller

```php
// app/Core/Product/Http/Controllers/ProductController.php
namespace App\Core\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Product\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['data' => $products]);
        }

        return view('pages.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $product], 201);
        }

        return redirect()->route('products.show', $product);
    }

    // ... show, update, destroy methods
}
```

### 4. Create Permissions

```php
// In RoleSeeder or new seeder
Permission::firstOrCreate(['name' => 'products.view', 'guard_name' => 'web']);
Permission::firstOrCreate(['name' => 'products.create', 'guard_name' => 'web']);
Permission::firstOrCreate(['name' => 'products.update', 'guard_name' => 'web']);
Permission::firstOrCreate(['name' => 'products.delete', 'guard_name' => 'web']);
```

### 5. Add Routes

```php
// routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)
        ->middleware('permission:products.view');
});
```

### 6. Create View

```blade
<!-- resources/views/pages/products/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    @can('products.create')
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Create Product
        </a>
    @endcan
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price) }}</td>
                <td>
                    <span class="badge badge-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                        {{ $product->status }}
                    </span>
                </td>
                <td>
                    @can('products.view')
                        <a href="{{ route('products.show', $product) }}">View</a>
                    @endcan
                    @can('products.update')
                        <a href="{{ route('products.edit', $product) }}">Edit</a>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

---

## Frontend Integration

### Alpine.js Integration

```html
<!-- Login Form -->
<form x-data="{ email: '', password: '', loading: false }"
      @submit.prevent="
          loading = true;
          fetch('/signin', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify({ email, password })
          })
          .then(r => r.json())
          .then(data => {
              if (data.success) {
                  window.location.href = '/';
              }
              loading = false;
          })
      ">
    <input type="email" x-model="email" placeholder="Email">
    <input type="password" x-model="password" placeholder="Password">
    <button type="submit" :disabled="loading">
        <span x-show="!loading">Login</span>
        <span x-show="loading">Loading...</span>
    </button>
</form>
```

### API Authentication Header

```javascript
// Set token in localStorage after login
localStorage.setItem('token', data.data.token);

// Use token for API calls
fetch('/api/users', {
    headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json'
    }
});
```

### Check Permissions in Blade

```blade
@can('users.create')
    <button class="btn btn-primary">Create User</button>
@endcan

@cannot('users.delete')
    <span class="text-red-500">You don't have permission to delete</span>
@endcannot

@role('admin')
    <div class="admin-panel">Admin only content</div>
@endrole
```

### Feature Flag Check

```blade
@if(feature_flag('new_dashboard'))
    @include('components.new-dashboard')
@else
    @include('components.old-dashboard')
@endif
```

### Settings

```blade
<p>Support: {{ setting('support_email') }}</p>
<p>Timezone: {{ setting('timezone', 'UTC') }}</p>
```

---

## Deployment

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=base_admin
DB_USERNAME=root
DB_PASSWORD=

# Cache & Queue
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database

# Activity Log
ACTIVITY_LOGGER_ENABLED=true
ACTIVITY_LOGGER_TABLE_NAME=activity_log

# App
APP_NAME="Base Admin"
APP_URL=http://localhost:8000
```

### Setup Commands

```bash
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

## Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# Run with coverage
php artisan test --coverage
```

---

## Troubleshooting

### Permission Denied

```php
// Check user permissions
$user = auth()->user();
dd($user->getAllPermissions()->pluck('name'));
dd($user->getRoles()->pluck('name'));
```

### Cache Issues

```bash
# Clear permission cache
php artisan cache:clear
php artisan permission:cache-reset

# Clear settings cache
php artisan tinker
>>> app(\App\Core\Setting\Services\SettingService::class)->clearCache();
```

### Migration Issues

```bash
# Fresh start
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```
