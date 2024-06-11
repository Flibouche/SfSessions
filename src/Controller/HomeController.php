<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // Route pour afficher la page d'accueil avec les sessions
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère toutes les sessions triées par date de début en ordre ascendant
        $sessions = $sessionRepository->findAll([[], ["startDate" => "ASC"]]);

        // Initialise les listes pour les sessions à venir, actuelles et passées
        $currentDateTime = new \DateTime();
        $upcomingSessions = [];
        $currentSessions = [];
        $pastSessions = [];

        // Divise les sessions en sessions à venir, actuelles et passées
        foreach ($sessions as $session) {
            if ($session->getStartDate() > $currentDateTime) {
                $upcomingSessions[] = $session;
            } elseif ($session->getEndDate() < $currentDateTime) {
                $pastSessions[] = $session;
            } else {
                $currentSessions[] = $session;
            }
        }

        // Affiche la page d'accueil avec les sessions classées
        return $this->render('home/index.html.twig', [
            'upcomingSessions' => $upcomingSessions,
            'currentSessions' => $currentSessions,
            'pastSessions' => $pastSessions,
        ]);
    }

    // Route pour afficher la page de formulaires
    #[Route('/forms', name: 'app_forms')]
    public function forms(): Response
    {
        // Redirige l'utilisateur non authentifié vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Affiche la page de formulaires
        return $this->render('home/forms.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // Route pour effectuer une recherche
    #[Route('/search/{key}', name: 'app_search')]
    public function search(CategoryRepository $categoryRepository, ModuleRepository $moduleRepository, Request $request, $key = null): Response
    {
        // Récupère la clé de recherche depuis la requête
        $key = $request->query->get('search');

        // Effectue la recherche dans les catégories et les modules
        $categories = $categoryRepository->findByWord($key);
        $modules = $moduleRepository->findByWord($key);

        // Affiche les résultats de la recherche
        return $this->render('home/search.html.twig', [
            'categories' => $categories,
            'modules' => $modules
        ]);
    }
}