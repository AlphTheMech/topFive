<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddingAccessToTestRequest extends ApiRequest
{
   

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
            'id' => 'required',
        ];
    }
}
