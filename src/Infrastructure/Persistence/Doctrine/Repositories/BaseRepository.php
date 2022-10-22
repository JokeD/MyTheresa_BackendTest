<?php

declare(strict_types = 1);


namespace App\Infrastructure\Persistence\Doctrine\Repositories;


use App\Domain\Shared\BaseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class BaseRepository extends ServiceEntityRepository implements BaseRepositoryInterface
{

    public function all(): array
    {
        return $this->findAll();
    }

    public function findById(int $id): object|null
    {
        return $this->find($id);
    }

    public function findLast(): ?object
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }

    public function add(object $item): void
    {
        $this->em->persist($item);
    }
}