<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

use App\Data\Dish\DishStoreDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'title' => ["required", "string", "min:2", "max:100"],
            'ingredients' => ["required", "string", "min:1", "max:1250"],
            'description' => ["required", "string", "min:3", "max:4000"],
            'price' => ["required", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'preview_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'main_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:20480"],
            'tag_ids' => ["nullable", "array"],
            'tag_ids.*' => ["nullable", "integer", "exists:tags,id"]
        ];
    }

    public final function DTO(): DishStoreDto
    {
        return new DishStoreDto(
            (int)$this->input('user_id'),
            $this->input('title'),
            $this->input('ingredients'),
            $this->input('description'),
            $this->input('price'),
            $this->file('preview_image'),
            $this->file('main_image'),
            $this->input('tag_ids'),
        );
    }
}
