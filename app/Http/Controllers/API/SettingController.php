<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = $request->user()->role === 'admin';

        $query = Setting::query();
        if (!$isAdmin) {
            $query->where('is_public', true);
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        return response()->json($query->get()->mapWithKeys(fn($s) => [$s->key => $s->typedValue()]));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'       => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
        ]);

        foreach ($request->settings as $item) {
            Setting::set($item['key'], $item['value']);
        }

        return response()->json(['message' => 'Settings updated']);
    }

    public function show(string $key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) return response()->json(['message' => 'Setting not found'], 404);

        return response()->json([$key => $setting->typedValue()]);
    }
}
