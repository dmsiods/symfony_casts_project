<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController
{
    #[Route('/question', 'question')]
    public function question(Request $request): Response
    {
        return new Response('Hello from question and QuestionController');
    }

    #[Route('/question/{slug}', 'show_question')]
    public function show_question($slug): Response
    {
        return new Response('Hello from question and QuestionController ' . $slug);
    }
}
