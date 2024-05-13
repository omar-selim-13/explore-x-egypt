<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Api\ApiResponseTrait;

class ArtifactRequest extends FormRequest
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


    public function rules(): array
    {
        return [
                'artifact_name' => 'required|max:60',
                'date' => 'required|max:20',
                'image' => 'required',
                'description' => 'required|max:255',
                'location_id' => 'required',
        ];
    }
}
