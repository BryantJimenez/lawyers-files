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
            'google_drive_client_id' => 'required|string|min:2|max:191',
            'google_drive_client_secret' => 'required|string|min:2|max:191',
            'google_drive_refresh_token' => 'required|string|min:2|max:191',
            'google_drive_folder_id' => 'required|string|min:2|max:191'
        ];
    }
}
