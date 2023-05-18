<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\VinylMix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VinylMixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinylMix::class);
    }

    /**
     * @return VinylMix[]
     */
    public function findAllOrderedByVotes(string $genre = null): array
    {
        $qb = $this
            ->createQueryBuilder('vm')
            ->orderBy('vm.votes', 'DESC')
        ;

        if ($genre) {
            $qb
                ->andWhere('vm.genre = :genre')
                ->setParameter('genre', $genre)
            ;
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
