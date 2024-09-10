<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ImageUploadDTO
{
    /**
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile[]
     */
    #[Assert\NotNull(message: "The image cannot be empty.")]
    #[Assert\All([
        new Assert\Image(
            maxSize: "5M",
            maxSizeMessage: "The maximum image size is 5MB.",
            mimeTypes: ["image/jpeg", "image/png", "image/jpg", "image/webp"],
            mimeTypesMessage: "Please upload a valid image (JPEG, PNG, JPG, WEBP)."
        )
    ])]
    public array $images;

    public function __construct(array $images)
    {
        $this->images = $images;
    }
}
