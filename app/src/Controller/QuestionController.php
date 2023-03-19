<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
//    /**
//     * @Route("/")
//     */
    public function homepage(): Response
    {
        return new Response('homepage controller worked!');
    }

    #[Route('/question', 'question')]
    public function question(Request $request): Response
    {
        echo 'Hello from question and QuestionController';

        return $this->render('base.html.twig', []);
    }

    /**
     * @Route("/questions/{slug}")
     */
    public function show(string $slug): Response
    {
        $answer = 'Hello from question and QuestionController';

        return new Response(sprintf('%s "%s"!', $answer, ucwords(str_replace('-', ' ', $slug))));

//        return $this->render('question/show.html.twig', [
//            'slug' => ucwords(str_replace('_', '-', $slug), '-'),
//            'random_array' => ['qwerty', 'Dima', 'blablabla'],
//            'answer' => $answer,
//        ]);
    }
}
