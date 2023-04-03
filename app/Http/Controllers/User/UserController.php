<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Nette\Utils\Json;

class UserController extends Controller
{

    public final function __construct(
        protected UserService $service
    )
    {
    }

    public final function update(int $id, UpdateRequest $request): ResponseResource|JsonResponse
    {
        $dto = $request->dto();
        $returnData = $this->service->update(dto: $dto, id: $id);
        $isUserData = $returnData instanceof User;

        return new ResponseResource(
            resource:  $isUserData ? new UserResource($returnData) : '',
            message:  $isUserData ? '' : $returnData,
            statusCode:  $isUserData ? 200 : 422
        );
    }

    public final function delete(int $id): ResponseResource
    {
        $user = $this->service->delete(id: $id);

        return new ResponseResource(
            message: $user ? 'Successfully deleted' : 'Try later to delete',
            statusCode: $user ? 200 : 422
        );
    }
}
