<?php


namespace App\Domain\Shared;


interface EventRepositoryInterface
{
    public function append(Event $event): void;
}