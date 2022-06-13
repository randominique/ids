<?php

namespace DBundle\Controller;

use DBundle\Entity\Contribuables;
use DBundle\Entity\Repartition;
use DBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contribuable controller.
 *
 */
class ContribuablesController extends Controller
{
    /**
     * Lists all contribuable entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contribuables = $em->getRepository('DBundle:Contribuables')->findAll();

        $allUsers = $em->getRepository(User::class)->findAll();
        return $this->render('contribuables/index.html.twig', array(
            'contribuables' => $contribuables,
            'allUsers' => $allUsers,
        ));
    }

    /**
     * Creates a new contribuable entity.
     *
     */
    public function newAction(Request $request)
    {
        $contribuable = new Contribuable();
        $form = $this->createForm('DBundle\Form\ContribuablesType', $contribuable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contribuable);
            $em->flush();

            return $this->redirectToRoute('contribuables_show', array('id' => $contribuable->getId()));
        }

        return $this->render('contribuables/new.html.twig', array(
            'contribuable' => $contribuable,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a contribuable entity.
     *
     */
    public function showAction(Contribuables $contribuable)
    {
        $deleteForm = $this->createDeleteForm($contribuable);

        return $this->render('contribuables/show.html.twig', array(
            'contribuable' => $contribuable,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing contribuable entity.
     *
     */
    public function editAction(Request $request, Contribuables $contribuable)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($contribuable);
        $editForm = $this->createForm('DBundle\Form\ContribuablesType', $contribuable);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contribuables', array('id' => $contribuable->getId()));
        }

        $responsableQuery =$em->getRepository(User::class)->findBy(array(
            'service' => $this->getUser()->getService()->getId(),
        ));
        $allUsers = $em->getRepository(User::class)->findAll();
        return $this->render('DBundle:contribuables:edit.html.twig', array(
            'contribuable' => $contribuable,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'usersService' => $responsableQuery,
            'allUsers' => $allUsers,
        ));
    }

    /**
     * Deletes a contribuable entity.
     *
     */
    public function deleteAction(Request $request, Contribuables $contribuable)
    {
        $form = $this->createDeleteForm($contribuable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contribuable);
            $em->flush();
        }

        return $this->redirectToRoute('contribuables_index');
    }

    /**
     * Creates a form to delete a contribuable entity.
     *
     * @param Contribuables $contribuable The contribuable entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contribuables $contribuable)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contribuables_delete', array('id' => $contribuable->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function miseAjourContribuablesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contribuables = $em->getRepository(Contribuables::class)->findAll();
        foreach ($contribuables as $contribuable)
        {
            $gestionnaireId = $em->getRepository(Repartition::class)->findOneBy([
                'nif' => $contribuable->getNif()
            ]);
            if($gestionnaireId) {
                $gestionnaire = $em->getRepository(User::class)->findOneBy([
                'id' => $gestionnaireId->getGestionnaireId()
                ]);
                if($gestionnaire) {
                    $contribuable->setGestionnaire($gestionnaire->__toString());
                    $em->flush();
                }
            }
        }
        return $this->redirectToRoute('contribuables');
    }
}
