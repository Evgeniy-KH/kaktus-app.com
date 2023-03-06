<?php

namespace App\Http\Requests;

use App\Contracts\Likeable;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;

class UnlikeRequest extends FormRequest
{
    protected array $likeableClass = [
        'Dish' => Dish::class,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('unlike', $this->likeable());
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'likeable_type' => [
                "bail",
                "required",
                "string",
            ],
            // the id of the liked object
            'dish_id' => [
                "required",
                "exists:dishes,id"
            ],
        ];
    }

    public function likeable(): Likeable
    {
        $className = $this->input('likeable_type');
        $class = new $this->likeableClass[$className];

        return $class::findOrFail($this->input('dish_id'));
    }
}
