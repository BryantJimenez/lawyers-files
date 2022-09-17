<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\SettingUpdateRequest;

class SettingController extends Controller
{
    public function edit() {
        $setting=Setting::where('id', 1)->firstOrFail();
        return view('admin.settings.edit', compact("setting"));
    }

    public function update(SettingUpdateRequest $request) {
        $setting=Setting::where('id', 1)->firstOrFail();
        $setting->fill(['google_drive_client_id' => request('google_drive_client_id'), 'google_drive_client_secret' => request('google_drive_client_secret'), 'google_drive_refresh_token' => request('google_drive_refresh_token'), 'google_drive_folder_id' => request('google_drive_folder_id')])->save();

        if ($setting) {
            return redirect()->route('settings.edit')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'Los ajustes han sido editados exitosamente.']);
        } else {
            return redirect()->route('settings.edit')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }
}
