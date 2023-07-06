<?php

namespace App\Http\Requests;

use App\Rules\VideoDuration;
use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file'  => ['required', 'file', 'mimes:mp4,mov,ogg,webm', 'max:10240', new VideoDuration()],
        ];
    }
}
