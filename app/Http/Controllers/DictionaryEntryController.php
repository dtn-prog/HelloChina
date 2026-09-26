<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDictionaryEntryRequest;
use App\Http\Requests\UpdateDictionaryEntryRequest;
use App\Models\DictionaryEntry;
use App\Core\Audit\Services\DictionaryEntryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dictionaryEntries.search');
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'max:100'],
            'type' => ['sometimes', 'string', 'in:all,chinese,pinyin,vietnamese'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        $entries = DictionaryEntryService::search(
            $validated['keyword'],
            $validated['type'] ?? 'all',
            (int) ($validated['limit'] ?? 200),
        );

        return response()->json(['data' => $entries]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDictionaryEntryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DictionaryEntry $dictionaryEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DictionaryEntry $dictionaryEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDictionaryEntryRequest $request, DictionaryEntry $dictionaryEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DictionaryEntry $dictionaryEntry)
    {
        //
    }
}
