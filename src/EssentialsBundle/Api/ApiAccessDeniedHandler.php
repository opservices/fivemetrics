<?php

namespace EssentialsBundle\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class ApiAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $attribute = $accessDeniedException->getAttributes();

        if ('ROLE_PAYMENT_VERIFIED' != $attribute[0]) {
            return $accessDeniedException;
        }

        return new JsonResponse(
            [
                'type'   => 'access_denied',
                'title'  => 'Access denied',
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'errors' => ['Payment was not completed'],
            ],
            JsonResponse::HTTP_FORBIDDEN,
            [
                'Content-Type' => 'application/problem+json',
                'Content-Language' => 'en'
            ]
        );
    }
}
