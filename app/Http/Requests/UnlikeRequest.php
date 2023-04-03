<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\Likeable;
use App\Models\Dish;
use Illuminate\Foundation\Http\FormRequest;

class UnlikeRequest extends FormRequest
{
    protected array $likeableClass = [
        Dish::class,
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('unlike', $this->likeable());
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'likeable_type_id' => [
                "bail",
                "required",
                "integer",
            ],
            // the id of the liked object
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
