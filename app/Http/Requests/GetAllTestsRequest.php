<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAllTestsRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
        ];
    }
}
