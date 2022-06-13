<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Form\StatistiqueType;
use App\Repository\ContratRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/search", name="app_search")
     */
    public function Search(Request $request,ContratRepository $contratRepository): Response
    {
        $search = new SearchData();
        $form = $this->createForm(SearchFormType::class,$search);
        $form->handleRequest($request);
        $resultats=""
        ;
        // if ($form->isSubmitted() && $form->isValid()) {
        if ($request->isMethod('Post')) {
            
            // dd($form->get('contrat')->getData());
            $resultats = $contratRepository->findSearch($form->getData());
            if (!$resultats) {
                $this->addFlash("msg", "Aucun enregistrement trouvé");
            }
        }
        return $this->render('home/search.html.twig', [
            'controller_name' => 'HomeController',
            'form'=>$form->createView(),
            'resultats'=>$resultats
        ]);
    }
    /**
     * @Route("/statistique", name="app_stat")
     */
    public function Statistique(Request $request,ContratRepository $contratRepository, UserRepository $userRepository,RecetteRepository $recetteRepository): Response
    {
       
        $form = $this->createForm(StatistiqueType::class);
        $form->handleRequest($request);
        $resultats=""
        ;
        // if ($form->isSubmitted() && $form->isValid()) {
        if ($request->isMethod('Post')) {
            
            // dd($form->getData());
            $resultats = $contratRepository->Statistique($form->getData());
            if (!$resultats) {
                $this->addFlash("msg", "Aucun enregistrement trouvé");
            }
           // dd($resultats);
        }
        return $this->render('home/statistique.html.twig', [
            'controller_name' => 'HomeController',
            'users'=>$userRepository->findAll(),
            'contrats'=>$contratRepository->findAll(),
            'recettes'=>$recetteRepository->findAll(),
            'form'=>$form->createView(),
            'resultats'=>$resultats
        ]);
    }
    /**
     * @Route("/journaliastion", name="app_journalisation")
     */
    public function Journaliastion(ContratRepository $contratRepository): Response
    {
        return $this->render('home/journalisation.html.twig', [
            'controller_name' => 'HomeController',
            'contrats'=>$contratRepository->findAll(),
            
        ]);
    }


}
