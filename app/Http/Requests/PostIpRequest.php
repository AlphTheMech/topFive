<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostIpRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ip_address' => ['required', 'string', 'max:16'],
            'user_id' => 'required'
        ];
    }
}
