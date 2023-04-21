<?php

namespace App\Http\Requests;

use App\Models\Enums\System\FileTypes;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class GenerateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'text' => ['required'],
        ];
    }

    /**
     * Redirect back on error and show toast.
     *
     * @param mixed $validator
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            dd($validator->fails(), $validator->errors());
            return response($validator->errors(), 422)->header('Content-Type', 'text/plain');
        }
    }
}
