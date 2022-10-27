<?php

declare(strict_types = 1);

namespace App\Domain\Shared;

trait TriggerEvents
{
    private array $recordedEvents;

    public function record(DomainEvent $aDomainEvent): void
    {
        $this->recordedEvents[] = $aDomainEvent;
    }

    public function releaseEvents(): array
    {
        $currentRecordedEvents = $this->recordedEvents;
        $this->recordedEvents  = [];
        return $currentRecordedEvents;
    }
}