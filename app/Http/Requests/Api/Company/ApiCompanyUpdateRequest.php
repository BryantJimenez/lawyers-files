<?php

namespace App\Http\Requests\Api\Company;

use Illuminate\Foundation\Http\FormRequest;

class ApiCompanyUpdateRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:191',
            'social_reason' => 'required|string|min:2|max:191',
            'rfc' => 'required|string|min:1|max:191|'.Rule::unique('companies')->ignore($this->company->slug, 'slug'),
            'address' => 'required|string|min:2|max:191'
        ];
    }
}
