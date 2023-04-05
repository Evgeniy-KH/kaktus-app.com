<?php
// ПОчему папка дата???? дата означает данные а тут у тебя не данные тут у тебя классы которые отвечают. Папка Dto
declare(strict_types=1);

namespace App\Dto\Dish;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class FilterDto extends Data
{
    public function __construct(

        private readonly array|null $data,
//        private readonly array|null  $keyword,
//        private readonly string|null $tagIds,
    )
    {
    }

    public final function getData(): array|null
    {
        return $this->data;
    }

//    public final function getKeyword(): array|null
//    {
//        return $this->keyword;
//    }
//
//    public final function getTagIds(): string|null
//    {
//        return $this->tagIds;
//    }
}
