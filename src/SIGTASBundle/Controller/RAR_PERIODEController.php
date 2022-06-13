<?php

namespace SIGTASBundle\Controller;

use SIGTASBundle\Entity\RAR_PERIODE;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Rar_periode controller.
 *
 * @Route("rar_periode")
 */
class RAR_PERIODEController extends Controller
{
    /**
     * Lists all rAR_PERIODE entities.
     *
     * @Route("/", name="rar_periode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $rAR_PERIODEs = $sigtas_em->getRepository('SIGTASBundle:RAR_PERIODE')->findAll();

        return $this->render('rar_periode/index.html.twig', array(
            'rAR_PERIODEs' => $rAR_PERIODEs,
        ));
    }

    /**
     * Finds and displays a rAR_PERIODE entity.
     *
     * @Route("/{id}", name="rar_periode_show")
     * @Method("GET")
     */
    public function showAction(RAR_PERIODE $rAR_PERIODE)
    {

        return $this->render('rar_periode/show.html.twig', array(
            'rAR_PERIODE' => $rAR_PERIODE,
        ));
    }
}
