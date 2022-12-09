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
        $setting->fill(['header_background_color' => request('header_background_color'), 'header_text_color' => request('header_text_color'), 'menu_background_color' => request('menu_background_color'), 'menu_background_color_hover' => request('menu_background_color_hover'), 'menu_icon_color' => request('menu_icon_color'), 'menu_text_color' => request('menu_text_color'), 'menu_border_color' => request('menu_border_color'), 'header_text' => request('header_text'), 'google_drive_client_id' => request('google_drive_client_id'), 'google_drive_client_secret' => request('google_drive_client_secret'), 'google_drive_refresh_token' => request('google_drive_refresh_token'), 'google_drive_folder_id' => request('google_drive_folder_id')])->save();

        if ($setting) {
            // Mover imagen a carpeta img y extraer nombre
            if ($request->hasFile('logo')) {
                $file=$request->file('logo');
                $logo=store_files($file, 'logo', '/admins/img/');
                $setting->fill(['logo' => $logo])->save();
            }

            return redirect()->route('settings.edit')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'Los ajustes han sido editados exitosamente.']);
        } else {
            return redirect()->route('settings.edit')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }
}
