<?php

namespace App\Traits;

trait OptionValues
{
    public static function optionCases(): array
    {
        return collect(self::cases())->map(fn($object) => [$object->value => $object->name])->flatten()->toArray();
    }
}
