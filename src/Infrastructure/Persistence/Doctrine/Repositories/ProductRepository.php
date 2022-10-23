<?php

declare(strict_types = 1);


namespace App\Infrastructure\Persistence\Doctrine\Repositories;


use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->em = $this->getEntityManager();
    }

    public function update(Product $product): void
    {
        $this->add($product);
    }

    public function findByPriceLessThanAndCategory(int $priceLessThan = null, string $category = null, $limit = 5): ?array
    {
        $qb = $this->em
            ->createQueryBuilder()
            ->select('j')
            ->from(Product::class, 'j');

        if (!is_null($priceLessThan)) {
            $qb
                ->andWhere("j.price.original < :price_identifier")
                ->setParameter('price_identifier', $priceLessThan);
        }

        if (!is_null($category)) {
            $qb
                ->andWhere("j.category = :category_identifier")
                ->setParameter('category_identifier', $category);
        }

        return $qb->getQuery()
            ->setMaxResults($limit)
            ->getResult();
    }
}