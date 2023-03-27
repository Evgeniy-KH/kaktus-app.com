<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;


use App\Dto\Dish\StoreDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //TODO  проверка пользователя с ошибкой. Потом подумай какая тут ошибка. Ну вернее сама со временем поймешь. Первый признакл джуна подобная проверка.
           // 'user_id' => 'required|exists:users,id',// Auth::user может проверка
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', auth()->user()->id);
                })
            ],// проверить если ser_id равно айторизрпрваному юзеру
            'title' => ["required", "string", "min:12", "max:255"],
            'ingredients' => ["required", "string", "min:1", "max:1250"],
            'description' => ["required", "string", "min:3", "max:4000"],
            'price' => ["required", "numeric", "min:0", "regex:/^\d{1,13}(\.\d{1,2})?$/"],
            'preview_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:2048"],
            'main_image' => ["required", "image", "mimes:jpg,png,jpeg,gif,svg", "max:20480"],
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
