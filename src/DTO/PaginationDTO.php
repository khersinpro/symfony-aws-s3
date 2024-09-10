<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationDTO
{
    public function __construct(
        #[Assert\Positive(message:'The page must be greater than 0')]
        public readonly int $page = 1,
        #[Assert\Positive(message: 'The page must be greater than 0')]
        #[Assert\LessThan(value: 100, message: 'The page must be less than 100')]
        public readonly int $limit = 6,
    )
    {
    }
}