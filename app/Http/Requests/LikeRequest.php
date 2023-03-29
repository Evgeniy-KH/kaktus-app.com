<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\Likeable;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    protected array $likeableClass = [
        'Dish' => Dish::class,
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'likeable_type' => [
                "bail",
                "required",
                "string",
            ],

            'id' => [
                "required",
                "exists:dishes,id"
            ],
        ];
    }

    public final function likeable(): Likeable
    {
        $class = new $this->likeableClass[$this->input('likeable_type')];

        return $class::findOrFail($this->input('id'));
    }

}
