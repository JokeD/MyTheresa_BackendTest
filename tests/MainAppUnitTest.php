<?php

declare(strict_types = 1);

namespace App\Tests;

use App\Domain\DiscountPercentageByCategory;
use App\Domain\DiscountPercentageBySku;
use App\Domain\NotAllowedCurrencyException;
use App\Domain\Price;
use App\Domain\Product;
use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase;


class MainAppUnitTest extends TestCase
{

    private \Faker\Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Faker::create();
    }

    public function testCreatePriceWithUnvalidCurrencyShouldThrowAnException()
    {
        $this->expectException(NotAllowedCurrencyException::class);

        Price::create(20, 'DOL');
    }

    public function testProductWithCategoryDiscountShouldApply()
    {
        $sku                = '000012';
        $category           = 'Boots';
        $name               = "Boots {$this->faker->name()} {$this->faker->colorName()} ";
        $originalPrice      = 20;
        $discountPercentage = 30;

        $product = new Product(
            $sku,
            $category,
            $name,
            Price::create($originalPrice, 'EUR'),
            [DiscountPercentageByCategory::create($category, $discountPercentage)]
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(14, $product->priceFinal());
        $this->assertEquals($originalPrice, $product->price()->original());
    }

    public function testProductWithDiffCategoryDiscountShouldNotApply()
    {
        $sku                = '000012';
        $category           = 'Boots';
        $name               = "Boots {$this->faker->name()} {$this->faker->colorName()} ";
        $originalPrice      = 20;
        $discountPercentage = 30;

        $productWithUndiscountableCategory = new Product(
            $sku,
            $category,
            $name,
            Price::create($originalPrice, 'EUR'),
            [DiscountPercentageByCategory::create('Shorts', $discountPercentage)]
        );

        $this->assertEquals($originalPrice, $productWithUndiscountableCategory->priceFinal());
    }

    public function testProductWithSkuDiscountShouldApply()
    {
        $sku                = '000012';
        $category           = 'Boots';
        $name               = "Boots {$this->faker->name()} {$this->faker->colorName()} ";
        $originalPrice      = 20;
        $discountPercentage = 30;

        $product = new Product(
            $sku,
            $category,
            $name,
            Price::create($originalPrice, 'EUR'),
            [DiscountPercentageBySku::create($sku, $discountPercentage)]
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(14, $product->priceFinal());
        $this->assertEquals($originalPrice, $product->price()->original());
    }

    public function testProductWithDiffSkyDiscountShouldNotApply()
    {
        $sku                = '000012';
        $category           = 'Boots';
        $name               = "Boots {$this->faker->name()} {$this->faker->colorName()} ";
        $originalPrice      = 20;
        $discountPercentage = 30;

        $productWithUndiscountableCategory = new Product(
            $sku,
            $category,
            $name,
            Price::create($originalPrice, 'EUR'),
            [DiscountPercentageBySku::create('603013', $discountPercentage)]
        );
        $this->assertEquals($originalPrice, $productWithUndiscountableCategory->priceFinal());
    }

    public function testGivenMultipleDiscountsTheBiggerIsApplied()
    {
        $sku                = '000012';
        $category           = 'Boots';
        $name               = "Boots {$this->faker->name()} {$this->faker->colorName()} ";
        $originalPrice      = 20;

        $product = new Product(
            $sku,
            $category,
            $name,
            Price::create($originalPrice, 'EUR'),
            [
                DiscountPercentageByCategory::create($category, 60),
                DiscountPercentageBySku::create($sku, 10),
                DiscountPercentageByCategory::create($category, 30),
                DiscountPercentageBySku::create($sku, 20),
            ]
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(8, $product->priceFinal());
        $this->assertEquals($originalPrice, $product->price()->original());
        $this->assertEquals("60 %",$product->appliedDiscountType());
    }
}