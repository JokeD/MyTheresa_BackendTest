<?php


namespace App\Domain\Shared;


interface DomainEventDispatcherInterface
{
    public function dispatchAll(array $aDomainEvents): void;
}