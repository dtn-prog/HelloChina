<?php

namespace App\Support\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface CrudResource
{
    /**
     * Get the model class name
     */
    public function model(): string;

    /**
     * Get the resource title (e.g., "Product", "User")
     */
    public function title(): string;

    /**
     * Get the route prefix (e.g., "products")
     */
    public function routePrefix(): string;

    /**
     * Define the columns for the index/table
     * Each column: ['field' => 'name', 'label' => 'Name', 'type' => 'text', 'sortable' => true]
     */
    public function columns(): array;

    /**
     * Define the form fields for create/edit
     * Each field: ['field' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required']
     */
    public function fields(): array;

    /**
     * Define filters for the index page
     * Each filter: ['field' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [...]]
     */
    public function filters(): array;

    /**
     * Define validation rules for create
     */
    public function rules(): array;

    /**
     * Define validation rules for update
     */
    public function updateRules(): array;

    /**
     * Define which fields are searchable
     */
    public function searchable(): array;

    /**
     * Define which fields are sortable
     */
    public function sortable(): array;

    /**
     * Define export columns
     */
    public function exportColumns(): array;

    /**
     * Define permission prefix (e.g., "products")
     */
    public function permissionPrefix(): string;

    /**
     * Define relationships to eager load
     */
    public function with(): array;

    /**
     * Custom query modifications
     */
    public function query(Builder $query): Builder;

    /**
     * Define actions for each row
     */
    public function actions(): array;

    /**
     * Define bulk actions
     */
    public function bulkActions(): array;

    /**
     * Get the view directory (e.g., 'pages.products')
     */
    public function viewDir(): string;
}
