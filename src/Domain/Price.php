<?php

declare(strict_types = 1);

namespace App\Domain;

const ALLOWED_CURRENCIES = ['EUR'];

class Price
{
    private int $original;
    private string $currency;

    private function __construct(
        int $original,
        string $currency
    )
    {
        $this->original = $original;
        $this->currency = $currency;
    }

    public static function create(
        int $original,
        string $currency
    ): self
    {
        if (!in_array($currency,ALLOWED_CURRENCIES)){
            throw NotAllowedCurrencyException::msg($currency);
        }
        return new self($original, $currency);
    }

    public function original(): int
    {
        return $this->original;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}