<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
    // Affiche la liste des étudiants
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $studentRepository): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupère la liste des étudiants ordonnée par nom
        $students = $studentRepository->findBy([], ["name" => "ASC"]);

        // Rend la vue Twig avec la liste des étudiants
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    // Ajoute ou modifie un étudiant
    #[Route('/student/new', name: 'new_student')]
    #[Route('/student/{id}/edit', name: 'edit_student')]
    public function new_edit(Student $student = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Initialise un nouvel étudiant ou récupère un étudiant existant
        if (!$student) {
            $student = new Student();
        }

        // Crée un formulaire pour l'étudiant
        $form = $this->createForm(StudentType::class, $student);

        // Traite la requête du formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistre l'étudiant dans la base de données
            $entityManager->persist($student);
            $entityManager->flush();

            // Redirige vers la liste des étudiants
            return $this->redirectToRoute('app_student');
        }

        // Rend la vue Twig pour ajouter ou modifier un étudiant
        return $this->render('student/new.html.twig', [
            'formAddStudent' => $form,
        ]);
    }

    // Supprime un étudiant
    #[Route('/student/{id}/delete', name: 'delete_student')]
    public function delete(student $student, EntityManagerInterface $entityManager)
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Supprime l'étudiant de la base de données
        $entityManager->remove($student);
        $entityManager->flush();

        // Redirige vers la liste des étudiants
        return $this->redirectToRoute('app_student');
    }

    // Affiche les détails d'un étudiant
    #[Route('/student/{id}', name: 'show_student')]
    public function show(student $student): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        // Rend la vue Twig des détails de l'étudiant
        return $this->render('student/show.html.twig', [
            'student' => $student
        ]);
    }
}