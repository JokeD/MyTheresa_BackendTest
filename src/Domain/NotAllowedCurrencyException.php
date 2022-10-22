<?php

declare(strict_types = 1);


namespace App\Domain;


class NotAllowedCurrencyException extends \Exception
{
    public static function msg(string $current): self
    {
        return new self($current . " currency not allowed");
    }
}