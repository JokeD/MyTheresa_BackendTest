<?php


namespace App\Domain;


interface ProductRepositoryInterface
{
    public function update(Product $product): void;

    public function findByPriceLessThan(int $priceLessThan, ?int $limit, ?int $offset): ?array;
}