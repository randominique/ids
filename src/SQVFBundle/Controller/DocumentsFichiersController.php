<?php

namespace SQVFBundle\Controller;

use SQVFBundle\Entity\DocumentsFichiers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Documentsfichier controller.
 *
 * @Route("documentsfichiers")
 */
class DocumentsFichiersController extends Controller
{
    /**
     * Lists all documentsFichier entities.
     *
     * @Route("/", name="documentsfichiers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $documentsFichiers = $sqvf_em->getRepository(DocumentsFichiers::class)->findAll();

        return $this->render('documentsfichiers/index.html.twig', array(
            'documentsFichiers' => $documentsFichiers,
        ));
    }

    /**
     * Creates a new documentsFichier entity.
     *
     * @Route("/new", name="documentsfichiers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $documentsFichier = new Documentsfichier();
        $form = $this->createForm('SQVFBundle\Form\DocumentsFichiersType', $documentsFichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($documentsFichier);
            $em->flush();

            return $this->redirectToRoute('documentsfichiers_show', array('id' => $documentsFichier->getId()));
        }

        return $this->render('documentsfichiers/new.html.twig', array(
            'documentsFichier' => $documentsFichier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a documentsFichier entity.
     *
     * @Route("/{id}", name="documentsfichiers_show")
     * @Method("GET")
     */
    public function showAction(DocumentsFichiers $documentsFichier)
    {
        $deleteForm = $this->createDeleteForm($documentsFichier);

        return $this->render('documentsfichiers/show.html.twig', array(
            'documentsFichier' => $documentsFichier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing documentsFichier entity.
     *
     * @Route("/{id}/edit", name="documentsfichiers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DocumentsFichiers $documentsFichier)
    {
        $deleteForm = $this->createDeleteForm($documentsFichier);
        $editForm = $this->createForm('SQVFBundle\Form\DocumentsFichiersType', $documentsFichier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('documentsfichiers_edit', array('id' => $documentsFichier->getId()));
        }

        return $this->render('documentsfichiers/edit.html.twig', array(
            'documentsFichier' => $documentsFichier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a documentsFichier entity.
     *
     * @Route("/{id}", name="documentsfichiers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DocumentsFichiers $documentsFichier)
    {
        $form = $this->createDeleteForm($documentsFichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($documentsFichier);
            $em->flush();
        }

        return $this->redirectToRoute('documentsfichiers_index');
    }

    /**
     * Creates a form to delete a documentsFichier entity.
     *
     * @param DocumentsFichiers $documentsFichier The documentsFichier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DocumentsFichiers $documentsFichier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('documentsfichiers_delete', array('id' => $documentsFichier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
