<?php
declare(strict_types=1);
namespace App\Http\Requests\User;

use App\Data\User\UserUpdateDto;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'alpha_dash', 'string'],
            'birthday' => ['sometimes', 'required', 'date', 'before:today'],
            'email' => ['sometimes', 'required', 'string', 'email'],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'current_password' => ['sometimes', 'required', 'string', 'min:8', 'current_password'],
            'avatar_path' => ['sometimes', 'required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:20480'],
        ];
    }

    public final function DTO(): UserUpdateDto
    {
        return new UserUpdateDto(
            $this->input('name'),
            $this->input('birthday'),
            $this->input('email'),
            $this->input('password'),
            $this->input('current_password'),
            $this->file('avatar_path'),
        );
    }
}
