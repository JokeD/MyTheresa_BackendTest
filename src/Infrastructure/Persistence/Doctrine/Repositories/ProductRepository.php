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

    public function findByPriceLessThan(int $priceLessThan, ?int $limit, ?int $offset): ?array
    {
        return $this->em->createQueryBuilder()->select('j')
            ->from(Product::class, 'j')
            ->where("j.price.original < :identifier")
            ->orderBy('j.id', 'ASC')
            ->setParameter('identifier', $priceLessThan)
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }

}