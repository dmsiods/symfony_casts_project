<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
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
        $genres = ['pop', 'rock'];
        $mix->setGenre($genres[array_rand($genres)]);
        $mix->setTrackCount(rand(5, 20));
        $mix->setVotes(rand(-50, 50));

        $entityManager->persist($mix);
        $entityManager->flush();

        return new Response(sprintf('Mix %d has %d tracks (saved)', $mix->getId(), $mix->getTrackCount()));
    }

    #[Route('mix/{id}', name: 'app_mix_show')]
    public function show(int $id, VinylMixRepository $mixRepository): Response
    {
        $mix = $mixRepository->find($id);

        return $this->render('mix/show.html.twig', [
            'mix' => $mix
        ]);
    }
}
