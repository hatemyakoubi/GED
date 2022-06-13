<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Action;
use App\Entity\Actions;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $entityManager;
    private SessionInterface $session;
    private FlashBagInterface $flashBag;

    public function __construct(UrlGeneratorInterface $urlGenerator,EntityManagerInterface $entityManager,SessionInterface $session )
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->session = $session;

    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $username = $request->request->get('username', '');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username'=>$username]);

        $this->session->set('username',$username);

        
        $action = new Actions();
            $action ->setTypeAction("connecté") 
                    ->setDateAction(new \DateTime())
                    ->setUtilisateur($user)
                          
            ;
            $this->entityManager->persist($action);
            $this->entityManager->flush();

        if(!$user->getActive()){
            $this->session->getFlashBag()->set('success', 'compte pas encore activée');

           // $this->flashBag->add('success', 'compte pas encore activée');
           return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
