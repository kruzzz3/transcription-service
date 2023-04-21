<?php

namespace App\Http\Requests;

use App\Models\Enums\System\FileTypes;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AudioUploadRequest extends FormRequest
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
        $this['uuid'] = str()->uuid()->toString(); // force uuid

        $dateAsArray = explode('-', Carbon::now()->translatedFormat('Y-m-d'));
        $this['path_to_store'] = 'public/audio/' . $dateAsArray[0] . '/' . $dateAsArray[1] . '/' . $dateAsArray[2];
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'file' => [
                'required',
                'mimes:mp3,m4a',
                'max:51200', // 50MB
            ],
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
