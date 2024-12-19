<?php
// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/creer-cours", name="admin_creer_cours", methods={"GET", "POST"})
     */
    public function creerCours(Request $request, EntityManagerInterface $entityManager)
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            $entityManager->flush();

            return $this->redirectToRoute('admin_liste_cours');
        }

        return $this->render('admin/creer_cours.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
