<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request): Response
    {
        return response()->view('workshop.configuration.settings.index', [
            'request' => $request,
            'settings' => site(false)->theme()->settings(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $requestInput = $request->all();

        if (isset($requestInput['settings']) && is_array($requestInput['settings']) && count($requestInput['settings']) > 0) {
            $settings = site(false)->theme()->settings();

            foreach ($settings as $setting) {
                if (isset($setting->name)) {
                    $value = $requestInput['settings'][$setting->name] ?? null;

                    if ($value instanceof UploadedFile) {
                        if ($key = Storage::putFileAs('/', $value, $value->getClientOriginalName(), 'public')) {
                            $value = $key;
                        } else {
                            $value = null;
                        }
                    }

                    Setting::set($setting->name, $value);
                }
            }
        }

        return redirect()->route('workshop.configuration.setting.index');
    }
}
