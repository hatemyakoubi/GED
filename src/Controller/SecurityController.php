<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Actions;
use App\Repository\UserRepository;
use App\Repository\ActionRepository;
use App\Repository\ActionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils,ActionsRepository $ActionRepository,SessionInterface $session): Response
    {    
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('home');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(InteractiveLoginEvent $event): void
    {
        // $user = $event->getAuthenticationToken()->getUser();
        // dump('hello');
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
