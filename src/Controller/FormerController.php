<?php

namespace App\Controller;

use App\Entity\Former;
use App\Form\FormerType;
use App\Repository\FormerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormerController extends AbstractController
{
    // Route pour afficher tous les formateurs
    #[Route('/former', name: 'app_former')]
    public function index(FormerRepository $formerRepository): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère tous les formateurs triés par nom en ordre ascendant
        $formers = $formerRepository->findBy([], ["name" => "ASC"]);
        return $this->render('former/index.html.twig', [
            'formers' => $formers,
        ]);
    }

    // Route pour créer ou éditer un formateur
    #[Route('/former/new', name: 'new_former')]
    #[Route('/former/{id}/edit', name: 'edit_former')]
    public function new_edit(Former $former = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Si le formateur n'existe pas, créer une nouvelle instance
        if (!$former) {
            $former = new Former();
        }

        // Crée et traite le formulaire
        $form = $this->createForm(FormerType::class, $former);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, persister les données
        if ($form->isSubmitted() && $form->isValid()) {
            $former = $form->getData();
            $entityManager->persist($former); // Préparation des données
            $entityManager->flush(); // Exécution et enregistrement dans la base de données

            return $this->redirectToRoute('app_former');
        }

        return $this->render('former/new.html.twig', [
            'formAddFormer' => $form,
        ]);
    }

    // Route pour supprimer un formateur
    #[Route('/former/{id}/delete', name: 'delete_former')]
    public function delete(Former $former, EntityManagerInterface $entityManager)
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Supprime le formateur et sauvegarde les modifications
        $entityManager->remove($former);
        $entityManager->flush();

        return $this->redirectToRoute('app_former');
    }

    // Route pour afficher un formateur spécifique
    #[Route('/former/{id}', name: 'show_former')]
    public function show(Former $former): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('former/show.html.twig', [
            'former' => $former
        ]);
    }
}