<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategoryController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;
    private FormFactoryInterface $formFactory;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'app_create_category')]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->formFactory->create(CategoryType::class, $category);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/category/delete/{id}', name: 'app_delete_category')]
    public function delete(int $id): Response
    {
        $category = $this->categoryRepository->find($id);
        if ($category) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            return new Response('Category deleted');
        }
        return new Response('Category not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/category/update/{id}', name: 'app_update_category')]
    public function update(int $id, Request $request): Response
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return new Response('Category not found', Response::HTTP_NOT_FOUND);
        }

        $form = $this->formFactory->create(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/update.html.twig', ['form' => $form->createView()]);
    }
}
