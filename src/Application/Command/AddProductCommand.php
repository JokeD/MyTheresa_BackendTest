<?php

declare(strict_types=1);


namespace App\Application\Command;


use App\Domain\Price;

class AddProductCommand
{
    private int $priceOriginal;
    private string $priceCurrency;
    private string $category;
    private string $name;
    private string $sku;

    public function __construct(
        int $priceOriginal,
        string $category,
        string $name,
        string $sku,
        string $priceCurrency = 'EUR'
    )
    {
        $this->priceOriginal = $priceOriginal;
        $this->priceCurrency = $priceCurrency;
        $this->category      = $category;
        $this->name          = $name;
        $this->sku           = $sku;
    }

    public function priceOriginal(): int
    {
        return $this->priceOriginal;
    }

    public function priceCurrency(): string
    {
        return $this->priceCurrency;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function sku(): string
    {
        return $this->sku;
    }
}