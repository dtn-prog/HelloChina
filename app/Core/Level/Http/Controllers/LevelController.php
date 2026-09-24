<?php

namespace App\Core\Level\Http\Controllers;

use App\Core\Level\Models\Level;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $levels = Level::query()
            ->orderBy('level')
            ->paginate($request->input('per_page', 25));

        if ($request->expectsJson()) {
            return response()->json(['data' => $levels]);
        }

        return view('pages.levels.index', compact('levels'));
    }
}
