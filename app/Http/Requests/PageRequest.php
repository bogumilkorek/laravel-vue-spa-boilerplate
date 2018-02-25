<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
      'title' => 'unique:pages,title,' . $this->id,
      'content' => 'required',
    ];
  }

  public function messages()
  {
    return [
      'title.required' => __("Field 'title' is required"),
      'title.unique' => __("Field 'title' must be unique"),
      'content.required' => __("Field 'content' is required"),
    ];
  }
}
