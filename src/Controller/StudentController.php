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
    #[Route('/student', name: 'app_student')]
    public function index(StudentRepository $studentRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $students = $studentRepository->findBy([], ["name" => "ASC"]);
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/student/new', name: 'new_student')]
    #[Route('/student/{id}/edit', name: 'edit_student')]
    public function new_edit(Student $student = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$student) {
            $student = new Student();
        }

        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student = $form->getData();
            // Prepare PDO
            $entityManager->persist($student);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_student');
        }

        return $this->render('student/new.html.twig', [
            'formAddStudent' => $form,
        ]);
    }

    #[Route('/student/{id}/delete', name: 'delete_student')]
    public function delete(student $student, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        $entityManager->remove($student);
        $entityManager->flush();

        return $this->redirectToRoute('app_student');
    }

    #[Route('/student/{id}', name: 'show_student')]
    public function show(student $student): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('student/show.html.twig', [
            'student' => $student
        ]);
    }
}