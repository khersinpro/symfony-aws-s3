<?php

namespace App\Controller\Api;

use App\DTO\ImageUploadDTO;
use App\Service\ImageService;
use App\Service\ImageUploaderService;
use App\Service\ValidationErrorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/image', name: 'api.image.')]
class ImageController extends AbstractController
{
    public function __construct(
        private ImageUploaderService $imageUploader, 
        private ImageService $imageService,
        private EntityManagerInterface $em
    )
    {
    }

    #[Route(name: 'index', methods: ['POST'])]
    public function index(
        Request $request,
        ValidationErrorService $validationErrorService
    ): Response
    {
        $uploadedFiles = $request->files->get('images', []);

        if (empty($uploadedFiles)) {
            return new JsonResponse(['error' => 'No files uploaded'], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        if ($uploadedFiles instanceof UploadedFile) {
            $uploadedFiles = [$uploadedFiles]; 
        }
    
        $imageUploadDTO = new ImageUploadDTO($uploadedFiles);

        $errorResponse = $validationErrorService->validate($imageUploadDTO);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        try {
            foreach ($uploadedFiles as $uploadedFile) {
                $imageErrorResponse = $this->imageService->processImageUpload($uploadedFile);

                if ($imageErrorResponse !== null) {
                    return $imageErrorResponse;
                }
            }

            return new JsonResponse(['message' => 'Image uploaded successfully'], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Unable to upload file', 'details' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
