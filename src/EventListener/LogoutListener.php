<?php
 
namespace App\EventListener;

use App\Entity\Actions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
 
class LogoutListener
{
    private EntityManagerInterface $entityManager;

 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
 
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {
        $action = new Actions();
        
        if (($token = $event->getToken()) && $user = $token->getUser()) {
            $action ->setTypeAction("déconnecté") 
            ->setDateAction(new \DateTime())
            ->setUtilisateur($user)
                        
            ;
            $this->entityManager->persist($action);
            $this->entityManager->flush();
                }
        
    }
}