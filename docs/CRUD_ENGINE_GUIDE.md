# Blade CRUD Engine — Developer Guide

## Overview

CRUD Engine giúp tạo nhanh CRUD UI cho bất kỳ module nào chỉ cần định nghĩa **Resource**.

---

## Quick Start

### 1. Tạo Resource

```php
// app/Core/Product/Resources/ProductResource.php
namespace App\Core\Product\Resources;

use App\Support\Contracts\CrudResource;
use App\Core\Product\Models\Product;

class ProductResource implements CrudResource
{
    public function model(): string
    {
        return Product::class;
    }

    public function title(): string
    {
        return 'Product';
    }

    public function routePrefix(): string
    {
        return 'products';
    }

    public function columns(): array
    {
        return [
            ['field' => 'id', 'label' => 'ID', 'type' => 'number', 'sortable' => true],
            ['field' => 'name', 'label' => 'Name', 'type' => 'text', 'sortable' => true],
            ['field' => 'price', 'label' => 'Price', 'type' => 'currency', 'sortable' => true],
            ['field' => 'status', 'label' => 'Status', 'type' => 'badge', 'sortable' => true],
        ];
    }

    public function fields(): array
    {
        return [
            ['field' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:255', 'col_span' => 8],
            ['field' => 'price', 'label' => 'Price', 'type' => 'number', 'rules' => 'required|numeric|min:0', 'col_span' => 4],
            ['field' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'rules' => 'required', 'col_span' => 4],
        ];
    }

    public function filters(): array
    {
        return [
            ['field' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Active', 'inactive' => 'Inactive']],
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function updateRules(): array
    {
        return $this->rules();
    }

    public function searchable(): array
    {
        return ['name'];
    }

    public function sortable(): array
    {
        return ['id', 'name', 'price', 'status'];
    }

    public function exportColumns(): array
    {
        return ['id' => 'ID', 'name' => 'Name', 'price' => 'Price', 'status' => 'Status'];
    }

    public function permissionPrefix(): string
    {
        return 'products';
    }

    public function with(): array { return []; }
    public function query($query) { return $query; }

    public function actions(): array
    {
        return [
            'view' => ['label' => 'View', 'permission' => 'view'],
            'edit' => ['label' => 'Edit', 'permission' => 'update'],
            'delete' => ['label' => 'Delete', 'permission' => 'delete', 'confirm' => true],
        ];
    }

    public function bulkActions(): array
    {
        return [
            'delete' => ['label' => 'Delete Selected', 'permission' => 'delete'],
            'activate' => ['label' => 'Activate', 'permission' => 'update'],
        ];
    }

    public function viewDir(): string
    {
        return 'pages.products';
    }
}
```

### 2. Tạo Controller

```php
// app/Core/Product/Http/Controllers/ProductController.php
namespace App\Core\Product\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Core\Product\Resources\ProductResource;
use App\Support\Contracts\CrudResource;

class ProductController extends CrudController
{
    protected function getResource(): CrudResource
    {
        return new ProductResource();
    }
}
```

### 3. Tạo Migration

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->decimal('price', 10, 2);
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    $table->softDeletes();
});
```

### 4. Thêm Routes

```php
// routes/web.php
Route::resource('products', ProductController::class)
    ->middleware('permission:products.view');
Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])
    ->name('products.bulk-action');
Route::get('/products/export', [ProductController::class, 'export'])
    ->name('products.export');
```

### 5. Tạo Permissions

```php
// database/seeders/RoleSeeder.php
'products' => ['view', 'create', 'update', 'delete', 'export', 'import'],
```

### 6. Tạo Views

```blade
{{-- resources/views/pages/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Products</h1>
    @can('products.create')
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Create Product</a>
    @endcan
</div>

<x-crud.filter-bar :filters="$resource->filters()" />
<x-crud.data-table :columns="$resource->columns()" :items="$items" :resource="$resource" />
<x-crud.bulk-actions :resource="$resource" />
@endsection
```

---

## Field Types

| Type | Description |
|------|-------------|
| `text` | Text input |
| `number` | Number input |
| `email` | Email input |
| `textarea` | Textarea |
| `select` | Dropdown select |
| `file` | File upload |
| `toggle` | Toggle switch |
| `checkbox` | Checkbox |
| `richtext` | Rich text editor (TinyMCE) |
| `date` | Date picker |
| `datetime` | Datetime display |
| `badge` | Status badge |
| `currency` | Formatted currency |
| `image` | Image thumbnail |

---

## Column Types

| Type | Description |
|------|-------------|
| `text` | Plain text |
| `number` | Number |
| `badge` | Status badge (green/yellow) |
| `currency` | Formatted currency (₫) |
| `datetime` | Formatted datetime |
| `image` | Image thumbnail |

---

## Filter Types

| Type | Description |
|------|-------------|
| `text` | Text search |
| `select` | Dropdown select |
| `date` | Date picker |
| `date_range` | Date range picker |
| `boolean` | Yes/No select |
| `number` | Number input |

---

## Blade Components

### DataTable

```blade
<x-crud.data-table 
    :columns="$resource->columns()" 
    :items="$items" 
    :resource="$resource"
    :selectable="true" />
```

### Filter Bar

```blade
<x-crud.filter-bar :filters="$resource->filters()" />
```

### Form

```blade
<x-crud.form 
    :fields="$resource->fields()" 
    :item="$item"
    :action="route('products.update', $item)"
    method="PUT"
    :resource="$resource" />
```

### Modal

```blade
<x-crud.modal id="myModal" title="Modal Title" size="lg">
    <p>Modal content</p>
    
    @slot('footer')
        <button @click="open = false">Cancel</button>
        <button @click="submit">Save</button>
    @endslot
</x-crud.modal>
```

### Bulk Actions

```blade
<x-crud.bulk-actions :resource="$resource" />
```

---

## Customizing Views

### Override View Directory

```php
// In Resource
public function viewDir(): string
{
    return 'pages.admin.products'; // Custom view directory
}
```

### Custom Actions

```php
// In Resource
public function actions(): array
{
    return [
        'view' => ['label' => 'View', 'permission' => 'view'],
        'edit' => ['label' => 'Edit', 'permission' => 'update'],
        'custom' => [
            'label' => 'Custom Action',
            'url' => fn ($item) => route('products.custom', $item),
            'permission' => 'custom',
            'class' => 'text-purple-600 hover:text-purple-900',
        ],
    ];
}
```

---

## Example: Creating a New Module

```bash
# 1. Create migration
php artisan make:migration create_orders_table

# 2. Create model
php artisan make:model Order -m

# 3. Create resource (manual)
# app/Core/Order/Resources/OrderResource.php

# 4. Create controller (manual)
# app/Core/Order/Http/Controllers/OrderController.php

# 5. Add routes
# routes/web.php

# 6. Add permissions
# database/seeders/RoleSeeder.php

# 7. Run migration
php artisan migrate

# 8. Seed permissions
php artisan db:seed --class=RoleSeeder
```

---

## API Response

All CRUD endpoints support JSON responses:

```bash
# List
GET /api/products
# Response: { "data": { "data": [...], "links": {...}, "meta": {...} } }

# Create
POST /api/products
# Request: { "name": "...", "price": 100 }
# Response: { "success": true, "data": {...} }

# Show
GET /api/products/1
# Response: { "data": {...} }

# Update
PUT /api/products/1
# Request: { "name": "Updated" }
# Response: { "success": true, "data": {...} }

# Delete
DELETE /api/products/1
# Response: { "success": true }
```
