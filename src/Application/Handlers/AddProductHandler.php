<?php

declare(strict_types = 1);

namespace App\Application\Handlers;


use App\Application\Command\AddProductCommand;
use App\Domain\Price;
use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;
use App\Domain\Shared\DomainEventDispatcherInterface;

class AddProductHandler
{
    private ProductRepositoryInterface $productRepository;
    private DomainEventDispatcherInterface $domainEventDispatcher;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        DomainEventDispatcherInterface $domainEventDispatcher
    )
    {
        $this->productRepository     = $productRepository;
        $this->domainEventDispatcher = $domainEventDispatcher;
    }

    public function __invoke(AddProductCommand $addProductCommand): void
    {
        $product = new Product(
            $addProductCommand->sku(),
            $addProductCommand->category(),
            $addProductCommand->name(),
            Price::create(
                $addProductCommand->priceOriginal(),
                $addProductCommand->priceCurrency()
            ),
        );
        $this->productRepository->add($product);
        $this->domainEventDispatcher->dispatchAll($product->releaseEvents());

    }
}