<?php

namespace App\Http\Requests\Company;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class CompanyStoreRequest extends FormRequest
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
    $users=User::where('state', '1')->get()->pluck('slug');
    $customer=(!Auth::user()->hasRole('Cliente')) ? true : false;
    return [
      'name' => 'required|string|min:2|max:191',
      'social_reason' => 'required|string|min:2|max:191',
      'address' => 'required|string|min:2|max:191',
      'customer_id' => Rule::requiredIf($customer).'|'.Rule::in($users)
    ];
  }
}
