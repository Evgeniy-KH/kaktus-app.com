<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class DishImagesType extends Enum
{
    const Preview = 0;
    const Main = 1;

    public static function fromName(string $name)
    {
        $name = ucwords($name);
        return constant("self::{$name}");
    }
}
