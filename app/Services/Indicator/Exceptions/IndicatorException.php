<?php

namespace App\Services\Indicator\Exceptions;

use App\Services\Indicator\Entity\Candle;
use Exception;

class IndicatorException extends Exception
{
    public static function keyNotExist(string $key): static
    {
        return new self("$key is required to create " . Candle::class . " from array data");
    }
}
