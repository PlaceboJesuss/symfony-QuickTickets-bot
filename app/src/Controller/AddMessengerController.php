<?php

namespace App\Controller;

use App\DTO\CreateMessengerDto;
use App\Services\MessengerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddMessengerController
{
    #[Route('/api/messenger', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        MessengerService $service
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $dto = new CreateMessengerDto(
            $data['type'] ?? null,
            $data['token'] ?? null,
            $data['name'] ?? null,
        );

        // валидация
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string)$errors], 400);
        }

        try {
            $messenger = $service->create($dto);
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        }

        return new JsonResponse([
            'id' => $messenger->getId(),
            'type' => $messenger->getType(),
            'token' => $messenger->getToken()
        ], 201);
    }
}
