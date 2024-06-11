<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    // Route pour afficher toutes les catégories
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère toutes les catégories triées par nom en ordre ascendant
        $categories = $categoryRepository->findBy([], ["name" => "ASC"]);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Route pour créer ou éditer une catégorie
    #[Route('/category/new', name: 'new_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function new_edit(Category $category = null, Request $request, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Si la catégorie n'existe pas, créer une nouvelle instance
        if (!$category) {
            $category = new Category();
        }

        // Crée et traite le formulaire
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, persister les données
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager->persist($category); // Préparation des données
            $entityManager->flush(); // Exécution et enregistrement dans la base de données

            $flashy->success('Category added !', 'http://your-awesome-link.com');
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/new.html.twig', [
            'formAddCategory' => $form,
        ]);
    }

    // Route pour supprimer une catégorie
    #[Route('/category/{id}/delete', name: 'delete_category')]
    public function delete(Category $category, EntityManagerInterface $entityManager, FlashyNotifier $flashy)
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Supprime la catégorie et sauvegarde les modifications
        $entityManager->remove($category);
        $entityManager->flush();

        $flashy->warning('Category deleted !', 'http://your-awesome-link.com');
        return $this->redirectToRoute('app_category');
    }

    // Route pour afficher une catégorie spécifique
    #[Route('/category/{id}', name: 'show_category')]
    public function show(Category $category): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}