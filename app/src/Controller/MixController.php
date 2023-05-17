<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\VinylMix;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MixController extends AbstractController
{
    #[Route('mix/new')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $mix = new VinylMix();
        $mix->setTitle('Do you remember... Phil Collins?');
        $mix->setDescription('description 1');
        $mix->setGenre('pop');
        $mix->setTrackCount(rand(5, 20));
        $mix->setVotes(rand(-50, 50));

        $entityManager->persist($mix);
        $entityManager->flush();

        return new Response(sprintf('Mix %d has %d tracks (saved)', $mix->getId(), $mix->getTrackCount()));
    }
}
