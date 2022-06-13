<?php

namespace DBundle\Controller;

use DBundle\Entity\Communication;
use DBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DBundle\Form\CommunicationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use NIFBundle\Entity\Clients as NIFOnlineClients;
use SIGTASBundle\Entity\Clients as SigtasClients;
use SIGTASBundle\Entity\FiscalRegime;
use SIGTASBundle\Entity\Enterprise;
use SIGTASBundle\Entity\SECTOR_ACTIVITY;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;


use Doctrine\DBAL\Event\Listeners\OracleSessionInit;

class CommunicationController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $communicationsQuery = $em->getRepository('DBundle:Communication')
            ->createQueryBuilder('com');

        if ($nifFilter) {
            $communicationsQuery
                ->andWhere('com.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $communicationsQuery
                ->andWhere('com.rs LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
        }
        if ($request->query->get('date_du') && $request->query->get('date_au')) {
            $communicationsQuery
                ->andWhere('com.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $request->query->get('date_du'))
                ->setParameter('date_au', $request->query->get('date_au'));
        }
        $communicationsQuery
            ->orderBy('com.id', 'DESC');
        $communicationsQuery->getQuery();

        $paginator  = $this->get('knp_paginator');
        $communications = $paginator->paginate(
            $communicationsQuery,
            $request->query->getInt('page', 1),
            20
        );

        // foreach ($communications->getItems() as $key => $communication)
        // {

        // }
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $allUsers = $em->getRepository(User::class)->findAll();

        return $this->render('DBundle:Communication:index.html.twig', array(
            'communications' => $communications,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'usersService' => $responsableQuery,
            'allUsers' => $allUsers
        ));
    }

    public function toPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $communicationsQuery = $em->getRepository('DBundle:Communication')
            ->createQueryBuilder('com');

        if ($nifFilter) {
            $communicationsQuery
                ->andWhere('com.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $communicationsQuery
                ->andWhere('com.rs LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
        }
        if ($request->query->get('date_du') && $request->query->get('date_au')) {
            $communicationsQuery
                ->andWhere('com.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $request->query->get('date_du'))
                ->setParameter('date_au', $request->query->get('date_au'));
        }
        $communicationsQuery
            ->orderBy('com.id', 'DESC');
        $communicationsQuery->getQuery();

        $paginator  = $this->get('knp_paginator');
        $communications = $paginator->paginate(
            $communicationsQuery,
            $request->query->getInt('page', 1),
            20
        );


        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Communications'));
        $pdf->SetSubject('Communications');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 10, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Communications';
        $html = $this->render('DBundle:Communication:to-pdf.html.twig', array(
            'communications' => $communications

        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    public function newAction(Request $request)
    {
        $communication = new Communication();
        $form = $this->createForm(CommunicationType::class, $communication);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $communication->setCreatedByUser($this->getUser());
            $em->persist($communication);
            $em->flush();

            return $this->redirectToRoute('communication_index');
        }

        return $this->render('DBundle:Communication:new.html.twig', array(
            'communication' => $communication,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Communication $communication)
    {
        $deleteForm = $this->createDeleteForm($communication);

        return $this->render('DBundle:Communication:show.html.twig', array(
            'communication' => $communication,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Communication $communication)
    {
        $deleteForm = $this->createDeleteForm($communication);
        $editForm = $this->createForm('DBundle\Form\CommunicationType', $communication);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('communication_edit', array('id' => $communication->getId()));
        }

        return $this->render('DBundle:Communication:edit.html.twig', array(
            'communication' => $communication,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, Communication $communication)
    {
        $form = $this->createDeleteForm($communication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($communication);
            $em->flush();
        }

        return $this->redirectToRoute('communication_index');
    }

    /**
     * Creates a form to delete a communication entity.
     *
     * @param Communication $communication The communication entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Communication $communication)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('communication_delete', array('id' => $communication->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    public function autoCompleNifAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $entrants = $em->getRepository(Communication::class)->getByNif($nif);
            $output = [];
            foreach ($entrants as $entrant) {
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');
                $nifFormated = number_format($entrant->getNif(), 0, '.', ' ');
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRs();
                $temp_array['useIt'] = $createdAt . ' - ' . $nifFormated . ' - ' . $entrant->getRs() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }
    public function autoCompleRsocAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $entrants = $em->getRepository(Communication::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($entrants as $entrant) {
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');
                $nifFormated = number_format($entrant->getNif(), 0, '.', ' ');
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRs();
                $temp_array['useIt'] = $nifFormated . ' - ' . $entrant->getRs() . ' - ' . $createdAt;

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }

    public function toExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nif = $request->query->get('nif');
        $rs = $request->query->get('rs');

        $communicationsQuery = $em->getRepository('DBundle:Communication')
            ->createQueryBuilder('com');

        if ($nif) {
            $communicationsQuery
                ->andWhere('com.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nif . '%');
        }
        if ($rs) {
            $communicationsQuery
                ->andWhere('com.rs LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rs . '%');
        }
        if ($request->query->get('date_du') && $request->query->get('date_au')) {
            $communicationsQuery
                ->andWhere('com.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $request->query->get('date_du'))
                ->setParameter('date_au', $request->query->get('date_au'));
        }
        $communicationsQuery
            ->orderBy('com.id', 'DESC');
        $communicationsQuery->getQuery();

        $paginator  = $this->get('knp_paginator');
        $communications = $paginator->paginate(
            $communicationsQuery,
            $request->query->getInt('page', 1),
            20
        );


        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("IDS")
            ->setLastModifiedBy("IDS")
            ->setTitle("Communications")
            ->setSubject("Liste des Communications")
            ->setDescription("Liste des Communications")
            ->setKeywords("office 2005 openxml php")
            ->setCategory("Category");
        $count = 5;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('B4', 'Numéro ')
            ->setCellValue('C4', 'Date ')
            ->setCellValue('D4', 'Type ')
            ->setCellValue('E4', 'N.I.F ')
            ->setCellValue('F4', 'Raison sociale ')
            ->setCellValue('G4', 'Objet ')
            ->setCellValue('H4', 'Commentaires ');

        foreach ($communications as $query) {
            $createdAt = date_format($query->getCreatedAt(), 'd-m-Y');
            // $nifFormated = number_format($query->getNif(),0 , '.', ' ');
            if ($query->getNif() != "-") {
                $nifFormated = number_format($query->getNif(), 0, '.', ' ');
            } else {
                $nifFormated = $query->getNif();
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B' . $count, $query->getId())
                ->setCellValue('C' . $count, $createdAt)
                ->setCellValue('D' . $count, $query->getTypeCommunication())
                ->setCellValue('E' . $count, $nifFormated)
                ->setCellValue('F' . $count, $query->getRs())
                ->setCellValue('G' . $count, $query->getObjet())
                ->setCellValue('H' . $count, $query->getCommentaires());
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Communications.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
