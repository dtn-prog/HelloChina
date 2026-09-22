<?php

namespace App\Core\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Setting\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index(Request $request)
    {
        $group = $request->input('group', 'general');
        $settings = $this->settingService->getGroup($group);
        $groups = ['general', 'email', 'sms', 'notification', 'storage', 'payment', 'security', 'maintenance'];

        if ($request->expectsJson()) {
            return response()->json(['data' => $settings, 'groups' => $groups]);
        }

        return view('pages.settings.index', compact('settings', 'groups', 'group'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
        ]);

        foreach ($request->settings as $setting) {
            $this->settingService->set($setting['key'], $setting['value'] ?? null);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Settings updated successfully.']);
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function getPublicSettings(Request $request)
    {
        return response()->json($this->settingService->getPublicSettings());
    }
}
