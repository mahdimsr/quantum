<?php

namespace App\Services\Exchange\Exceptions;

use App\Services\Exchange\Repository\Candle;
use Exception;

class CandleException extends Exception
{
    public static function keyNotExist(string $key): static
    {
        return new self("$key is required to create " . Candle::class . " from array data");
    }
}
