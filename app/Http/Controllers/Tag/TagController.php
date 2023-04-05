<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\DishCollection;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function __construct(protected Tag $tag)
    {
    }

    public final function index(): ResponseResource
    {
        //TODO прочитать и запомнить чем отличается all() от get()  в моделях Eloquent
        return new ResponseResource(
            resource: TagResource::collection($this->tag->all()),
        );
    }
}
