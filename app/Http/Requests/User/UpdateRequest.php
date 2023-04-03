<?php
declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Data\User\UserUpdateDto;
use App\Dto\User\UpdateDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    public final function authorize(): bool
    {
        return true;
    }

    public final function rules(): array
    {
        return [
            'name' => ['sometimes', 'alpha_dash', 'string'],
            'birthday' => ['sometimes', 'date', 'before:today'],
            'email' => ['sometimes', 'string', 'email',],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed', 'different:current_password',
//                Password::min(8)
//                    ->letters()
//                    ->mixedCase()
//                    ->numbers()
//                    ->symbols()
//                    ->uncompromised()
            ],
            'current_password' => ['sometimes', 'string', 'min:8'],
            'avatar_path' => ['sometimes', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:20480'],
        ];
    }

    public final function dto(): UpdateDto
    {
        return new UpdateDto(
            name: $this->input('name'),
            birthday: $this->input('birthday'),
            email: $this->input('email'),
            password: $this->input('password'),
            currentPassword: $this->input('current_password'),
            avatarPath: $this->file('avatar_path'),
        );
    }
}
