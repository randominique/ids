<?php

namespace DBundle\Controller;

use DBundle\Entity\Dge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Dge controller.
 *
 * @Route("dge")
 */
class DgeController extends Controller
{
    /**
     * Lists all dge entities.
     *
     * @Route("/", name="dge_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dges = $em->getRepository('DBundle:Dge')->findAll();

        return $this->render('dge/index.html.twig', array(
            'dges' => $dges,
        ));
    }

    /**
     * Creates a new dge entity.
     *
     * @Route("/new", name="dge_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dge = new Dge();
        $form = $this->createForm('DBundle\Form\DgeType', $dge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dge);
            $em->flush();

            return $this->redirectToRoute('dge_show', array('id' => $dge->getId()));
        }

        return $this->render('dge/new.html.twig', array(
            'dge' => $dge,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a dge entity.
     *
     * @Route("/{id}", name="dge_show")
     * @Method("GET")
     */
    public function showAction(Dge $dge)
    {
        $deleteForm = $this->createDeleteForm($dge);

        return $this->render('dge/show.html.twig', array(
            'dge' => $dge,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing dge entity.
     *
     * @Route("/{id}/edit", name="dge_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dge $dge)
    {
        $deleteForm = $this->createDeleteForm($dge);
        $editForm = $this->createForm('DBundle\Form\DgeType', $dge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dge_edit', array('id' => $dge->getId()));
        }

        return $this->render('dge/edit.html.twig', array(
            'dge' => $dge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a dge entity.
     *
     * @Route("/{id}", name="dge_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Dge $dge)
    {
        $form = $this->createDeleteForm($dge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dge);
            $em->flush();
        }

        return $this->redirectToRoute('dge_index');
    }

    /**
     * Creates a form to delete a dge entity.
     *
     * @param Dge $dge The dge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dge $dge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dge_delete', array('id' => $dge->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
