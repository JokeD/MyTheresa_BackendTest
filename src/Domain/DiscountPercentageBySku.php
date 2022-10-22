<?php

declare(strict_types = 1);

namespace App\Domain;


class DiscountPercentageBySku implements Discountable
{
    private string $productSkuDiscountable;
    private int $percentage;

    private function __construct(string $productSku, int $percentage)
    {
        $this->productSkuDiscountable = $productSku;
        $this->percentage             = $percentage;
    }

    public static function create(string $productSku, int $percentage): self
    {
        return new self($productSku, $percentage);
    }

    public function apply(Product $product): int
    {
        $originalProductPrice = $product->price()->original();

        if ($product->sku() === $this->productSkuDiscountable) {

            return intval($originalProductPrice - ($originalProductPrice * ($this->percentage / 100)));
        }

        return $originalProductPrice;
    }

    public function discountType(): string
    {
        return "{$this->percentage} %";
    }
}