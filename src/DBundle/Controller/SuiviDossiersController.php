<?php

namespace DBundle\Controller;

use DBundle\Entity\SuiviDossiers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Suividossier controller.
 *
 * @Route("suividossiers")
 */
class SuiviDossiersController extends Controller
{
    /**
     * Lists all suiviDossier entities.
     *
     * @Route("/", name="suividossiers_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $suiviDossiers = $em->getRepository('DBundle:SuiviDossiers')->findAll();

        return $this->render('suividossiers/index.html.twig', array(
            'suiviDossiers' => $suiviDossiers,
        ));
    }

    /**
     * Creates a new suiviDossier entity.
     *
     * @Route("/new", name="suividossiers_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $suiviDossier = new SuiviDossiers();
        $suiviDossier->setDateEnlevement(new \DateTime());
        $suiviDossier->setResponsableSortie($this->getUser());
        $form = $this->createForm('DBundle\Form\SuiviDossiersType', $suiviDossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($suiviDossier);
            $em->flush();

            return $this->redirectToRoute('suividossiers_show', array('id' => $suiviDossier->getId()));
        }

        return $this->render('suividossiers/new.html.twig', array(
            'suiviDossier' => $suiviDossier,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a suiviDossier entity.
     *
     * @Route("/{id}", name="suividossiers_show")
     * @Method("GET")
     */
    public function showAction(SuiviDossiers $suiviDossier)
    {
        $deleteForm = $this->createDeleteForm($suiviDossier);

        return $this->render('suividossiers/show.html.twig', array(
            'suiviDossier' => $suiviDossier,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing suiviDossier entity.
     *
     * @Route("/{id}/remettre/le/dossier", name="suividossiers_remettre")
     * @Method({"GET"})
     */
    public function remettreAction(SuiviDossiers $suiviDossier)
    {
        $em = $this->getDoctrine()->getManager();
        $suiviDossier->setResponsableRemise($this->getUser());
        $suiviDossier->setDateRemise(new \DateTime());
        $em->flush();
        return $this->redirectToRoute('suividossiers_show', array('id' => $suiviDossier->getId()));
    }

    /**
     * Displays a form to edit an existing suiviDossier entity.
     *
     * @Route("/{id}/edit", name="suividossiers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SuiviDossiers $suiviDossier)
    {
        $deleteForm = $this->createDeleteForm($suiviDossier);
        $editForm = $this->createForm('DBundle\Form\SuiviDossiersType', $suiviDossier);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('suividossiers_edit', array('id' => $suiviDossier->getId()));
        }

        return $this->render('suividossiers/edit.html.twig', array(
            'suiviDossier' => $suiviDossier,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a suiviDossier entity.
     *
     * @Route("/{id}", name="suividossiers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SuiviDossiers $suiviDossier)
    {
        $form = $this->createDeleteForm($suiviDossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($suiviDossier);
            $em->flush();
        }

        return $this->redirectToRoute('suividossiers_index');
    }

    /**
     * Creates a form to delete a suiviDossier entity.
     *
     * @param SuiviDossiers $suiviDossier The suiviDossier entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SuiviDossiers $suiviDossier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('suividossiers_delete', array('id' => $suiviDossier->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
