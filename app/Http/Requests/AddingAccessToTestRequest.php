<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddingAccessToTestRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_subject' => ['required', 'string', 'max:255'],
            'name_test' => ['required', 'string', 'max:255'],
            'id' => ['required'],
        ];
    }
}