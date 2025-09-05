<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    private PlayerRepository $playerRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $entityManager)
    {
        $this->playerRepository = $playerRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/player/', name: 'app_player')]
    public function index(): Response
    {
        $players = $this->playerRepository->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }

    #[Route('/player/create/validate', name: 'app_create_player_validate')]
    public function create(): Response
    {
        $name = $_GET['name'];
        $player = new Player();
        $player->setName($name);
        $player->setExperience(0);

        $this->entityManager->persist($player);
        $this->entityManager->flush();

        return new Response('Player created with id '.$player->getId());
    }

    #[Route('/player/create', name: 'app_player_create')]
    public function createPlayer(): Response
    {
        return $this->render('player/create.html.twig');
    }

    #[Route('/player/update/{id}/validate', name:'app_update_player_validate')]
    public function update(int $id): Response
    {
        // Logic to update a player would go here
    }

    #[Route('/player/update/{id}', name: 'app_update_player')]
    public function updatePlayer(): Response
    {
        return $this->render('player/update.html.twig');
    }

    #[Route('/player/delete/{id}/validate', name:'app_delete_player_validate')]
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

    #[Route('/player/delete/{id}', name:'app_delete_player')]
    public function deletePlayer(int $id): Response
    {
        return $this->render('player/delete.html.twig');
    }
}