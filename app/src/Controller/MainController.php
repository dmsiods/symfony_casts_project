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
    public function app_main(Request $request, EntityManagerInterface $em): RedirectResponse|Response
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

        return $this->render('main/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit-quote/{id}', name: 'editQuote')]
    public function editQuote(Request $request, EntityManagerInterface $em, int $id): RedirectResponse|Response
    {
        $quoteRepository = $em->getRepository(Quote::class);
        $quote = $quoteRepository->findOneBy(['id' => $id]);

        if (!$quote) {
            throw $this->createNotFoundException(
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

    #[Route('/get-quotes', name: 'getQuotes')]
    public function getQuotes(Request $request, EntityManagerInterface $em): Response
    {
        $quotes = $em->getRepository(Quote::class)->findAll();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $jsonData = array();
            $idx = 0;
            foreach($quotes as $quote) {
                $temp = array(
                    'historian' => $quote->getHistorian(),
                    'year' => $quote->getYear(),
                );
                $jsonData[$idx++] = $temp;
            }
            return new JsonResponse($jsonData);
        } else {
            return $this->render('main/ajax.html.twig');
        }
    }

    #[Route('/test-add', name: 'testAddQuote')]
    public function testAddQuote(Request $request): Response
    {
        return $this->render('main/ajaxAdd.html.twig');
    }

    #[Route('/add-quote', name: 'addQuote')]
    public function addQuote(Request $request, EntityManagerInterface $em): Response
    {
        $params = $request->request->get('params');

        if ($request->isXmlHttpRequest()) {
            $new_quote = new Quote();
            $new_quote->setQuote($params['param1']);
            $new_quote->setHistorian($params['param2']);
            $new_quote->setYear($params['param3']);

            $em->persist($new_quote);
            $em->flush();

            return new JsonResponse(['massage' => 'hello!']);
        }
//        $this->addFlash('success', 'Добавлено!');

        return $this->redirectToRoute('getQuotes');
    }

    /**
     * @Route("/dim")
     */
    public function showCustom(): Response
    {
//        dd();

        return $this->render('dim/showCustom.html.twig', [
            'smth' => 'content of smth property',
            'prop_arr' => ['aaa', 'bbb', 'ccc']
        ]);
    }
}
