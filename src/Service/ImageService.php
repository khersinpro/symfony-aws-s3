<?php

namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Service responsible for processing image uploads.
 * Manages the upload, validation, and persistence of the Image entity.
 */
class ImageService
{
    public function __construct(
        private ImageUploaderService $imageUploader, 
        private EntityManagerInterface $em, 
        private ValidationErrorService $validationErrorService
    ) {
    }

    /**
     * Processes the image upload.
     *
     * Uploads the image, validates the Image entity, and persists it to the database.
     * If validation fails, returns a JsonResponse with error details.
     *
     * @param UploadedFile $uploadedFile The file being uploaded.
     *
     * @return JsonResponse|null Returns null if the image is successfully processed, or a JsonResponse with errors if validation fails.
     */
    public function processImageUpload(UploadedFile $uploadedFile): ?JsonResponse
    {
        $filePath = $this->imageUploader->upload($uploadedFile);
        $imageUrl = $this->imageUploader->generateUrl($filePath);

        $image = new Image();
        $image->setFilename($uploadedFile->getClientOriginalName());
        $image->setUrl($imageUrl);
        $image->setFilePath($filePath);
        $image->setUploadedAt(new \DateTimeImmutable());

        $errorResponse = $this->validationErrorService->validate($image);
        if ($errorResponse !== null) {
            return $errorResponse; 
        }

        $this->em->persist($image);
        $this->em->flush();

        return null;
    }
}
