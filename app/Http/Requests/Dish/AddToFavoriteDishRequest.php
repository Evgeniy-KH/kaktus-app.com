<?php
declare(strict_types=1);
namespace App\Http\Requests\Dish;

use App\Data\Dish\DishFavoriteDto;
use Illuminate\Foundation\Http\FormRequest;

class AddToFavoriteDishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public final function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public final function rules()
    {
        return [
            'id' => ["required", "exists:dishes,id"],
        ];
    }

    public final function DTO (): DishFavoriteDto
    {
        return new DishFavoriteDto(
            (int)$this->input('id')
        );
    }
}
