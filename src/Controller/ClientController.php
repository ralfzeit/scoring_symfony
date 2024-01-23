<?php
/*
 * Контроллер клиентов
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ScoringService;
use Twig\Environment;

/**
 * Класс контроллера клиентов
 */
#[Route('/client')]
class ClientController extends AbstractController
{
    /**
     * Отображает постраничный список клиентов (отсортированы по имени)
     */
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(Request $request, Environment $twig, ClientRepository $clientRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $clientRepository->getClientPaginator($offset);

        return $this->render('client/index.html.twig', [
            'clients' => $paginator,
            'previous' => $offset - ClientRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + ClientRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * Регистрация клиента
     */
    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ScoringService $sc): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $client->setScore($sc->calculateScoreReg(
                    $client->getPhone(), 
                    $client->getEmail(), 
                    $client->getEducationId()->getTitle(), 
                    $client->isAgree()
                    )
                );
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * Просмотр информации о клиенте
     */
    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * Редактирование клиента
     */
    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * Удаление клиента
     */
    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
