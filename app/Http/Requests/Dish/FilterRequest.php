<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

use App\Dto\Dish\FilterDto;
use Illuminate\Foundation\Http\FormRequest;


class FilterRequest extends FormRequest
{
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
            'price' => ["sometimes","string"],  //"nullable"
            'keywords.*' => ["sometimes","string"],
            'tag_ids' => ["sometimes", "string"]
        ];
    }

    public final function dto(): FilterDto
    {
        return new FilterDto(
            data: $this->input(),
        );
    }
}
