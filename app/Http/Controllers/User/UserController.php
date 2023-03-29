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

        return new ResponseResource(
            resource: $returnData instanceof User ? new UserResource($returnData) : '',
            message:    $returnData instanceof User ? '' : $returnData,
            statusCode: $returnData instanceof User  ? 200 : 422
        );
    }
}
