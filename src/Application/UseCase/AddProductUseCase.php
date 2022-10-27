<?php

declare(strict_types = 1);


namespace App\Application\UseCase;


use App\Application\Command\AddProductCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class AddProductUseCase
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus
    )
    {
        $this->messageBus = $messageBus;
    }

    public function execute(array $products): void
    {
        foreach ($products as $product) {
            try {
                $this->messageBus->dispatch(new AddProductCommand(
                    $product->price,
                    $product->category,
                    $product->name,
                    $product->sku
                ));
            } catch (\Exception $e) {
                throw new \RuntimeException($e->getMessage(), 400);
            }
        }
    }
}