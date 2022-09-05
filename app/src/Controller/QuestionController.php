<?php

namespace App\Controller;

use App\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question', 'question')]
    public function question(Request $request): Response
    {
        echo 'Hello from question and QuestionController';

        return $this->render('base.html.twig', []);
    }

    #[Route('/question/{slug}', 'show_question')]
    public function show_question($slug): Response
    {
        $answer = 'Hello from question and QuestionController';
//        return new Response(
//            sprintf($answer . ' ' . '%s', ucwords(str_replace('_', '-', $slug), '-'))
//        );
        return $this->render('question/show.html.twig', [
            'slug' => ucwords(str_replace('_', '-', $slug), '-'),
            'random_array' => ['qwerty', 'Dima', 'blablabla'],
            'answer' => $answer,
        ]);
    }
}
