<?php

declare(strict_types = 1);

namespace App\Ui\Http;


use App\Application\UseCase\AddProductUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


final class AddProductController extends RequestValidator
{
    private AddProductUseCase $addProductUseCase;

    public function __construct(AddProductUseCase $addProductUseCase)
    {
        $this->addProductUseCase = $addProductUseCase;
    }

    public function post(Request $request): JsonResponse
    {
        return $this->persists($request);
    }

    private function persists(Request $request): JsonResponse
    {
        if (!$this->validate($request)) {
            return new JsonResponse('wrong request', 400);
        }

        $productRequest = json_decode($request->getContent());

        try {
            $this->addProductUseCase->execute($productRequest->products);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }
        return new JsonResponse(['success' => $request->getMethod() . ' Products '], 200);
    }
}