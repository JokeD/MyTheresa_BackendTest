<?php


namespace App\Tests;


use App\Application\UseCase\AddProductUseCase;
use App\Application\UseCase\GetProductsUseCase;
use App\Domain\Product;
use App\Infrastructure\Persistence\Doctrine\Repositories\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainIntegrationTest extends WebTestCase
{
    private ?object $transport;

    private string $dummyProducts;

    protected function setUp(): void
    {
        self::bootKernel([
            'environment' => 'test',
            'debug'       => false,
        ]);

        $this->transport = static::getContainer()->get('command_bus');
        $this->dummyProducts = '{ 
            "products": [
                {
                    "sku": "000001",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 89000
                },
                {
                    "sku": "000002",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 99000
                }
            ]
        }';
    }

    public function testAddProductUseCase()
    {

        $productRepository = static::getContainer()->get(ProductRepository::class);

        $this->assertEmpty($productRepository->findLast());

        $addProductUseCase = new AddProductUseCase($this->transport);

        $addProductUseCase->execute((json_decode($this->dummyProducts))->products);

        $this->assertNotEmpty($productRepository->findLast());
    }

    public function testGetProductsUseCase()
    {
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $addProductUseCase = new AddProductUseCase($this->transport);
        $addProductUseCase->execute((json_decode($this->dummyProducts))->products);

        $listProductUseCase = new GetProductsUseCase($productRepository);
        $products = $listProductUseCase->execute();

        $this->assertNotEmpty($products);

        foreach ($products as $product) {
            $this->assertInstanceOf(Product::class,$product);
        }

        $productLessThan = $listProductUseCase->execute(100000);

        $this->assertCount(2,$productLessThan);

        $productLessThanAndCategory = $listProductUseCase->execute(99000,'boots');

        $this->assertCount(1,$productLessThanAndCategory);
    }

}