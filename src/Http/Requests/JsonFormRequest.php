<?php
namespace CSlant\Blog\Api\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class JsonFormRequest extends FormRequest
{
    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($response);
    }
}
