# Frontend Integration Guide

## Overview

Base Admin sử dụng Blade + Alpine.js + Tailwind CSS v4. Hướng dẫn tích hợp frontend.

---

## Layout Structure

```blade
<!-- layouts/app.blade.php -->
@extends('layouts.app')

@section('content')
    <!-- Page content -->
@endsection
```

### Available Components

```blade
{{-- Header --}}
<x-header.user-dropdown />
<x-header.notification-dropdown />

{{-- Sidebar --}}
@include('layouts.sidebar')

{{-- UI Components --}}
<x-ui.alert type="success" message="Success!" />
<x-ui.avatar src="{{ $user->avatar_url }}" />
<x-ui.badge type="primary" text="New" />
<x-ui.button type="primary" text="Submit" />
<x-ui.modal id="myModal" title="Modal Title" />

{{-- Form --}}
<x-form.date-picker name="date" />
<x-form.input-group label="Email" type="email" />
<x-form.select-input :options="$options" />
<x-form.toggle-switch name="active" />

{{-- Tables --}}
<x-tables.basic-one :data="$data" />
```

---

## Authentication Flow

### 1. Login Page

```blade
<!-- resources/views/pages/auth/signin.blade.php -->
<form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <span class="text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required>
        @error('password')
            <span class="text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label>
            <input type="checkbox" name="remember"> Remember me
        </label>
    </div>
    <button type="submit">Login</button>
</form>
```

### 2. Protected Routes

```php
// routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    // All admin routes here
});
```

### 3. User Menu

```blade
<!-- layouts/app-header.blade.php -->
<div class="dropdown">
    <button class="dropdown-toggle">
        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
        {{ auth()->user()->name }}
    </button>
    <div class="dropdown-menu">
        <a href="{{ route('profile') }}">Profile</a>
        <a href="{{ route('sessions') }}">Sessions</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>
```

---

## Permission-Based UI

### Show/Hide Elements

```blade
@can('users.create')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Create User
    </a>
@endcan

@cannot('users.delete')
    <span class="text-red-500">No permission</span>
@endcannot
```

### Navigation Menu

```blade
<!-- layouts/sidebar.blade.php -->
@if(auth()->user()->can('users.view'))
    <li><a href="{{ route('users.index') }}">Users</a></li>
@endif

@if(auth()->user()->can('settings.view'))
    <li><a href="{{ route('settings.index') }}">Settings</a></li>
@endif

@if(auth()->user()->can('audit_logs.view'))
    <li><a href="{{ route('audit.index') }}">Audit Logs</a></li>
@endif
```

### Table Actions

