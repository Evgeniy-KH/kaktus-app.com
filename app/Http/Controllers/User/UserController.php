<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Filters\Dish\DishFilter;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\MessageResource;
use App\Models\Dish;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends BaseController
{

    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct($userService);
    }

    public function update(int $id, UpdateRequest $request): MessageResource|JsonResponse
    {
        $userDto = $request->DTO();
        $returnData = $this->service->update(userDto: $userDto, id: $id);

        if ($returnData['success'] === true) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully updated'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => $returnData['message']
            ]))->response()
                ->setStatusCode($returnData['code']);
        }
    }
}
