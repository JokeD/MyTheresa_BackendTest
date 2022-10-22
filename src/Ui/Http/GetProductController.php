<?php

declare(strict_types = 1);

namespace App\Ui\Http;


use App\Application\UseCase\GetProductsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


final class GetProductController extends RequestValidator
{
    private GetProductsUseCase $getProductsUseCase;

    public function __construct(GetProductsUseCase $getProductsUseCase)
    {
        $this->getProductsUseCase = $getProductsUseCase;
    }

    public function get(Request $request): JsonResponse
    {
        $orderBy  = [];
        $limit    = 5;
        $offset   = 0;

        $priceLessThan  = (int)$request->get('priceLessThan') ?? null;

        $products = $this->getProductsUseCase->execute($priceLessThan, $orderBy, $limit, $offset);
        return new JsonResponse($products, 200);
    }
}