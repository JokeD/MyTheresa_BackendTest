<?php

declare(strict_types = 1);

namespace App\Application\Handlers;


use App\Application\Command\AddProductCommand;
use App\Domain\Price;
use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;

class AddProductHandler
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(AddProductCommand $addProductCommand): void
    {
        $this->productRepository->add(
            new Product(
                $addProductCommand->sku(),
                $addProductCommand->category(),
                $addProductCommand->name(),
                Price::create($addProductCommand->priceOriginal(), $addProductCommand->priceCurrency()),
            )
        );
    }
}