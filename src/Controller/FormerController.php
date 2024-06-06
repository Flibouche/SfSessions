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
    #[Route('/former', name: 'app_former')]
    public function index(FormerRepository $formerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $formers = $formerRepository->findBy([], ["name" => "ASC"]);
        return $this->render('former/index.html.twig', [
            'formers' => $formers,
        ]);
    }

    #[Route('/former/new', name: 'new_former')]
    #[Route('/former/{id}/edit', name: 'edit_former')]
    public function new_edit(Former $former = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$former) {
            $former = new Former();
        }

        $form = $this->createForm(FormerType::class, $former);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $former = $form->getData();
            // Prepare PDO
            $entityManager->persist($former);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_former');
        }

        return $this->render('former/new.html.twig', [
            'formAddFormer' => $form,
        ]);
    }

    #[Route('/former/{id}/delete', name: 'delete_former')]
    public function delete(former $former, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager->remove($former);
        $entityManager->flush();

        return $this->redirectToRoute('app_former');
    }

    #[Route('/former/{id}', name: 'show_former')]
    public function show(former $former): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('former/show.html.twig', [
            'former' => $former
        ]);
    }
}