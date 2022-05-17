<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:30'],
            "permission" => ['required', 'string', 'max:30']
        ];
    }
}
