<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;


use App\Dto\Dish\FavoriteDto;
use Illuminate\Foundation\Http\FormRequest;

class AddToFavoriteDishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public final function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public final function rules(): array
    {
        return [
            'dishId' => ["required", "exists:dishes,id"],
            'userId' => ["required"],
        ];
    }

    public final function dto(): FavoriteDto
    {
        return new FavoriteDto(
            userId: (int)$this->input('userId'),
            dishId: (int)$this->input('dishId')
        );
    }
}
