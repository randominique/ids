<?php

namespace DBundle\Controller;

use DBundle\Entity\AnnexeTva;
use DBundle\Entity\CourierSortant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Annexetva controller.
 *
 * @Route("annexe-tva")
 */
class AnnexeTvaController extends Controller
{
    /**
     * Lists all annexeTva entities.
     *
     * @Route("/", name="annexe-tva_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $annexeTvas = $em->getRepository(CourierSortant::class)
        ->createQueryBuilder('c')
        ->where('c.categorie = 4')
        ->getQuery()
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $annexeTvas,
            $request->query->getInt('page', 1),
            20
        );  

        return $this->render('annexetva/index.html.twig', array(
            'annexeTvas' => $pagination,
        ));
    }

    /**
     * Creates a new annexeTva entity.
     *
     * @Route("/nouveau", name="annexe-tva_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $annexeTva = new Annexetva();
        $form = $this->createForm('DBundle\Form\AnnexeTvaType', $annexeTva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($annexeTva);
            $em->flush();

            return $this->redirectToRoute('annexe-tva_show', array('id' => $annexeTva->getId()));
        }

        return $this->render('annexetva/new.html.twig', array(
            'annexeTva' => $annexeTva,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a annexeTva entity.
     *
     * @Route("/{id}", name="annexe-tva_show")
     * @Method("GET")
     */
    public function showAction(AnnexeTva $annexeTva)
    {
        $deleteForm = $this->createDeleteForm($annexeTva);

        return $this->render('annexetva/show.html.twig', array(
            'annexeTva' => $annexeTva,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing annexeTva entity.
     *
     * @Route("/{id}/mettre-a-jour", name="annexe-tva_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AnnexeTva $annexeTva)
    {
        $deleteForm = $this->createDeleteForm($annexeTva);
        $editForm = $this->createForm('DBundle\Form\AnnexeTvaType', $annexeTva);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('annexe-tva_show', array('id' => $annexeTva->getId()));
        }

        return $this->render('annexetva/edit.html.twig', array(
            'annexeTva' => $annexeTva,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a annexeTva entity.
     *
     * @Route("/{id}", name="annexe-tva_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AnnexeTva $annexeTva)
    {
        $form = $this->createDeleteForm($annexeTva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($annexeTva);
            $em->flush();
        }

        return $this->redirectToRoute('annexe-tva_index');
    }

    /**
     * Creates a form to delete a annexeTva entity.
     *
     * @param AnnexeTva $annexeTva The annexeTva entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AnnexeTva $annexeTva)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('annexe-tva_delete', array('id' => $annexeTva->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
