<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Service responsible for handling validation errors.
 * Validates entities or DTOs and returns a JSON response with error messages if validation fails.
 */
class ValidationErrorService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validates an entity or DTO and returns a JsonResponse with error messages if validation fails.
     *
     * @param object $entity The entity or DTO to be validated.
     * @param array|null $groups Optional validation groups.
     *
     * @return JsonResponse|null Returns a JsonResponse with validation error messages if there are errors, or null if the entity is valid.
     */
    public function validate($entity, array $groups = null): ?JsonResponse
    {
        $errors = $this->validator->validate($entity, null, $groups);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        return null;
    }
}