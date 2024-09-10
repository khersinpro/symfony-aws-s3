<?php

namespace App\Controller\Admin;

use App\DTO\PaginationDTO;
use App\Repository\ImageRepository;
use App\Service\ImageUploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin.')]
class AdminController extends AbstractController
{
    #[Route(name: 'index')]
    public function index(
        #[MapQueryString] ?PaginationDTO $paginationDTO,
        ImageUploaderService $imageUploaderService, 
        ImageRepository $imageRepository
    ): Response
    {
        $images = $imageRepository->findAllPaginatedImages(
            $paginationDTO->page ?? 1,
            $paginationDTO->limit ?? 6
        );

        foreach ($images as $image) {
            $image->setUrl($imageUploaderService->generatePresignedUrl($image->getFilePath()));
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminAdminController',
            'images' => $images,
        ]);
    }
}
