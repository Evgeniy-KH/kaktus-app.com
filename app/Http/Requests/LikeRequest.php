<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\Likeable;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    protected array $likeableClass = [
        Dish::class,
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'likeable_type_id' => [
                "bail",
                "required",
                "integer",
            ],

            'id' => [
                "required",
                "exists:dishes,id"
            ],
        ];
    }

    public final function likeable(): Likeable
    {
        $class = new $this->likeableClass[(int) $this->input('likeable_type_id')];

        return $class::find($this->input('id'));
    }
}
