<?php
declare(strict_types=1);

namespace App\Http\Requests\Dish;

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
            'price' => ["sometimes"],  //"nullable"
            'keyword' => ["sometimes", "string", "min:2", "max:100"],//"nullable"
        ];
    }
}
