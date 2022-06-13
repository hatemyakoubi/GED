<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Action;
use App\Entity\Actions;
use App\Form\UserFormType;
use App\Form\ResetFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\ActionRepository;
use App\Repository\ActionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/user",name="app_admin_users")
     * @IsGranted("ROLE_DGI")
     */
    public function users(UserRepository $userRepository, ActionsRepository $actionsRepository):Response
    {
        $nbruser = $userRepository->NbrUsers();
       //dd($nbruser);
        $nbr = '';
        foreach ($nbruser as $n) {
            $nbr = $n['nombre'];
        }
        $userConnecter = $actionsRepository->findUsersConnecter($nbr);

       // dd($userConnecter);
        // $userDeconnecter = $actionsRepository->findUsersDeconecter($nbr);
        $dateDeconnecter =[];
        // foreach ($userDeconnecter as $u){
        //     $dateDeconnecter[]= [
        //         'id' => $u->getId(),
        //         'date' => $u->getDateAction()
        //     ] ;
        // }
      //  dd($userDeconnecter);
        $users = $userRepository->findAll();
        return $this->render('admin/user/user.html.twig', [
            'users' => $users,
            'nbr'=>$nbr,
            'userConnecter' => $userConnecter,
            'dateDeconnecter' => $dateDeconnecter
        ]);
    }

    /**
     * @Route("/admin/user/new",name="app_admin_new_user")
     * @IsGranted("ROLE_DGI")
     */
    public function newUser(Request $request, TranslatorInterface $translator, UserPasswordEncoderInterface $passwordEncoder,
    UserRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user, ["translator" => $translator]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoded = $passwordEncoder->encodePassword($user, $form->get("justpassword")->getData());
            $user->setPassword($encoded)
                 ->setActive(false);
              //  dd($user);
            $userRepository->add($user);
            $this->addFlash("success", $translator->trans('backend.user.add_user'));
            return $this->redirectToRoute("app_admin_users");
        }
        return $this->render("admin/user/userform.html.twig", [
            'userForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/admin/user/edit/{id}",name="app_admin_edit_user")
     * @IsGranted("ROLE_DGI")
     */
    public function editUser(User $user, Request $request, TranslatorInterface $translator,UserPasswordEncoderInterface $passwordEncoder,
    UserRepository $userRepository)
    {
        $form = $this->createForm(UserFormType::class, $user, ["translator" => $translator]);
        $form->get('justpassword')->setData($user->getPassword());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form["justpassword"]->getData();
            if ($user->getPassword() != $password) {
                $encoded = $passwordEncoder->encodePassword($user, $form->get("justpassword")->getData());
                $user->setPassword($encoded);
            }
            $userRepository->add($user);
            $this->addFlash("success", $translator->trans('backend.user.modify_user'));
            return $this->redirectToRoute("app_admin_users");
        }
        return $this->render("admin/user/userform.html.twig", ["userForm" => $form->createView()]);
    }

    /**
     * @Route("/admin/user/delete/{id}",name="app_admin_delete_user")
     * @IsGranted("ROLE_DGI")
     */
    public function delete(User $user,UserRepository $userRepository)
    {
        $userRepository->remove($user);
        $this->addFlash("success","Utilisateur supprimé");
        return $this->redirectToRoute('app_admin_users');
    }

    /**
     * @Route("/admin/user/changevalidite/{id}",name="app_admin_changevalidite_user",methods={"GET","POST"})
     * @IsGranted("ROLE_DGI")
     */
    public function activate(User $user, UserRepository $userRepository)
    {
        $userRepository->changeValidite($user);
        return $this->redirectToRoute('app_admin_users');
    }

    /**
     * @Route("/admin/user/changemotpasse/{id}", name="app_admin_changepswd", methods={"GET","POST"})
     */

     public function ResetPassword(Request $request,TranslatorInterface $translator, UserRepository $userRepository,ActionsRepository $ActionRepository,
     UserPasswordEncoderInterface $passwordEncoder,$id){
        
        $action = new Actions();
        $user= $userRepository->find($id);
        $form =$this->createForm(ResetFormType::class,$user, ["translator" => $translator]);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            
            $plainPassword = $form["plainPassword"]->getData();
           
            if ($passwordEncoder->isPasswordValid($user, $plainPassword)) {
               
                $password = $form["password"]->getData();
                $encoded = $passwordEncoder->encodePassword($user, $form->get("password")->getData());
                $user->setPassword($encoded);
                $userRepository->add($user);
              
                $action ->setTypeAction("modifier mot de passe") 
                        ->setDateAction(new \Datetime())
                        ->setUtilisateur($user)
                              
                ;
                $ActionRepository->add($action);
                
                $this->addFlash("success", $translator->trans('backend.user.changed_password'));
                return $this->redirectToRoute("app_login");
            }else {
                $this->addFlash("error", $translator->trans('backend.user.new_passwod_must_be'));
                return $this->render('admin/user/ResetPassword.html.twig',[
                    'form'=>$form->createView(),
                ]);
        
            }
          
        }

        return $this->render('admin/user/ResetPassword.html.twig',[
            'form'=>$form->createView(),
        ]);

    }
}
