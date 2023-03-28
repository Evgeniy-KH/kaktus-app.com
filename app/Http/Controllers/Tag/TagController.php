<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function __construct(protected Tag $tag)
    {
    }

    public final function index(): AnonymousResourceCollection
    {
         return TagResource::collection($this->tag->all());
    }
}
