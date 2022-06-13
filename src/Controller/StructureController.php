<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/structure")
 */
class StructureController extends AbstractController
{
    /**
     * @Route("/", name="app_structure_index", methods={"GET"})
     * @IsGranted("ROLE_DGI")
     */
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_structure_new", methods={"GET", "POST"})
     */
    public function new(Request $request,StructureRepository $structureRepository,TranslatorInterface $translator): Response
    {
        $structure = new Structure();
        $user = new User();
        $form = $this->createForm(StructureType::class, $structure, ["translator" => $translator]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (($form->get('codeCentre')->getData()) != 'Recette') {
               $structure->setCodeCentre($form->get('CodeStructure')->getData());
            }
            $structureRepository->add($structure);
            $this->addFlash("success", $translator->trans('backend.structure.add_structure'));
            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_structure_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Structure $structure, StructureRepository $structureRepository,TranslatorInterface $translator): Response
    {
        $form = $this->createForm(StructureType::class, $structure, ["translator" => $translator]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $structureRepository->add($structure);
            $this->addFlash("success", $translator->trans('backend.structure.Modify_structure'));
            return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_structure_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Structure $structure, StructureRepository $structureRepository): Response
    {
        $structureRepository->remove($structure);
        $this->addFlash("success","Structure supprimé");

        return $this->redirectToRoute('app_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
