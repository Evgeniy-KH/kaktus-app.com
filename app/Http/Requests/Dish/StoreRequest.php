<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;


use App\Dto\Dish\StoreDto;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //TODO  проверка пользователя с ошибкой. Потом подумай какая тут ошибка. Ну вернее сама со временем поймешь. Первый признакл джуна подобная проверка.
            'user_id' => 'required|exists:users,id',
            // TODO почему тайтл длинна 100 и 2. Понимаю что на угада. Но туту должно быть или то что зазказчик задал или хотя бы как в базе.
            'title' => ["required", "string", "min:2", "max:100"],
            'ingredients' => ["required", "string", "min:1", "max:1250"],
            'description' => ["required", "string", "min:3", "max:4000"],
            'price' => ["required", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'preview_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'main_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:20480"],
            //TODO  если включить голову то понятно что проверка эта не нужна. если у тебя есть проверка снизу. Она просто бессмысленна. Подумать и понять, почему никакого в ней смысла.
            'tag_ids' => ["nullable", "array"],
            'tag_ids.*' => ["nullable", "integer", "exists:tags,id"]
        ];
    }

    // нужно писатьс маленькой. dto() dTO()
    public final function DTO(): StoreDto
    {
        return new StoreDto(
            userId: (int)$this->input('user_id'),
            title: $this->input('title'),
            ingredients: $this->input('ingredients'),
            description: $this->input('description'),
            price: (float)$this->input('price'),
            previewImage: $this->file('preview_image'),
            mainImage: $this->file('main_image'),
            tagIds: $this->input('tag_ids'),
        );
    }
}
