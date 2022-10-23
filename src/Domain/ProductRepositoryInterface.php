<?php


namespace App\Domain;


interface ProductRepositoryInterface
{
    public function update(Product $product): void;

    public function findByPriceLessThanAndCategory(int $priceLessThan, string $category = null, $limit = 5): ?array;
}