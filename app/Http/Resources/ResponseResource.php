<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ResponseResource extends JsonResource
{
    public function __construct(mixed $resource = null, string $message = '', int $statusCode = 200)
    {
        $this->resource = $resource;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public final function toArray($request): array
    {
        return [
            'message' => $this->message,
            'status' => $this->statusCode,
            'data' => $this->resource,
        ];
    }

    public final function withResponse($request, $response): void
    {
        $response->setStatusCode($this->statusCode);
    }
}
