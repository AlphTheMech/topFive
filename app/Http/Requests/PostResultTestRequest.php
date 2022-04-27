<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostResultTestRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mark' => ['required', 'integer'],
            'test_id' => ['required'],
            'subject_id' => ['required'],
        ];
    }
}
