<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VinylController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response('homepage in vinyl controller!');
    }

    #[Route('/browse/{slug?}')]
    public function browse(?string $slug): Response
    {
        if (!$slug) {
            $title = 'All genres';
        } else {
            $title = 'Genre: ' . ucwords(str_replace('-', ' ', $slug));
        }

        return new Response( $title);
    }
}
