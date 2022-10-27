<?php

declare(strict_types = 1);


namespace App\Infrastructure\Persistence\Doctrine\Repositories;


use App\Domain\Shared\Event;
use App\Domain\Shared\EventRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    protected EntityManagerInterface $em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
        $this->em = $this->getEntityManager();
    }


    public function append(Event $event): void
    {
        $this->add($event);
    }
}