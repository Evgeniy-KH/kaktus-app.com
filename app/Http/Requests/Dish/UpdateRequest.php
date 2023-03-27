<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

use App\Data\Dish\UpdateDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'userId' => ["required", "integer", "exists:users,id"],
            'id' => ["required", "integer", "exists:dishes,id"],
            'title' => ["sometimes", "required", "string", "min:2", "max:200"],
            'ingredients' => ["sometimes", "required", "string", "min:1", "max:1250"],
            'description' => ["sometimes", "required", "string", "min:3", "max:4000"],
            'price' => ["sometimes", "required", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'previewImage' => ["sometimes", "required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'mainImage' => ["sometimes", "required", "image", "max:20480"],
            'tagIds' => ["nullable", "array"],
            'tagIds.*' => ["nullable", "integer", "exists:tags,id"]
        ];
    }

    public final function dto(): UpdateDto
    {
        return new UpdateDto(
            id:  (int)$this->input('id'),
            userId:  (int)$this->input('userId'),
            title:  $this->input('title'),
            ingredients: $this->input('ingredients'),
            description: $this->input('description'),
            price: (float) $this->input('price'),
            previewImage:  $this->file('previewImage'),
            mainImage: $this->file('mainImage'),
            tagIds: $this->input('tagIds'),
        );
    }
}
