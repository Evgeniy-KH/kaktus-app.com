<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

use App\Data\Dish\DishStoreDto;
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
    public final function DTO(): DishStoreDto
    {
        //TODO ИМЕННОВАННЫЕ ПАРВАМЕТРЫ по всему проекту!
        return new DishStoreDto(
            //TODO ОЧЕНЬ ВАЖНО В КАКОМ СТИЛЕ ТЫ ПИШЕШЬ ПРОЕКТ!"!!!! ЕПсли у тебя переменные которые ты пригнимаешь с фронта идут с снейк кейс стиле, то все переменные должны быть только в этом стиле.!!!!!!!!!!!!
            (int)$this->input('user_id'),
            $this->input('title'),
            $this->input('ingredients'),
            $this->input('description'),
            //Выше у тебя юзер ид это число, а прайс это что то хаотичное?!?!?!?!??!
            // ЦЕНЦ указывать строкорй, это самое худшее что можно сделать. 
            $this->input('price'),
            $this->file('preview_image'),
            $this->file('main_image'),
            $this->input('tag_ids'),
        );
    }
}
