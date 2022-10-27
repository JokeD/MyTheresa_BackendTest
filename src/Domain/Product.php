<?php

declare(strict_types = 1);

namespace App\Domain;


use App\Domain\Shared\TriggerEvents;

class Product implements \JsonSerializable
{
    private int $id;
    private string $sku;
    private string $category;
    private string $name;
    private Price $price;
    private array $discounts;

    use TriggerEvents;

    public function __construct(
        string $sku,
        string $category,
        string $name,
        Price $price,
        array $discounts = []
    )
    {
        $this->category  = $category;
        $this->sku       = $sku;
        $this->name      = $name;
        $this->price     = $price;
        $this->discounts = $discounts;

        $this->record(new ProductWasCreated($this));
    }

    /**
     * @return string
     */
    public function sku(): string
    {
        return $this->sku;
    }

    public function category(): string
    {
        return $this->category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function discounts(): array
    {
        return $this->discounts;
    }

    public function priceFinal(): int
    {
        if (empty($this->discounts)) {
            return $this->price()->original();
        }

        return $this->calculateBiggerDiscount()['price'];
    }

    public function appliedDiscountType(): string|null
    {

        return !empty($this->discounts()) ? $this->calculateBiggerDiscount()['type'] : null;
    }

    private function calculateBiggerDiscount(): array
    {
        $applicableDiscounts = [];

        foreach ($this->discounts as $discount) {

            $priceDiscountApplied  = is_null($discount) ? $this->price()->original() : $discount->apply($this);
            $applicableDiscounts[] =
                [
                    'price' => $priceDiscountApplied,
                    'type'  => $priceDiscountApplied !== $this->price()->original() ? $discount->discountType() : null
                ];
        }
        usort($applicableDiscounts, fn($a, $b) => $a['price'] <=> $b['price']);
        return $applicableDiscounts[0];
    }

    public function jsonSerialize() : mixed
    {
        return [
            'sku'      => $this->sku(),
            'name'     => $this->name(),
            'category' => $this->category(),
            'price'    => [
                'original'            => $this->price()->original(),
                'final'               => $this->priceFinal(),
                'discount_percentage' => $this->appliedDiscountType(),
                'currency'            => $this->price()->currency()
            ]
        ];
    }
}