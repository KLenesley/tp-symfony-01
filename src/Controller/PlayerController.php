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
    #[Route('/player/', name: 'app_player')]
    public function index(PlayerRepository $playerRepository): Response
    {
        $players = $playerRepository->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }

    #[Route('/player/create/validate', name: 'app_create_player_validate')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $name = $_GET['name'];
        $player = new Player();
        $player->setName($name);

        $entityManager->persist($player);
        $entityManager->flush();

        return new Response('Player created with id '.$player->getId());
    }

    #[Route('/player/create', name: 'app_player_create')]
    public function createPlayer(): Response
    {
        return $this->render('player/create.html.twig');
    }
}