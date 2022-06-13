<?php

namespace SIGTASBundle\Controller;

use SIGTASBundle\Entity\FiscalRegime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Fiscalregime controller.
 *
 * @Route("fiscalregime")
 */
class FiscalRegimeController extends Controller
{
    /**
     * Lists all fiscalRegime entities.
     *
     * @Route("/", name="fiscalregime_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fiscalRegimes = $em->getRepository('SIGTASBundle:FiscalRegime')->findAll();

        return $this->render('fiscalregime/index.html.twig', array(
            'fiscalRegimes' => $fiscalRegimes,
        ));
    }

    /**
     * Creates a new fiscalRegime entity.
     *
     * @Route("/new", name="fiscalregime_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $fiscalRegime = new Fiscalregime();
        $form = $this->createForm('SIGTASBundle\Form\FiscalRegimeType', $fiscalRegime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fiscalRegime);
            $em->flush();

            return $this->redirectToRoute('fiscalregime_show', array('id' => $fiscalRegime->getId()));
        }

        return $this->render('fiscalregime/new.html.twig', array(
            'fiscalRegime' => $fiscalRegime,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a fiscalRegime entity.
     *
     * @Route("/{id}", name="fiscalregime_show")
     * @Method("GET")
     */
    public function showAction(FiscalRegime $fiscalRegime)
    {
        $deleteForm = $this->createDeleteForm($fiscalRegime);

        return $this->render('fiscalregime/show.html.twig', array(
            'fiscalRegime' => $fiscalRegime,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing fiscalRegime entity.
     *
     * @Route("/{id}/edit", name="fiscalregime_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FiscalRegime $fiscalRegime)
    {
        $deleteForm = $this->createDeleteForm($fiscalRegime);
        $editForm = $this->createForm('SIGTASBundle\Form\FiscalRegimeType', $fiscalRegime);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fiscalregime_edit', array('id' => $fiscalRegime->getId()));
        }

        return $this->render('fiscalregime/edit.html.twig', array(
            'fiscalRegime' => $fiscalRegime,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a fiscalRegime entity.
     *
     * @Route("/{id}", name="fiscalregime_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FiscalRegime $fiscalRegime)
    {
        $form = $this->createDeleteForm($fiscalRegime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fiscalRegime);
            $em->flush();
        }

        return $this->redirectToRoute('fiscalregime_index');
    }

    /**
     * Creates a form to delete a fiscalRegime entity.
     *
     * @param FiscalRegime $fiscalRegime The fiscalRegime entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FiscalRegime $fiscalRegime)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fiscalregime_delete', array('id' => $fiscalRegime->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
