<?php

declare(strict_types = 1);

namespace App\Domain\Shared;


interface BaseRepositoryInterface
{

    public function add(object $item): void;

    public function findById(int $id): ?object;

    public function findLast(): ?object;

    public function all(): array;
}