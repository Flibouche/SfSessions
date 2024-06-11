<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Program;
use App\Entity\Session;
use App\Entity\Student;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    // Affiche toutes les sessions (accès restreint aux administrateurs)
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        // Redirige les utilisateurs non connectés vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère toutes les sessions et les affiche
        $sessions = $sessionRepository->findBy([], ["title" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    // Crée ou édite une session
    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Redirige les utilisateurs non connectés vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Crée un formulaire pour ajouter ou modifier une session
        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide, persiste la session
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();
            return $this->redirectToRoute('app_session');
        }

        // Affiche le formulaire pour ajouter ou modifier une session
        return $this->render('session/new.html.twig', [
            'formAddSession' => $form,
        ]);
    }

    // Supprime une session
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager)
    {
        // Redirige les utilisateurs non connectés vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Supprime la session et redirige vers la liste des sessions
        $entityManager->remove($session);
        $entityManager->flush();
        return $this->redirectToRoute('app_session');
    }

    // Ajoute un étudiant à une session
    #[Route('/session/add_student/{session}/{student}', name: 'add_student_session')]
    public function addStudentSession(Session $session, Student $student, EntityManagerInterface $entityManager)
    {
        // Ajoute un étudiant à la session et persiste les modifications
        $session->addStudent($student);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // Retire un étudiant d'une session
    #[Route('/session/remove_student/{session}/{student}', name: 'remove_student_session')]
    public function removeStudentSession(Session $session, Student $student, EntityManagerInterface $entityManager)
    {
        // Retire un étudiant de la session et persiste les modifications
        $session->removeStudent($student);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // Ajoute un programme à une session
    #[Route('/session/add_program/{session}/{module}', name: 'add_program_session')]
    public function addProgramSession(Session $session, Module $module, EntityManagerInterface $entityManager)
    {
        // Crée un nouveau programme, le configure et le persiste
        $program = new Program();
        $nbDays = filter_input(INPUT_POST, 'nbDays', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $program->setSession($session);
        $program->setModule($module);
        $program->setNbDays($nbDays);
        $entityManager->persist($program);
        $entityManager->flush();
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // Retire un programme d'une session
    #[Route('/session/remove_program/{session}/{program}', name: 'remove_program_session')]
    public function removeProgramSession(Session $session, Program $program, EntityManagerInterface $entityManager)
    {
        // Retire un programme de la session et persiste les modifications
        $session->removeProgram($program);
        $entityManager->persist($session);
        $entityManager->flush();
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // Affiche les détails d'une session
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session = null, SessionRepository $sr): Response
    {
        // Redirige les utilisateurs non connectés vers la page de connexion
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère les étudiants non inscrits et les modules non programmés pour cette session
        $notRegistered = $sr->findNotRegistered($session->getId());
        $unscheduledModules = $sr->findUnscheduledModules($session->getId());

        // Affiche les détails de la session
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'notRegistered' => $notRegistered,
            'unscheduledModules' => $unscheduledModules
        ]);
    }

    // Génère un fichier PDF pour une session spécifique
    #[Route('session/{id}/pdf', name: 'session.pdf')]
    public function generatePdfSession(Session $session = null, SessionRepository $sr, PdfService $pdf)
    {
        // Récupère les étudiants non inscrits et les modules non programmés pour cette session
        $notRegistered = $sr->findNotRegistered($session->getId());
        $unscheduledModules = $sr->findUnscheduledModules($session->getId());

        // Rend la vue Twig en HTML
        $html = $this->render('session/show.html.twig', [
            'session' => $session,
            'notRegistered' => $notRegistered,
            'unscheduledModules' => $unscheduledModules
        ]);

        // Affiche le fichier PDF généré à partir du HTML
        $pdf->showPdfFile($html);
    }
}