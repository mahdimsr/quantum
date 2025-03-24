<?php

namespace App\Traits;

trait OptionValues
{
    public static function optionCases(): array
    {
        $optionCases = [];

        foreach (self::cases() as $case){

            $optionCases[$case->value] = $case->value;
        }

        return $optionCases;
    }
}
