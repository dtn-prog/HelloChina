<?php

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
            ['field' => 'image', 'label' => 'Image', 'type' => 'image'],
            ['field' => 'name', 'label' => 'Name', 'type' => 'text', 'sortable' => true],
            ['field' => 'sku', 'label' => 'SKU', 'type' => 'text'],
            ['field' => 'price', 'label' => 'Price', 'type' => 'currency', 'sortable' => true],
            ['field' => 'stock', 'label' => 'Stock', 'type' => 'number', 'sortable' => true],
            ['field' => 'status', 'label' => 'Status', 'type' => 'badge', 'sortable' => true],
            ['field' => 'created_at', 'label' => 'Created', 'type' => 'datetime', 'sortable' => true],
        ];
    }

    public function fields(): array
    {
        return [
            [
                'field' => 'name',
                'label' => 'Product Name',
                'type' => 'text',
                'rules' => 'required|string|max:255',
                'col_span' => 8,
            ],
            [
                'field' => 'slug',
                'label' => 'Slug',
                'type' => 'text',
                'rules' => 'nullable|string|max:255|unique:products,slug',
                'col_span' => 4,
                'auto_generate' => 'name',
            ],
            [
                'field' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'rules' => 'nullable|string',
                'col_span' => 12,
            ],
            [
                'field' => 'price',
                'label' => 'Price',
                'type' => 'number',
                'rules' => 'required|numeric|min:0',
                'col_span' => 4,
            ],
            [
                'field' => 'sale_price',
                'label' => 'Sale Price',
                'type' => 'number',
                'rules' => 'nullable|numeric|min:0',
                'col_span' => 4,
            ],
            [
                'field' => 'sku',
                'label' => 'SKU',
                'type' => 'text',
                'rules' => 'nullable|string|max:50|unique:products,sku',
                'col_span' => 4,
            ],
            [
                'field' => 'stock',
                'label' => 'Stock',
                'type' => 'number',
                'rules' => 'required|integer|min:0',
                'col_span' => 4,
            ],
            [
                'field' => 'category',
                'label' => 'Category',
                'type' => 'select',
                'options' => [
                    'electronics' => 'Electronics',
                    'clothing' => 'Clothing',
                    'food' => 'Food',
                    'other' => 'Other',
                ],
                'rules' => 'nullable|string',
                'col_span' => 4,
            ],
            [
                'field' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
                'rules' => 'required|in:active,inactive',
                'col_span' => 4,
            ],
            [
                'field' => 'image',
                'label' => 'Image',
                'type' => 'file',
                'rules' => 'nullable|image|max:2048',
                'col_span' => 12,
            ],
        ];
    }

    public function filters(): array
    {
        return [
            [
                'field' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ],
            ],
            [
                'field' => 'category',
                'label' => 'Category',
                'type' => 'select',
                'options' => [
                    'electronics' => 'Electronics',
                    'clothing' => 'Clothing',
                    'food' => 'Food',
                    'other' => 'Other',
                ],
            ],
            [
                'field' => 'price',
                'label' => 'Price Range',
                'type' => 'number',
            ],
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function updateRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function searchable(): array
    {
        return ['name', 'sku', 'description', 'category'];
    }

    public function sortable(): array
    {
        return ['id', 'name', 'price', 'stock', 'status', 'created_at'];
    }

    public function exportColumns(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sku' => 'SKU',
            'price' => 'Price',
            'sale_price' => 'Sale Price',
            'stock' => 'Stock',
            'category' => 'Category',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public function permissionPrefix(): string
    {
        return 'products';
    }

    public function with(): array
    {
        return [];
    }

    public function query($query)
    {
        return $query;
    }

    public function actions(): array
    {
        return [
            'view' => ['label' => 'View', 'icon' => 'eye', 'permission' => 'view'],
            'edit' => ['label' => 'Edit', 'icon' => 'edit', 'permission' => 'update'],
            'delete' => ['label' => 'Delete', 'icon' => 'trash', 'permission' => 'delete', 'method' => 'delete', 'confirm' => true],
        ];
    }

    public function bulkActions(): array
    {
        return [
            'delete' => ['label' => 'Delete Selected', 'icon' => 'trash', 'permission' => 'delete'],
            'activate' => ['label' => 'Activate', 'icon' => 'check', 'permission' => 'update'],
            'deactivate' => ['label' => 'Deactivate', 'icon' => 'x', 'permission' => 'update'],
        ];
    }

    public function viewDir(): string
    {
        return 'pages.products';
    }
}
