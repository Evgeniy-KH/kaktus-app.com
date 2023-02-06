<?php

namespace App\Http\Requests\Dish;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => ["required","exists:users,id"],
            'title' => ["required", "string", "min:2", "max:100"],
            'ingredients'=> ["required", "string", "min:1", "max:1250"],
            'description'=> ["required", "string", "min:3", "max:4000"],
            'price'=> ["required", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'preview_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'main_image' => ["required", "file", "mimes:jpg,png,jpeg,gif,svg", "max:20480"],
            'tag_ids' => ["nullable", "array"],
            'tag_ids.*' => ["nullable", "integer","exists:tags,id"]
        ];
    }
}
