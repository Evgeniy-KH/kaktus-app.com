<?php
declare(strict_types=1);

namespace App\Dto;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class likeableDto extends Data
{

    public function __construct(
        private readonly int          $userId,
        private readonly int          $likeableId,
        private readonly string       $likeableType,
    )
    {
    }

    public final function getUserId(): int
    {
        return $this->userId;
    }

    public final function getLikeableId(): int
    {
        return $this->likeableId;
    }

    public final function getLikeableType(): string
    {
        return $this->likeableType;
    }
}
