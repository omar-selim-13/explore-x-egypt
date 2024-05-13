<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Api\ApiResponseTrait;

class LocationRequest extends FormRequest
{
    use ApiResponseTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->apiresponse(422, ' Validation failed ', $validator->errors()); 
        throw new ValidationException($validator ,$response );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location_name' => 'required|max:60',
            'type' => 'required|max:50',
            'image' => 'required',
            'description' => 'required|max:255',
            'total_artifacts' => 'required|numeric',
            'city_id' => 'required',
        ];
    }
}
