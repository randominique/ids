<?php

namespace SQVFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SQVFBundle\Entity\Dossiers;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $dossiers = $sqvf_em->getRepository(Dossiers::class)->findAll();

        return $this->render('SQVFBundle:Default:index.html.twig', array(
            'dossiers' => $dossiers
        ));
    }
}
