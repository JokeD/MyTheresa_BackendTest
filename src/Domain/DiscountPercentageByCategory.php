<?php

declare(strict_types = 1);


namespace App\Domain;


class DiscountPercentageByCategory implements Discountable
{
    private string $productCategoryDiscountable;
    private int $percentage;

    private function __construct(string $category, int $percentage)
    {
        $this->productCategoryDiscountable = $category;
        $this->percentage                  = $percentage;
    }

    public static function create(string $category, int $percentage): self
    {
        return new self($category, $percentage);
    }

    public function apply(Product $product): int
    {
        $originalProductPrice = $product->price()->original();
        if ($product->category() === $this->productCategoryDiscountable) {
            return intval($originalProductPrice - ($originalProductPrice * ($this->percentage / 100)));
        }

        return $originalProductPrice;
    }

    public function discountType(): string
    {
        return "{$this->percentage} %";
    }
}