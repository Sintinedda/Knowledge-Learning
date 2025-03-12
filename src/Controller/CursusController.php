<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\Theme;
use App\Form\CursusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cursus')]
final class CursusController extends AbstractController
{
    #[Route('/{id}/new}', name: 'app_cursus_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $theme = $em->getRepository(Theme::class)->findOneBy(['id' => $id]);
        $cursus = new Cursus();
        $form = $this->createForm(CursusType::class, $cursus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cursus->setTheme($theme);
            $em->persist($cursus);
            $em->flush();

            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cursus/new.html.twig', [
            'cursus' => $cursus,
            'form' => $form,
            'theme' => $theme
        ]);
    }

    #[Route('/{id}', name: 'app_cursus_show', methods: ['GET'])]
    public function show(Cursus $cursus): Response
    {
        return $this->render('cursus/show.html.twig', [
            'cursus' => $cursus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cursus_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cursus $cursus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CursusType::class, $cursus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cursus/edit.html.twig', [
            'cursus' => $cursus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cursus_delete', methods: ['POST'])]
    public function delete(Request $request, Cursus $cursus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cursus->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cursus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
    }
}
