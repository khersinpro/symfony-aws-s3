<?php

namespace App\Service;

use Aws\S3\S3Client;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Service responsible for uploading images to a cloud storage service (e.g., AWS S3).
 * It handles file uploads, generating public URLs, and generating presigned URLs for secure access.
 */
class ImageUploaderService
{
    private FilesystemOperator $storage;
    private S3Client $s3Client;
    private string $bucketName;
    private string $defaultRegion;

    public function __construct(FilesystemOperator $s3Storage, S3Client $s3Client, string $bucketName, string $defaultRegion)
    {
        $this->storage = $s3Storage;
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
        $this->defaultRegion = $defaultRegion;
    }

    /**
     * Uploads an image to the cloud storage and returns the file path.
     *
     * @param UploadedFile $file The file to be uploaded.
     *
     * @return string The file path where the image is stored.
     */
    public function upload(UploadedFile $file): string
    {
        $filename = sprintf('%s.%s', uniqid(), $file->guessExtension());
        $filePath = 'uploads/' . $filename;

        $this->storage->write($filePath, file_get_contents($file->getPathname()));

        return $filePath;
    }

    /**
     * Generates a presigned URL for secure access to the uploaded file.
     *
     * @param string $filePath The path of the file in the S3 bucket.
     * @param int $expirationInSeconds The expiration time of the presigned URL in seconds (default: 3600 seconds).
     *
     * @return string The presigned URL that allows access to the file for a limited time.
     */
    public function generatePresignedUrl(string $filePath, int $expirationInSeconds = 3600): string
    {
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucketName,
            'Key'    => $filePath,
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, '+' . $expirationInSeconds . ' seconds');

        return (string) $request->getUri();
    }

    /**
     * Generates a public URL to access the uploaded file.
     *
     * @param string $filePath The path of the file in the S3 bucket.
     *
     * @return string The public URL that can be used to access the file.
     */
    public function generateUrl(string $filePath): string
    {
        return sprintf('https://%s.s3.%s.amazonaws.com/%s',
            $this->bucketName,
            $this->defaultRegion,
            $filePath
        );
    }
}
