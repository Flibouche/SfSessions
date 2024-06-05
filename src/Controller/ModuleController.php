<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository): Response
    {
        $modules = $moduleRepository->findBy([], ["title" => "ASC"]);
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
        ]);
    }

    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function new_edit(Module $module = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$module) {
            $module = new Module();
        }

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $employe = $form->getData();
            // Prepare PDO
            $entityManager->persist($employe);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_module');
        }

        return $this->render('module/new.html.twig', [
            'formAddModule' => $form,
        ]);
    }

    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(module $module, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_module');
    }

    #[Route('/module/{id}', name: 'show_module')]
    public function show(module $module): Response
    {
        return $this->render('module/show.html.twig', [
            'module' => $module
        ]);
    }
}