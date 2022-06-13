<?php

namespace SIGTASBundle\Controller;

use SIGTASBundle\Entity\RAR_SANS_PERIODE;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Rar_sans_periode controller.
 *
 * @Route("rar_sans_periode")
 */
class RAR_SANS_PERIODEController extends Controller
{
    /**
     * Lists all rAR_SANS_PERIODE entities.
     *
     * @Route("/", name="rar_sans_periode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rAR_SANS_PERIODEs = $em->getRepository('SIGTASBundle:RAR_SANS_PERIODE')->findAll();

        return $this->render('rar_sans_periode/index.html.twig', array(
            'rAR_SANS_PERIODEs' => $rAR_SANS_PERIODEs,
        ));
    }

    /**
     * Finds and displays a rAR_SANS_PERIODE entity.
     *
     * @Route("/{id}", name="rar_sans_periode_show")
     * @Method("GET")
     */
    public function showAction(RAR_SANS_PERIODE $rAR_SANS_PERIODE)
    {

        return $this->render('rar_sans_periode/show.html.twig', array(
            'rAR_SANS_PERIODE' => $rAR_SANS_PERIODE,
        ));
    }
}
