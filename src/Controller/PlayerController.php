<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayerController extends AbstractController
{
    #[Route('/player/create', name: 'create_player')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $player = new Player();
        $player->setName('Miss Fortune');

        $entityManager->persist($player);
        $entityManager->flush();

        return new Response('Player created with id '.$player->getId());
    }
}