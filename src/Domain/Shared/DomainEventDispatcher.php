<?php

declare(strict_types = 1);

namespace App\Domain\Shared;


class DomainEventDispatcher implements DomainEventDispatcherInterface
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function dispatchAll(array $aDomainEvents): void
    {
        foreach ($aDomainEvents as $aDomainEvent) {
            $this->eventRepository->append(
                new Event(
                    get_class($aDomainEvent),
                    $aDomainEvent->jsonSerialized(),
                    $aDomainEvent->occurredOn()
                )
            );
        }
    }
}