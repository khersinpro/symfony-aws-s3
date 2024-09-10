<?php
namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Filename cannot be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Filename cannot exceed {{ limit }} characters."
    )]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "URL cannot be blank.")]
    #[Assert\Url(message: "The URL format is invalid.")]
    private ?string $url = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Upload date is required.")]
    #[Assert\Type("\DateTimeImmutable", message: "The date must be a valid DateTimeImmutable object.")]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "File path cannot be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "File path cannot exceed {{ limit }} characters."
    )]
    private ?string $filePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }
}
