<?php

namespace App\Services\Indicator\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class RSIException extends Exception
{
    public static function keyNotExists(string $key): static
    {
        return new self("$key not exists in passing argument", Response::HTTP_NOT_ACCEPTABLE);
    }
}
