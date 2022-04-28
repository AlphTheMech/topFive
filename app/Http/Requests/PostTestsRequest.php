<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostTestsRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_test' => ['required', 'unique:tests', 'string', 'max:255'],
            'json_data' => 'required'
        ];
    }
}
