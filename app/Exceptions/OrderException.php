<?php

namespace App\Exceptions;

class OrderException extends \Exception
{
    public static function leverageFailed(?string $message = null): self
    {
        if ($message) {

            logs()->channel('order')->error($message);
        }

        return new self('Leverage Setup Failed');
    }

    public static function placeOrderFailed(?string $message = null): self
    {
        if ($message) {

            logs()->channel('order')->error($message);
        }

        return new self('Place Order Failed');
    }
}
