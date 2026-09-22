<?php

namespace App\Core\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Dashboard\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $widgets = $this->dashboardService->getWidgetsForDashboard($user);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => collect($widgets)->map(fn ($w) => [
                    'id' => $w['config']['id'],
                    'name' => $w['instance']->getName(),
                    'description' => $w['instance']->getDescription(),
                    'width' => $w['instance']->getWidth(),
                    'height' => $w['instance']->getHeight(),
                    'view' => $w['instance']->getView(),
                    'data' => $w['data'],
                ])->values(),
            ]);
        }

        return view('pages.dashboard.ecommerce', compact('widgets'));
    }

    public function availableWidgets(Request $request)
    {
        $widgets = $this->dashboardService->getAvailableWidgets($request->user());

        return response()->json([
            'data' => collect($widgets)->map(fn ($w) => [
                'id' => $w->getId(),
                'name' => $w->getName(),
                'description' => $w->getDescription(),
                'group' => $w->getGroup(),
                'width' => $w->getWidth(),
                'height' => $w->getHeight(),
            ])->values(),
        ]);
    }

    public function saveWidgets(Request $request)
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*' => 'required|string',
        ]);

        $this->dashboardService->saveUserWidgets($request->user(), $request->widgets);

        return response()->json(['success' => true, 'message' => 'Widgets saved successfully.']);
    }

    public function refreshWidget(Request $request, string $widgetId)
    {
        $widget = $this->dashboardService->getWidget($widgetId);

        if (!$widget) {
            return response()->json(['error' => 'Widget not found'], 404);
        }

        if (!$widget->canAccess($request->user())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => [
                'id' => $widget->getId(),
                'name' => $widget->getName(),
                'data' => $widget->getData(),
            ],
        ]);
    }
}
