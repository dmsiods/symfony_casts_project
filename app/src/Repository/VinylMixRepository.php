<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\VinylMix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class VinylMixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinylMix::class);
    }

    public function createOrderedByVotesQueryBuilder(string $genre = null): QueryBuilder
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

        return $qb;
    }
}
