<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy([], ["title" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employe = $form->getData();
            // Prepare PDO
            $entityManager->persist($employe);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
        ]);
    }

    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }


    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session): Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
