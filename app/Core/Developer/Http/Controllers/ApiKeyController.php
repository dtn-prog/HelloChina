<?php

namespace App\Core\Developer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Developer\Models\ApiKey;
use App\Core\Audit\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = ApiKey::with('user');

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        $apiKeys = $query->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['data' => $apiKeys]);
        }

        return view('pages.developer.api-keys.index', compact('apiKeys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $apiKey = ApiKey::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'token' => ApiKey::generateToken(),
            'abilities' => $request->abilities ?? ['*'],
            'expires_at' => $request->expires_at,
        ]);

        $this->auditService->logCreate($apiKey, $apiKey->toArray());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $apiKey,
                'token' => $apiKey->token,
                'message' => 'API key created. Store the token securely - it won\'t be shown again.',
            ], 201);
        }

        return back()->with('success', 'API key created. Token: ' . $apiKey->token);
    }

    public function show(Request $request, ApiKey $apiKey)
    {
        if ($request->expectsJson()) {
            return response()->json(['data' => $apiKey]);
        }

        return view('pages.developer.api-keys.show', compact('apiKey'));
    }

    public function destroy(Request $request, ApiKey $apiKey)
    {
        $this->auditService->logDelete($apiKey);
        $apiKey->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'API key deleted.');
    }

    public function regenerate(Request $request, ApiKey $apiKey)
    {
        $oldToken = $apiKey->token;
        $apiKey->update(['token' => ApiKey::generateToken()]);

        $this->auditService->log("Regenerated API key: {$apiKey->name}", $apiKey, 'default', [
            'event' => 'updated',
            'old_token' => substr($oldToken, 0, 8) . '...',
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $apiKey,
                'token' => $apiKey->token,
                'message' => 'API key regenerated. Store the token securely.',
            ]);
        }

        return back()->with('success', 'API key regenerated. Token: ' . $apiKey->token);
    }
}
