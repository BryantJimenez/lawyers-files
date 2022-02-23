<?php

namespace App\Http\Requests\Resolution;

use Illuminate\Foundation\Http\FormRequest;

class ResolutionStoreRequest extends FormRequest
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
      'date' => 'required|date_format:d-m-Y|before_or_equal:'.date('d-m-Y'),
      'description' => 'required|string|min:2|max:6000',
      'files' => 'nullable|array',
      'files.*' => 'required|string|min:2|max:255'
    ];
  }
}
