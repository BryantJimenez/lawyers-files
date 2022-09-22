<?php

namespace App\Http\Requests\User;

use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Auth;

class UserStoreRequest extends FormRequest
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
    if (Auth::user()->hasRole('Super Admin')) {
      $roles=Role::where('name', '!=', 'Cliente')->get()->pluck('name');
    } else {
      $roles=Role::where([['name', '!=', 'Super Admin'], ['name', '!=', 'Cliente']])->get()->pluck('name');
    }
    return [
      'photo' => 'nullable|file|mimetypes:image/*',
      'name' => 'required|string|min:2|max:191',
      'lastname' => 'required|string|min:2|max:191',
      'phone' => 'required|string|min:5|max:15',
      'type' => 'required|'.Rule::in($roles),
      'email' => 'required|string|email|max:191|unique:users,email',
      'password' => 'required|string|min:8|confirmed'
    ];
  }
}
