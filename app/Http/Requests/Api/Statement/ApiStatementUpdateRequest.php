<?php

namespace App\Http\Requests\Api\Statement;

use App\Models\Type;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class ApiStatementUpdateRequest extends FormRequest
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
        $types=Type::where('state', '1')->get()->pluck('id');
        $companies=Company::where([['state', '1'], ['user_id', Auth::id()]])->get()->pluck('id');
        return [
            'name' => 'required|string|min:2|max:191',
            'type_id' => 'required|'.Rule::in($types),
            'company_id' => 'required|'.Rule::in($companies),
            'description' => 'required|string|min:2|max:6000'
        ];
    }
}
