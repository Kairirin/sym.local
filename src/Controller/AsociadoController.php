<?php

namespace App\Controller;

use App\Entity\Asociado;
use App\Form\AsociadoType;
use App\Repository\AsociadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/asociado')]
final class AsociadoController extends AbstractController
{
    #[Route(name: 'app_asociado_index', methods: ['GET'])]
    public function index(AsociadoRepository $asociadoRepository): Response
    {
        return $this->render('asociado/index.html.twig', [
            'asociados' => $asociadoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_asociado_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $asociado = new Asociado();
        $form = $this->createForm(AsociadoType::class, $asociado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($asociado);
            $entityManager->flush();

            return $this->redirectToRoute('app_asociado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asociado/new.html.twig', [
            'asociado' => $asociado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asociado_show', methods: ['GET'])]
    public function show(Asociado $asociado): Response
    {
        return $this->render('asociado/show.html.twig', [
            'asociado' => $asociado,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_asociado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Asociado $asociado, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AsociadoType::class, $asociado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_asociado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asociado/edit.html.twig', [
            'asociado' => $asociado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_asociado_delete', methods: ['POST'])]
    public function delete(Request $request, Asociado $asociado, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$asociado->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($asociado);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_asociado_index', [], Response::HTTP_SEE_OTHER);
    }
}
