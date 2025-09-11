<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GameController extends AbstractController
{
    private GameRepository $gameRepository;
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(GameRepository $gameRepository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->gameRepository = $gameRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        $games = $this->gameRepository->findAll();

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/game/create', name: 'app_create_game')]
    public function create(Request $request): Response
    {
        $game = new Game();
        $form = $this->formFactory->create(GameType::class, $game);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($game);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_game');
        }

        return $this->render('game/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
