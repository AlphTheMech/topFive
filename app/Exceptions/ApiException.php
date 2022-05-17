<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiException extends HttpResponseException
{    
    /**
     * __construct
     *
     * @param  mixed $code
     * @param  mixed $message
     * @param  mixed $errors
     * @return void
     */
    public function __construct($code = 422, $message = 'Ошибка валидации', $errors = [])
    {
        $data = [
            'error' => [
                'code' => $code,
                'message' => $message,
            ]
        ];
        if (count($errors) > 0) {
            $data['error']['errors'] = $errors;
        }

        parent::__construct(response()->json($data, $code));
    }
}