```blade
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>
                @can('users.view')
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm">
                        View
                    </a>
                @endcan
                @can('users.update')
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm">
                        Edit
                    </a>
                @endcan
                @can('users.delete')
                    <form method="POST" action="{{ route('users.destroy', $user) }}" 
                          onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

---

## API Integration

### JavaScript API Client

```javascript
// resources/js/api.js
const API = {
    token: localStorage.getItem('token'),

    async request(method, url, data = null) {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        };

        if (this.token) {
            options.headers['Authorization'] = `Bearer ${this.token}`;
        }

        if (data) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(`/api${url}`, options);
        return response.json();
    },

    // Auth
    login(email, password) {
        return this.request('POST', '/auth/login', { email, password });
    },

    logout() {
        return this.request('POST', '/auth/logout');
    },

    profile() {
        return this.request('GET', '/auth/profile');
    },

    // Users
    getUsers(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('GET', `/users?${query}`);
    },

    getUser(id) {
        return this.request('GET', `/users/${id}`);
    },

    createUser(data) {
        return this.request('POST', '/users', data);
    },

    updateUser(id, data) {
        return this.request('PUT', `/users/${id}`, data);
    },

    deleteUser(id) {
        return this.request('DELETE', `/users/${id}`);
    },

    // Settings
    getPublicSettings() {
        return this.request('GET', '/settings');
    },

    // Media
    uploadMedia(file, folderId = null) {
        const formData = new FormData();
        formData.append('file', file);
        if (folderId) {
            formData.append('folder_id', folderId);
        }

        return fetch('/api/media/upload', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${this.token}`,
            },
            body: formData
        }).then(r => r.json());
    },

    searchMedia(query, type = null) {
        const params = { q: query };
        if (type) params.type = type;
        const queryStr = new URLSearchParams(params).toString();
        return this.request('GET', `/media/search?${queryStr}`);
    }
};

export default API;
```

### Alpine.js Component

```html
<!-- User List Component -->
<div x-data="userList()" x-init="loadUsers()">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Users</h1>
        @can('users.create')
            <button @click="showCreateModal = true" class="btn btn-primary">
                Create User
            </button>
        @endcan
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input type="text" x-model="search" @input.debounce.300ms="loadUsers()"
               placeholder="Search users..." class="form-input">
    </div>

    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="user in users" :key="user.id">
                <tr>
                    <td x-text="user.name"></td>
                    <td x-text="user.email"></td>
                    <td>
                        <span :class="'badge badge-' + user.status" 
                              x-text="user.status"></span>
                    </td>
                    <td x-text="user.roles.map(r => r.name).join(', ')"></td>
                    <td>
                        <a :href="`/users/${user.id}`" class="btn btn-sm">View</a>
                        <button @click="editUser(user)" class="btn btn-sm">Edit</button>
                        <button @click="deleteUser(user.id)" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        <button @click="loadUsers(pagination.prev_page_url)" 
                :disabled="!pagination.prev_page_url">
            Previous
        </button>
        <span x-text="`Page ${pagination.current_page} of ${pagination.last_page}`"></span>
        <button @click="loadUsers(pagination.next_page_url)" 
                :disabled="!pagination.next_page_url">
            Next
        </button>
    </div>
</div>

<script>
function userList() {
    return {
        users: [],
        search: '',
        pagination: {},
        showCreateModal: false,

        async loadUsers(url = '/api/users') {
            if (this.search) {
                url += `?search=${this.search}`;
            }
            const response = await API.request('GET', url.replace('/api', ''));
            this.users = response.data.data;
            this.pagination = response.data;
        },

        async deleteUser(id) {
            if (!confirm('Are you sure?')) return;
            await API.deleteUser(id);
            this.loadUsers();
        },

        editUser(user) {
            // Open edit modal
        }
    }
}
</script>
```

---

## Feature Flag Integration

```blade
@if(feature_flag('new_dashboard'))
    @include('components.new-dashboard')
@else
    @include('components.old-dashboard')
@endif

@if(feature_flag('ai_features', auth()->user()))
    @include('components.ai-features')
@endif
```

```javascript
// Check feature flag in JS
const response = await API.request('GET', '/settings');
const settings = response.data;

if (settings.ai_features_enabled) {
    // Show AI features
}
```

---

## Settings Integration

```blade
<!-- Display settings -->
<p>Support Email: {{ setting('support_email') }}</p>
<p>Timezone: {{ setting('timezone', 'UTC') }}</p>
<p>Currency: {{ setting('default_currency', 'VND') }}</p>

<!-- Check maintenance mode -->
@if(setting('maintenance_mode'))
    <div class="alert alert-warning">
        {{ setting('maintenance_message', 'System under maintenance.') }}
    </div>
@endif
```

---

## Notification Integration

```blade
<!-- Notification Bell -->
<div x-data="{ open: false, count: 0 }" x-init="
    count = await fetch('/api/notifications/count').then(r => r.json())
">
    <button @click="open = !open">
        🔔
        <span x-show="count > 0" class="badge" x-text="count"></span>
    </button>
    
    <div x-show="open" @click.away="open = false">
        <!-- Notification list -->
    </div>
</div>
```

---

## Media Upload Component

```html
<div x-data="mediaUpload()">
    <input type="file" @change="handleFile($event)" accept="image/*">
    
    <div x-show="uploading" class="progress">
        <div class="progress-bar" :style="`width: ${progress}%`"></div>
    </div>
    
    <div x-show="uploaded">
        <img :src="imageUrl" alt="Uploaded">
        <button @click="removeImage()">Remove</button>
    </div>
</div>

<script>
function mediaUpload() {
    return {
        uploading: false,
        uploaded: false,
        progress: 0,
        imageUrl: null,

        async handleFile(event) {
            const file = event.target.files[0];
            this.uploading = true;
            
            const formData = new FormData();
            formData.append('file', file);
            
            const response = await fetch('/api/media/upload', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: formData
            });
            
            const data = await response.json();
            this.imageUrl = data.data.url;
            this.uploaded = true;
            this.uploading = false;
        },

        removeImage() {
            this.uploaded = false;
            this.imageUrl = null;
        }
    }
}
</script>
```

---

## Error Handling

```javascript
// Global error handler
async function apiRequest(method, url, data = null) {
    try {
        const response = await API.request(method, url, data);
        
        if (response.error) {
            showToast(response.error, 'error');
            return null;
        }
        
        return response;
    } catch (error) {
        if (error.status === 401) {
            // Unauthorized - redirect to login
            window.location.href = '/signin';
        } else if (error.status === 403) {
            showToast('You do not have permission', 'error');
        } else if (error.status === 422) {
            // Validation error
            const errors = error.data.errors;
            Object.keys(errors).forEach(key => {
                showToast(errors[key][0], 'error');
            });
        } else {
            showToast('An error occurred', 'error');
        }
        return null;
    }
}
```

---

## CSRF Protection

```blade
<!-- In layout -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- In JavaScript -->
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
});
```

---

## Real-time Updates (Optional)

```javascript
// Using Laravel Echo with WebSocket
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true
});

// Listen for notifications
Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        showToast(notification.message);
        updateNotificationCount();
    });

// Listen for broadcast events
Echo.channel('orders')
    .listen('OrderCreated', (e) => {
        addOrderToTable(e.order);
    });
```
