<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @param  string  $message
     * @param  int  $statusCode
     * @return void
     */
    public function __construct($resource = null, $message = null, $statusCode = null)
    {
        $this->resource = $resource;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request):array
    {
        return [
            'data' => [
                'message' => $this->message,
                'status_code' => $this->statusCode,
                'data' => $this->resource,
            ]
        ];
    }

    /**
     * Customize the response for a specific HTTP status code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
