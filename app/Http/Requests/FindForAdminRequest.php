<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FindForAdminRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'string', 'max:255'],
        ];
    }
}
