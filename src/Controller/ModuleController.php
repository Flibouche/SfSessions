<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    // Route pour afficher tous les modules
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository, Request $request): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère la page actuelle pour la pagination
        $page = $request->query->getInt('page', 1);

        // Récupère les modules paginés
        $modules = $moduleRepository->paginateModules($page);

        // Affiche la page d'index des modules avec la pagination
        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }

    // Route pour créer ou éditer un module
    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function new_edit(Module $module = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Si le module n'existe pas, créer une nouvelle instance
        if (!$module) {
            $module = new Module();
        }

        // Crée et traite le formulaire
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, persister les données
        if ($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData();
            $entityManager->persist($module); // Préparation des données
            $entityManager->flush(); // Exécution et enregistrement dans la base de données

            return $this->redirectToRoute('app_module');
        }

        // Affiche le formulaire pour créer ou éditer un module
        return $this->render('module/new.html.twig', [
            'formAddModule' => $form,
        ]);
    }

    // Route pour supprimer un module (accessible uniquement pour les administrateurs)
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(Module $module, EntityManagerInterface $entityManager)
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Supprime le module et sauvegarde les modifications
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_module');
    }

    // Route pour afficher les détails d'un module spécifique
    #[Route('/module/{id}', name: 'show_module')]
    public function show(Module $module): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Affiche les détails du module spécifié
        return $this->render('module/show.html.twig', [
            'module' => $module
        ]);
    }
}