<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => "sometimes|min:2|max:255",
            'description' => "sometimes|max:1000",
            'image_urls' => "sometimes|array",
            'image_urls.*.url' => 'sometimes|url|ends_with:jpg,jpeg,png',
        ];
    }
}
