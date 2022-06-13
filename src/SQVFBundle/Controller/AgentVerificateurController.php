<?php

namespace SQVFBundle\Controller;

use SQVFBundle\Entity\sqvf_dossiers_agent_verificateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AgentVerificateurController extends Controller
{
    public function listAction()
    {
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $agents = $sqvf_em->getRepository(sqvf_dossiers_agent_verificateur::class)->findAll();

        return $this->render('SQVFBundle:AgentVerificateur:list.html.twig', array(
            'agents' => $agents
        ));
    }

    public function showAction()
    {
        return $this->render('SQVFBundle:AgentVerificateur:show.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('SQVFBundle:AgentVerificateur:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('SQVFBundle:AgentVerificateur:delete.html.twig', array(
            // ...
        ));
    }

}
