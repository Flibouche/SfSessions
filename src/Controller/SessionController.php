<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Program;
use App\Entity\Session;
use App\Entity\Student;
use App\Form\ProgramType;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $sessions = $sessionRepository->findBy([], ["title" => "ASC"]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$session) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData();
            // Prepare PDO
            $entityManager->persist($session);
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute('app_session');
    }

    #[Route('/session/add_student/{session}/{student}', name: 'add_student_session')]
    public function addStudentSession(Session $session, Student $student, EntityManagerInterface $entityManager)
    {

        $session->addStudent($student);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/remove_student/{session}/{student}', name: 'remove_student_session')]
    public function removeStudentSession(Session $session, Student $student, EntityManagerInterface $entityManager)
    {

        $session->removeStudent($student);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    // Je crée mon program, je l'instancie, je lui set les infos, une fois le programme crée : session addprogram,  Pour program : Set session, set module, set nbjours
    #[Route('/session/add_program/{session}/{module}', name: 'add_program_session')]
    public function addProgramSession(Session $session, Module $module, EntityManagerInterface $entityManager)
    {

        $program = new Program();

        $nbDays = filter_input(INPUT_POST, 'nbDays', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $program->setSession($session);
        $program->setModule($module);
        $program->setNbDays($nbDays);

        $entityManager->persist($program);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/remove_program/{session}/{program}', name: 'remove_program_session')]
    public function removeProgramSession(Session $session, Program $program, EntityManagerInterface $entityManager)
    {
        $session->removeProgram($program);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session = null, SessionRepository $sr): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $notRegistered = $sr->findNotRegistered($session->getId());
        $unscheduledModules = $sr->findUnscheduledModules($session->getId());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'notRegistered' => $notRegistered,
            'unscheduledModules' => $unscheduledModules
        ]);
    }
}
