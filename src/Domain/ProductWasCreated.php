<?php

declare(strict_types = 1);

namespace App\Domain;

use App\Domain\Shared\DomainEvent;

class ProductWasCreated implements DomainEvent
{
    private \DateTimeImmutable $occurredOn;
    private Product $product;

    public function __construct(Product $product)
    {
        $this->occurredOn = new \DateTimeImmutable();
        $this->product    = $product;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function data(): Product
    {
        return $this->product;
    }

    public function jsonSerialized(): bool|string
    {
        return json_encode($this->data());
    }
}