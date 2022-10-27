<?php

declare(strict_types = 1);

namespace App\Domain\Shared;


class Event
{
    private ?int $id;
    private string $name;
    private string $body;
    private \DateTime $occurredOn;

    public function __construct(string $name, string $body, \DateTime $occurredOn, int $id = null)
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->body       = $body;
        $this->occurredOn = $occurredOn;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function occurredOn(): \DateTime
    {
        return $this->occurredOn;
    }
}