<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\TestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function app_main(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quote = $form->getData();

            $em->persist($quote);
            $em->flush();

            $this->addFlash('success', 'Добавлено!');

            return $this->redirectToRoute('app_main');
        }
//        $data = $request->request->all();
//
//        dump($data['test_form']['title']);

        return $this->render('main/index.html.twig', [
            'form' => $form->createView()
        ]);

        //        phpinfo();

//        return $this->json([
//            'message' => 'Welcome to your new controller!',
//            'path' => 'src/Controller/MainController.php',
//        ]);
    }

    #[Route('/edit-quote/{quote}', name: 'editQuote')]
    public function editQuote(Request $request, EntityManagerInterface $em, Quote $quote)
    {
//        $quoteRepository = $em->getRepository(Quote::class);
//        $quote = $quoteRepository->findOneBy(['id' => $id]);

        if (!$quote) {
            throw $this->createNotFoundException(
//                sprintf('Такой страницы не найдено с id = "%s"', $id));
                sprintf('Такой страницы не найдено с id')
            );
        }

        $form = $this->createForm(TestFormType::class, $quote);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Обновлено!');

            return $this->redirectToRoute('editQuote', ['quote' => $quote->getId()]);
        }

        return $this->render('main/editQuote.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
