<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Acteur;
use App\Entity\Contrat;
use App\Form\ActeurType;
use App\Entity\Operation;
use App\Form\ContratType;
use App\Form\ContratActeur;
use App\Form\ContratActeurType;
use App\Form\EditFileContratType;
use App\Form\EditContratActeurType;
use App\Repository\ActeurRepository;
use App\Repository\ContratRepository;
use App\Repository\OperationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contrat")
 */
class ContratController extends AbstractController
{
    /**
     * @Route("/", name="app_contrat_index", methods={"GET"})
     */
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_contrat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContratRepository $contratRepository, OperationRepository $operationRepository,
     ActeurRepository $acteurRepository): Response
    {
        $contrat = new Contrat();
        $operation = new Operation();
        $acteurVendeur = new Acteur();
        $acteurAcheteur = new Acteur();
        $user = $this->getUser();
        //$target_dir = '%kernel.project_dir%/public/uploads/contrats';
        $form = $this->createForm(ContratActeurType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // /** @var UploadedFile $File */
            // $File = $_FILES["file"]["name"];
           // dd($_FILES);
             $target_file = basename($_FILES["file"]["name"]);
             $File_data = file_get_contents($_FILES["file"]["tmp_name"]);
            //$File = $_POST['file'];
            // $File = $form->get('doc')->getData();
            $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
           // dd("$target_dir/$target_file");
            $codeCentre = $form->get('codeCentre')->getData();
           $File_name = $codeCentre.'-'.md5(uniqid()).'.'.$FileType;
           // $File_name = $codeCentre.'-'.md5(uniqid()).'.pdf';
           // On copie le fichier dans le dossier uploads
          // move_uploaded_file($_FILES['file']['name'], "$target_dir/$target_file");

            // $File->move(
            //     $this->getParameter('contrat_directory'),
            //     $File_name
            // );
            $contrat->setCodeCentre($form->get('codeCentre')->getData())
                    ->setCodeRecette($form->get('codeRecette')->getData())
                    ->setTypeContrat($form->get('typeContrat')->getData())
                    ->setTypeOperation($form->get('typeOperation')->getData())
                    ->setReference($form->get('reference')->getData())
                    ->setDateContrat($form->get('dateContrat')->getData())
                    ->setRedacteur($form->get('redacteur')->getData())
                    ->setMontant($form->get('montant')->getData())
                    ->setAdresseEmp($form->get('adresseEmp')->getData())
                    ->setDoc($File_data)
                    // On crée le fichier dans la base de données
                     ->setFileName($File_name)
                    ->addUtilisateur($user)
            ;
            $contratRepository->add($contrat);

            //Crée l'acteur vendeur
            $acteurVendeur->setIdentifiant($form->get('identifiantV')->getData())
                         ->setType('Vendeur')
                         ->setNom($form->get('nomV')->getData())
                         ->setPrenom($form->get('prenomV')->getData())
                         ->addContrat($contrat)
            ;
            $acteurRepository->add($acteurVendeur);
            //Crée l'acteur acheteur
            $acteurAcheteur->setIdentifiant($form->get('identifiantA')->getData())
                        ->setType('Acheteur')
                        ->setNom($form->get('nomA')->getData())
                        ->setPrenom($form->get('prenomA')->getData())
                        ->addContrat($contrat)
            ;
            $acteurRepository->add($acteurAcheteur);
            //Creé l'operation 
            $operation->setTypeOperation("Creation d\'un contrat")
                      ->setContrats($contrat)
                      ->setUtilisateur($user)
                      ->setDateOperation(new \DateTime())
                      ;
            $operationRepository->add($operation);
            // on cree l'acteur dans la bd
            $acteurVendeur->addContrat($contrat);
            $acteurRepository->add($acteurVendeur);
            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="app_contrat_show", methods={"GET"})
     * 
     */
    public function show(Contrat $contrat): Response
    { 
        $file = base64_encode(stream_get_contents($contrat->getDoc()));
        //dd($contrat);
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
            'file'=>$file,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contrat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contrat $contrat, ActeurRepository $acteurRepository , ContratRepository $contratRepository,OperationRepository $operationRepository): Response
    {
        $operation = new Operation(); 

        $form = $this->createForm(ContratActeurType::class);
        $form->handleRequest($request);
        $file = base64_encode(stream_get_contents($contrat->getDoc()));
        $act = $contrat->getActeurs();
       
        foreach ($act as $i){
            if ($i->getType() == 'Vendeur') {
                $vendeur = $acteurRepository->find($i->getId());
            }else{
                $acheteur = $acteurRepository->find($i->getId());
            }
          
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $target_file = basename($_FILES["file"]["name"]);
            $File_name="";
            if ($target_file) {
                $File_data = file_get_contents($_FILES["file"]["tmp_name"]);
                $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $codeCentre = $form->get('codeCentre')->getData();
                $File_name = $codeCentre.'-'.md5(uniqid()).'.'.$FileType;
                $contrat ->setDoc($File_data)
                         ->setFileName($File_name);
            }

            // edit contrat
            $contrat->setCodeCentre($form->get('codeCentre')->getData())
            ->setCodeRecette($form->get('codeRecette')->getData())
            ->setTypeContrat($form->get('typeContrat')->getData())
            ->setTypeOperation($form->get('typeOperation')->getData())
            ->setReference($form->get('reference')->getData())
            ->setDateContrat($form->get('dateContrat')->getData())
            ->setRedacteur($form->get('redacteur')->getData())
            ->setMontant($form->get('montant')->getData())
            ->setAdresseEmp($form->get('adresseEmp')->getData())
            // ->setDoc($File_data)
            // // On crée le fichier dans la base de données
            // ->setFileName($File_name)
            ->addUtilisateur($this->getUser())
              ;
            $contratRepository->add($contrat);
             //Creé l'operation 
             $operation->setTypeOperation("Edit d\'un contrat")
             ->setContrats($contrat)
             ->setUtilisateur($this->getUser())
             ->setDateOperation(new \DateTime())
             ;
            $operationRepository->add($operation);
            // edit acteur vendeur
            $vendeur->setIdentifiant($form->get('identifiantV')->getData())
                         ->setType('Vendeur')
                         ->setNom($form->get('nomV')->getData())
                         ->setPrenom($form->get('prenomV')->getData())
                         ->addContrat($contrat)
            ;
            $acteurRepository->add($vendeur);
            // edit l'acteur acheteur
            $acheteur->setIdentifiant($form->get('identifiantA')->getData())
                        ->setType('Acheteur')
                        ->setNom($form->get('nomA')->getData())
                        ->setPrenom($form->get('prenomA')->getData())
                        ->addContrat($contrat)
            ;
            $acteurRepository->add($acheteur);
            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'file'=>$file,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{id}/edit-file", name="app_contrat_editFile", methods={"GET","POST"})
     */
    public function editFile(Request $request, Contrat $contrat, ContratRepository $contratRepository, OperationRepository $operationRepository):Response
    {
        $operation = new Operation(); 
        $codeCentre = $contrat->getCodeCentre();
        if ($request->isMethod('POST')) {
           $target_file = basename($_FILES["file"]["name"]);
           $File_data = file_get_contents($_FILES["file"]["tmp_name"]);
           $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
           $File_name = $codeCentre.'-'.md5(uniqid()).'.'.$FileType;
           $contrat->setDoc($File_data)
                   ->setFileName($File_name);
             //Creé l'operation 
             $operation->setTypeOperation("Edit le fichier contrat")
             ->setContrats($contrat)
             ->setUtilisateur($this->getUser())
             ->setDateOperation(new \DateTime())
             ;
            $contratRepository->add($contrat);
            $operationRepository->add($operation);
            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('contrat/editFile.html.twig', [
            'contrat' => $contrat,
        ]);
    }
    /**
     * @Route("/{id}", name="app_contrat_delete", methods={"POST"})
     */
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
