<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\Likeable;
use App\Models\Dish;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    protected array $likeableClass = [
        'Dish' => Dish::class,
    ];

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
            'likeable_type' => [
                "bail",
                "required",
                "string",
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
        $className = $this->input('likeable_type');
        $class = new $this->likeableClass[$className];

        return $class::findOrFail($this->input('id'));
    }

}
