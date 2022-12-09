<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => 'nullable|file|mimetypes:image/*',
            'header_background_color' => 'required|string|min:7|max:7',
            'header_text_color' => 'required|string|min:7|max:7',
            'menu_background_color' => 'required|string|min:7|max:7',
            'menu_background_color_hover' => 'required|string|min:7|max:7',
            'menu_icon_color' => 'required|string|min:7|max:7',
            'menu_text_color' => 'required|string|min:7|max:7',
            'menu_border_color' => 'required|string|min:7|max:7',
            'header_text' => 'nullable|string|min:2|max:100',
            'google_drive_client_id' => 'required|string|min:2|max:191',
            'google_drive_client_secret' => 'required|string|min:2|max:191',
            'google_drive_refresh_token' => 'required|string|min:2|max:191',
            'google_drive_folder_id' => 'required|string|min:2|max:191'
        ];
    }
}
