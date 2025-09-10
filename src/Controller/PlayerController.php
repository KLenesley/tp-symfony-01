<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    private PlayerRepository $playerRepository;
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    #[Route('/player', name: 'app_player')]
    public function index(): Response
    {
        $players = $this->playerRepository->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }

    #[Route('/player/create', name: 'app_create_player')]
    public function create(Request $request): Response
    {
        $player = new Player();
        $form = $this->formFactory->create(PlayerType::class, $player);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($player);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_player');
        }

        return $this->render('player/create.html.twig', ['form' => $form->createView()]);
    }

    public function createPlayer(): Response
    {
        return $this->render('player/create.html.twig');
    }

    #[Route('/player/delete/{id}/validate', name: 'app_delete_player_validate')]
    public function delete(int $id): Response
    {
        $player = $this->playerRepository->find($id);
        if ($player) {
            $this->entityManager->remove($player);
            $this->entityManager->flush();
            return new Response('Player deleted');
        }
        return new Response('Player not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/player/delete/{id}', name: 'app_delete_player')]
    public function deletePlayer(int $id): Response
    {
        return $this->render('player/delete.html.twig');
    }
}
