<?php

namespace App\Controller;

use App\Entity\Gouvernorat;
use App\Form\GouvernoratType;
use App\Repository\GouvernoratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gouvernorat")
 */
class GouvernoratController extends AbstractController
{
    /**
     * @Route("/", name="app_gouvernorat_index", methods={"GET"})
     */
    public function index(GouvernoratRepository $gouvernoratRepository): Response
    {
        return $this->render('gouvernorat/index.html.twig', [
            'gouvernorats' => $gouvernoratRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_gouvernorat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GouvernoratRepository $gouvernoratRepository): Response
    {
        $gouvernorat = new Gouvernorat();
        $form = $this->createForm(GouvernoratType::class, $gouvernorat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gouvernoratRepository->add($gouvernorat);
            return $this->redirectToRoute('app_gouvernorat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gouvernorat/new.html.twig', [
            'gouvernorat' => $gouvernorat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_gouvernorat_show", methods={"GET"})
     */
    public function show(Gouvernorat $gouvernorat): Response
    {
        return $this->render('gouvernorat/show.html.twig', [
            'gouvernorat' => $gouvernorat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_gouvernorat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Gouvernorat $gouvernorat, GouvernoratRepository $gouvernoratRepository): Response
    {
        $form = $this->createForm(GouvernoratType::class, $gouvernorat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gouvernoratRepository->add($gouvernorat);
            return $this->redirectToRoute('app_gouvernorat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gouvernorat/edit.html.twig', [
            'gouvernorat' => $gouvernorat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_gouvernorat_delete", methods={"POST"})
     */
    public function delete(Request $request, Gouvernorat $gouvernorat, GouvernoratRepository $gouvernoratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gouvernorat->getId(), $request->request->get('_token'))) {
            $gouvernoratRepository->remove($gouvernorat);
        }

        return $this->redirectToRoute('app_gouvernorat_index', [], Response::HTTP_SEE_OTHER);
    }
}
