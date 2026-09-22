<?php

namespace App\Core\Developer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Developer\Models\Webhook;
use App\Core\Developer\Models\WebhookDelivery;
use App\Core\Audit\Services\AuditService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = Webhook::withCount('deliveries');

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $webhooks = $query->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['data' => $webhooks]);
        }

        return view('pages.developer.webhooks.index', compact('webhooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array|min:1',
            'retry_count' => 'nullable|integer|min:0|max:10',
        ]);

        $webhook = Webhook::create([
            'name' => $request->name,
            'url' => $request->url,
            'events' => $request->events,
            'retry_count' => $request->retry_count ?? 3,
        ]);

        $webhook->generateSecret();
        $this->auditService->logCreate($webhook, $webhook->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $webhook], 201);
        }

        return back()->with('success', 'Webhook created.');
    }

    public function show(Request $request, Webhook $webhook)
    {
        $deliveries = $webhook->deliveries()->latest()->paginate(20);

        if ($request->expectsJson()) {
            return response()->json(['data' => $webhook, 'deliveries' => $deliveries]);
        }

        return view('pages.developer.webhooks.show', compact('webhook', 'deliveries'));
    }

    public function update(Request $request, Webhook $webhook)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'required|array|min:1',
            'status' => 'required|in:active,inactive',
            'retry_count' => 'nullable|integer|min:0|max:10',
        ]);

        $webhook->update($request->only(['name', 'url', 'events', 'status', 'retry_count']));
        $this->auditService->logUpdate($webhook, $webhook->getOriginal(), $webhook->getAttributes());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $webhook]);
        }

        return back()->with('success', 'Webhook updated.');
    }

    public function destroy(Request $request, Webhook $webhook)
    {
        $this->auditService->logDelete($webhook);
        $webhook->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Webhook deleted.');
    }

    public function test(Request $request, Webhook $webhook)
    {
        $payload = [
            'event' => 'webhook.test',
            'timestamp' => now()->toISOString(),
            'data' => ['message' => 'Test webhook delivery'],
        ];

        $delivery = WebhookDelivery::create([
            'webhook_id' => $webhook->id,
            'event' => 'webhook.test',
            'payload' => $payload,
            'status' => 'pending',
        ]);

        // TODO: Dispatch webhook delivery job
        // dispatch(new DeliverWebhookJob($webhook, $delivery));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Test webhook queued.']);
        }

        return back()->with('success', 'Test webhook queued.');
    }

    public function deliveries(Request $request, Webhook $webhook)
    {
        $deliveries = $webhook->deliveries()
            ->when($request->input('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json(['data' => $deliveries]);
        }

        return view('pages.developer.webhooks.deliveries', compact('webhook', 'deliveries'));
    }
}
