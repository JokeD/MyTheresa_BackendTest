<?php

declare(strict_types = 1);

namespace App\Ui\Http;


use Symfony\Component\HttpFoundation\Request;


abstract class RequestValidator
{
    public function validateContentType(Request $request): bool
    {
        return $request->getContentType() === 'json';
    }

    public function validateJsonContent(Request $request): bool
    {
        $requestContent = $request->getContent();

        return !empty($requestContent) && is_string($requestContent) &&
            is_array(json_decode($requestContent, true));
    }

    public function validate(Request $request): bool
    {
        return $this->validateContentType($request) && $this->validateJsonContent($request);
    }
}