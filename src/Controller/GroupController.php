<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GroupController extends AbstractController
{
    private GroupRepository $groupRepository;
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(GroupRepository $groupRepository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->groupRepository = $groupRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    #[Route('/group', name: 'app_group')]
    public function index(): Response
    {
        $groups = $this->groupRepository->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $groups
        ]);
    }

    #[Route('/group/create', name: 'app_create_group')]
    public function create(Request $request): Response
    {
        $group = new Group();
        $form = $this->formFactory->create(GroupType::class, $group);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($group);
            $this->entityManager->flush();
            
            return $this->redirectToRoute('app_group');
        }

        return $this->render('group/create.html.twig', ['form' => $form->createView()]);
    }
}
