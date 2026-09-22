<?php

namespace App\Http\Controllers;

use App\Support\Contracts\CrudResource;
use App\Support\Traits\HasCrud;

abstract class CrudController extends Controller
{
    use HasCrud;
}
