<?php

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
