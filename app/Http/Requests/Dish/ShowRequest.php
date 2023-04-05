<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

use App\Dto\Dish\UpdateDto;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
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
            'user_id' => ["required", "integer", "exists:users,id"],
            'id' => ["required", "integer", "exists:dishes,id"],
            'title' => ["sometimes", "string", "min:4", "max:255"],
            'ingredients' => ["sometimes", "string", "min:1", "max:1250"],
            'description' => ["sometimes", "string", "min:3", "max:4000"],
            'price' => ["sometimes", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'preview_image' => ["sometimes", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'main_image' => ["sometimes", "image", "max:20480"],
            'tag_ids.*' => ["nullable", "integer", "exists:tags,id"]
        ];
    }

    public final function dto(): UpdateDto
    {
        return new UpdateDto(
            id:  (int)$this->input('id'),
            userId:  (int)$this->input('user_id'),
            title:  $this->input('title'),
            ingredients: $this->input('ingredients'),
            description: $this->input('description'),
            price: (float) $this->input('price'),
            previewImage:  $this->file('preview_image'),
            mainImage: $this->file('main_image'),
            tagIds: $this->input('tag_ids'),
        );
    }
}
