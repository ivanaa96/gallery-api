<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
            'title' => "required|min:2|max:255",
            'description' => "sometimes|max:1000",
            'image_urls' => "required|array",
            'image_urls.*.url' => 'required|url|ends_with:jpg,jpeg,png',
        ];
    }
}
