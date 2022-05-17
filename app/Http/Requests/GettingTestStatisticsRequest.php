<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GettingTestStatisticsRequest extends ApiRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'test_id' => 'required',
        ];
    }
}
