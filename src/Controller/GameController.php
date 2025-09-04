<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $info = ['Bonjour', 'Bienvenue', 'A', 'Symfony', 'Twig'];
        return $this->render('index.html.twig', [
            'infos' => $info
        ]);
    }

    #[Route('/go/{id}', name: 'app_start')]
    public function start(int $id): Response
    {
        $info = "La partie n$id est lancé !";
        return new Response($info);
    }

    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        return $this->index();
    }
}

?>