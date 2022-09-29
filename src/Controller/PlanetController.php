<?php

namespace App\Controller;

use App\Entity\Planet;
use App\Form\PlanetType;
use App\Repository\PlanetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sw/planet')]
class PlanetController extends AbstractController
{
    #[Route('/', name: 'app_planet_index', methods: ['GET'])]
    public function index(PlanetRepository $planetRepository): Response
    {
        return $this->render('planet/index.html.twig', [
            'planets' => $planetRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanetRepository $planetRepository): Response
    {
        $planet = new Planet();
        $form = $this->createForm(PlanetType::class, $planet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planetRepository->save($planet, true);

            return $this->redirectToRoute('app_planet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planet/new.html.twig', [
            'planet' => $planet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planet_show', methods: ['GET'])]
    public function show(Planet $planet): Response
    {
        return $this->render('planet/show.html.twig', [
            'planet' => $planet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planet $planet, PlanetRepository $planetRepository): Response
    {
        $form = $this->createForm(PlanetType::class, $planet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planetRepository->save($planet, true);

            return $this->redirectToRoute('app_planet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planet/edit.html.twig', [
            'planet' => $planet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planet_delete', methods: ['POST'])]
    public function delete(Request $request, Planet $planet, PlanetRepository $planetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planet->getId(), $request->request->get('_token'))) {
            $planetRepository->remove($planet, true);
        }

        return $this->redirectToRoute('app_planet_index', [], Response::HTTP_SEE_OTHER);
    }
}
