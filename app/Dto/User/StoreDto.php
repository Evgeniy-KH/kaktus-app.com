<?php
declare(strict_types=1);

namespace App\Dto\User;

use Spatie\LaravelData\Data;

class StoreDto extends Data
{
    public function __construct(
        private readonly string|null $name,
        private readonly string|null $birthday,
        private readonly string|null $email,
        private readonly string|null $password,
        private readonly string|null $current_password,
        private readonly object|null $avatar_path,
    )
    {
    }

    public final function getName(): string|null
    {
        return $this->name;
    }

    public final function getBirthday(): string|null
    {
        return $this->birthday;
    }

    public final function getEmail(): string|null
    {
        return $this->email;
    }

    public final function getPassword(): string|null
    {
        return $this->password;
    }

    public final function getCurrentPassword(): string|null
    {
        return $this->current_password;
    }

    public final function getAvatarPath(): object|null
    {
        return $this->avatar_path;
    }
}
