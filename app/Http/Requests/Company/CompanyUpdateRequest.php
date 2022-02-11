<?php

namespace App\Http\Requests\Company;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyUpdateRequest extends FormRequest
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
        $users=User::all()->pluck('slug');
        return [
            'name' => 'required|string|min:2|max:191',
            'social_reason' => 'required|string|min:2|max:191',
            'address' => 'required|string|min:2|max:191',
            'state' => 'required|'.Rule::in(['0', '1']),
            'customer_id' => 'required|'.Rule::in($users)
        ];
    }
}