<?php


namespace App\Application\UseCase;


use App\Domain\DiscountPercentageByCategory;
use App\Domain\DiscountPercentageBySku;
use App\Domain\Price;
use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;

class GetProductsUseCase
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     * @throws \App\Domain\NotAllowedCurrencyException
     * Improvement move the construction of the product discounts
     * to a UseCase / Service and call a factory who can build all
     * the discounts rules instead of being hardcoded here
     */
    public function execute(
        int $priceLessThan,
        ?array $orderBy = null,
        $limit = null,
        $offset = null
    ): array
    {

        if (!$priceLessThan) {
            $products = $this->productRepository->findBy([], $orderBy, $limit, $offset);
        } else {
            $products = $this->productRepository->findByPriceLessThan($priceLessThan, $limit, $offset);
        }

        $appliedDiscountsProducts = [];

        foreach ($products as $product) {
            $appliedDiscountsProducts[] = new Product(
                $product->sku(),
                $product->category(),
                $product->name(),
                Price::create($product->price()->original(), $product->price()->currency()),
                [
                    DiscountPercentageByCategory::create('boots', 30),
                    DiscountPercentageBySku::create('000003', 15),
                ]
            );
        }
        return $appliedDiscountsProducts;
    }
}