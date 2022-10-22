<?php

declare(strict_types = 1);


namespace App\Domain;


interface Discountable
{
    public function apply(Product $product): int;

    public function discountType(): string;
}