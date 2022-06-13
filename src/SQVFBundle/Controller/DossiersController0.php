<?php

namespace SQVFBundle\Controller;

use SQVFBundle\Entity\Dossiers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Dossier controller.
 *
 * @Route("dossiers")
 */
class DossiersController extends Controller
{
    /**
     * Lists all dossier entities.
     *
     * @Route("/", name="dossiers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dossiers = $em->getRepository('SQVFBundle:Dossiers')->findAll();

        return $this->render('dossiers/index.html.twig', array(
            'dossiers' => $dossiers,
        ));
    }

    /**
     * Creates a new dossier entity.
     *
     * @Route("/new", name="dossiers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dossier = new Dossier();
        $form = $this->createForm('SQVFBundle\Form\DossiersType', $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dossier);
            $em->flush();

            return $this->redirectToRoute('dossiers_show', array('id' => $dossier->getId()));
        }

        return $this->render('dossiers/new.html.twig', array(
            'dossier' => $dossier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a dossier entity.
     *
     * @Route("/{id}", name="dossiers_show")
     * @Method("GET")
     */
    public function showAction(Dossiers $dossier)
    {
        $deleteForm = $this->createDeleteForm($dossier);

        return $this->render('dossiers/show.html.twig', array(
            'dossier' => $dossier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing dossier entity.
     *
     * @Route("/{id}/edit", name="dossiers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dossiers $dossier)
    {
        $deleteForm = $this->createDeleteForm($dossier);
        $editForm = $this->createForm('SQVFBundle\Form\DossiersType', $dossier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dossiers_edit', array('id' => $dossier->getId()));
        }

        return $this->render('dossiers/edit.html.twig', array(
            'dossier' => $dossier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a dossier entity.
     *
     * @Route("/{id}", name="dossiers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Dossiers $dossier)
    {
        $form = $this->createDeleteForm($dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dossier);
            $em->flush();
        }

        return $this->redirectToRoute('dossiers_index');
    }

    /**
     * Creates a form to delete a dossier entity.
     *
     * @param Dossiers $dossier The dossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dossiers $dossier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dossiers_delete', array('id' => $dossier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
