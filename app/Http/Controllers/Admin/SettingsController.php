<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.index', [
            'facebookLiveUrl' => Setting::getValue('facebook_live_url'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'facebook_live_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $value = $validated['facebook_live_url'] ?? null;
        $value = $value !== null ? trim($value) : null;
        $value = $value === '' ? null : $value;

        Setting::setValue('facebook_live_url', $value);

        return redirect()
            ->route('admin.settings.edit')
            ->with('message', 'Setting have been saved successfully.');
    }
}
