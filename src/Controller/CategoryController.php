<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ["name" => "ASC"]);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'new_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function new_edit(Category $category = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$category) {
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            // Prepare PDO
            $entityManager->persist($category);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/new.html.twig', [
            'formAddCategory' => $form,
        ]);
    }

    #[Route('/category/{id}/delete', name: 'delete_category')]
    public function delete(category $category, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_category');
    }

    #[Route('/category/{id}', name: 'show_category')]
    public function show(category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}