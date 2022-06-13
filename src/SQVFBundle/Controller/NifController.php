<?php

namespace SQVFBundle\Controller;

use SQVFBundle\Entity\sqvf_nif;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NifController extends Controller
{
    public function listAction()
    {
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $nifs = $sqvf_em->getRepository(sqvf_nif::class)->findAll();

        return $this->render('SQVFBundle:Nif:list.html.twig', array(
            'nifs' => $nifs
        ));
    }

    public function showAction()
    {
        return $this->render('SQVFBundle:Nif:show.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('SQVFBundle:Nif:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('SQVFBundle:Nif:delete.html.twig', array(
            // ...
        ));
    }

}
