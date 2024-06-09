<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sessions = $sessionRepository->findAll([[], ["startDate" => "ASC"]]);

        $currentDateTime = new \DateTime();

        $upcomingSessions = [];
        $currentSessions = [];
        $pastSessions = [];

        foreach ($sessions as $session) {
            if ($session->getStartDate() > $currentDateTime) {
                $upcomingSessions[] = $session;
            } elseif ($session->getEndDate() < $currentDateTime) {
                $pastSessions[] = $session;
            } else {
                $currentSessions[] = $session;
            }
        }

        return $this->render('home/index.html.twig', [
            'upcomingSessions' => $upcomingSessions,
            'currentSessions' => $currentSessions,
            'pastSessions' => $pastSessions,
        ]);
    }

    #[Route('/forms', name: 'app_forms')]
    public function forms(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/forms.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
