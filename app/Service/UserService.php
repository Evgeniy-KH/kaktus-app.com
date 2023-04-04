<?php

namespace App\Service;

use App\Dto\User\UpdateDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Collection;

class UserService
{
    public function __construct(
        private readonly Hash     $hash,
        private readonly Storage   $storage,
    )
    {
    }

    public final function update(UpdateDto $dto, int $id): User|string
    {
        $user = $this->show(id: $id);

        if ($dto->getName() !== null || $dto->getBirthday() !== null) {
            $user->update([
                'name' => $dto->getName(),
                'birthday' => $dto->getBirthday()
            ]);
        }

        if ($dto->getCurrentPassword() !== null && $dto->getPassword() !== null) {
            if ($this->hash::check($dto->getCurrentPassword(), $user->password)) {
                $user->update([
                    'password' => $this->hash::make($dto->getPassword())
                ]);
            } else {
                return $message = 'Your current password is incorrect';
            }
        }

        if ($dto->getAvatarPath() !== null) {
            if ($user->avatar_path) {
                $this->storage::delete('public/images/' . $user->avatar_path);
            }

            $file = $dto->getAvatarPath();
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $user->update(["avatar_path" => $fileName]);
        }

        return $user;
    }

    public final function show(int $id): User
    {
        return User::find($id);
    }

    public final function delete(int $id): bool
    {
        return User::find($id)->delete();
    }
}


//          $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
