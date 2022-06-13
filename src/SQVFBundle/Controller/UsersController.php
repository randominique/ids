<?php

namespace SQVFBundle\Controller;

use SQVFBundle\Entity\sqvf_users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller
{
    public function listAction()
    {
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $users = $sqvf_em->getRepository(sqvf_users::class)->findAll();

        return $this->render('SQVFBundle:Users:list.html.twig', array(
            'users' => $users
        ));
    }

    public function showAction()
    {
        return $this->render('SQVFBundle:Users:show.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('SQVFBundle:Users:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('SQVFBundle:Users:delete.html.twig', array(
            // ...
        ));
    }

}
