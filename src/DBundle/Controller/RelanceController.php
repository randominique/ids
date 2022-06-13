<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use DBundle\Entity\Relance;
use DBundle\Form\RelanceType;

class RelanceController extends Controller
{
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $Relance = new Relance();
        $Relance->setUser($user);

        $form = $this->createForm(RelanceType::class, $Relance);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Relance);
            $em->flush();

            return $this->redirectToRoute('liste_relance');
        }
        return $this->render('DBundle:Relance:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) {
            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            // $query = $em->getRepository(Relance::class)
            // ->createQueryBuilder('c')
            // ->where('c.date BETWEEN :date_du AND :date_au')
            // ->setParameter('date_du', $date_du)
            // ->setParameter('date_au', $date_au)
            // ->orderBy('c.date', 'asc')
            // ->getQuery();

            // $paginator  = $this->get('knp_paginator');
            // $couriers = $paginator->paginate(
            //     $query,
            //     $request->query->getInt('page', 1),
            //     20
            // );
        }
        else
        {
            $query = $em->getRepository(Relance::class)
            ->createQueryBuilder('r')
            ->orderBy('r.date', 'desc')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $relances = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }

        return $this->render('DBundle:Relance:liste.html.twig', array(
            'relances' => $relances,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ));
    }

    public function showAction(Relance $relance)
    {
        return $this->render('DBundle:Relance:show.html.twig', array(
            'relance' => $relance
        ));
    }

}
