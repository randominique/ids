<?php

namespace DBundle\EventListener;


use Avanzu\AdminThemeBundle\Event\TaskListEvent;
use Avanzu\AdminThemeBundle\Model\TaskModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use DBundle\Entity\CourrierDispatching;

class NavbarTaskListener
{
    private $em;

    public function __construct(EntityManagerInterface $em, Security $security){
        $this->em = $em;
        $this->security = $security;
    }

    public function onListTasks(TaskListEvent $event)
    {
        $user = $this->security->getUser();
        $tasks = $this->em->getRepository(CourrierDispatching::class)->findBy([
            'gestionnaire' => $user->getId(),
            'traite' => 0
        ]);

        foreach($tasks as $task) {
            $event->addTask(new TaskModel('Doc No: '.$task->getDocNo(), 10, TaskModel::COLOR_RED));
        }

    }

}