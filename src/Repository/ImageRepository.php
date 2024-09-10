<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Image>
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Image::class);
    }

    public function findAllPaginatedImages(int $page, int $limit = 6): PaginationInterface
    {
        $query = $this
        ->createQueryBuilder('i')
        ->setMaxResults($limit)
        ->setFirstResult(($page - 1) * $limit)
        ->orderBy('i.uploadedAt', 'DESC')
        ->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }
}
