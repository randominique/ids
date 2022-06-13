<?php
namespace DBundle\EventListener;

use Avanzu\AdminThemeBundle\Model\UserModel;
use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use Avanzu\AdminThemeBundle\Model\NavBarUserLink;
use Symfony\Component\Security\Core\Security;

class ShowUserListener {

    private $security;
    public function __construct(Security $security){
        $this->security = $security;
    }

    public function onShowUser(ShowUserEvent $event){
        $me = $this->security->getUser();
        $user = new UserModel;
        $user->setUsername($me->getUsername())
        ->setName($me->getNom())
        ->setIsOnline(true)
        ->setMemberSince($me->getDate())
        ->setTitle($me->getService()->getNom())
        ->setAvatar('/images/avatar.jpg');
        $event->setUser($user);
        // $event->setShowProfileLink(false);
        // $event->addLink(new NavBarUserLink('Followers', 'logout'));
    }

    // protected function getUser(){

    // }

}