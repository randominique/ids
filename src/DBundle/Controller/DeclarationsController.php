<?php

namespace DBundle\Controller;

use DBundle\Entity\Declarations;
use NIFBundle\Entity\Clients;
use SIGTASBundle\Entity\Document;
use SIGTASBundle\Entity\TaxPayer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DeclarationsController extends Controller
{

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $raz = $em->getRepository(Declarations::class)->DeclarationsTruncate();

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $doctypeno = 1;
        $docstateno = 2;

        // $documents = $sigtas_em->getRepository(Document::class)->findBy(
        //     array('docTypeNo' => $doctypeno,'docStateNo' => $docstateno,'docTpYear' => $yearCourr)
        // );

        $documents = $sigtas_em->createQueryBuilder()
            ->select('d')    
            ->from(Document::class, 'd')
            ->where('d.docTypeNo = :dtp')
            ->setParameter('dtp', $doctypeno)
            ->andwhere('d.docStateNo = :dts')
            ->setParameter('dts', $docstateno)
            ->andwhere('d.docTpYear = :year')
            ->setParameter('year', $yearCourr)
            ->andwhere('d.docTpMonth <= :month')
            ->setParameter('month', 3)
            ->orderBy('d.docNo', 'ASC')
            ->getQuery()
            ->getResult();

        if ($documents) {
            foreach($documents as $document){
                $declarations = new Declarations;
                $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                    array('taxPayerNo' => $document->getTaxPayerNo())
                );
                if ($sigtasInfos) {
                    $nifInfos = $nif_em->getRepository(Clients::class)->findOneBy(
                        array('nif' => $sigtasInfos->getNif())
                    );
                    if ($nifInfos) {
                        $documentNif = $nifInfos->getNif();
                        $documentRs = $nifInfos->getRs();
                    }
                }
                $declarations->setDocNo($document->getDocNo());
                $declarations->setTaxTypeNo($document->getTaxTypeNo());
                $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                    'id' => $document->getTaxTypeNo()
                ]);
                if($taxTypeTrouver)
                {
                    $declarations->setTaxType($taxTypeTrouver->getTaxTypeDesc());
                }
                $declarations->setTaxPeriodNo($document->getTaxPeriodNo());
                $declarations->setTaxPayerNo($document->getTaxPayerNo());
                $declarations->setNif($documentNif);
                $declarations->setRs($documentRs);
                $declarations->setDocTypeNo($document->getDocTypeNo());
                $declarations->setCreatedDate($document->getCreatedDate());
                $declarations->setLetterNo($document->getLetterNo());
                $declarations->setAssessNo($document->getAssessNo());
                $declarations->setReceivedDate($document->getReceivedDate());
                $declarations->setPrintedDate($document->getPrintedDate());
                $declarations->setDocTpStartDate($document->getDocTpStartDate());
                $declarations->setDocTpEndDate($document->getDocTpEndDate());
                $declarations->setDocTpDueDate($document->getDocTpDueDate());
                $declarations->setDocTpMonth($document->getDocTpMonth());
                $declarations->setDocTpYear($document->getDocTpYear());
                $declarations->setPersonalText($document->getPersonalText());
                $declarations->setIrdFileNo($document->getIrdFileNo());
                $declarations->setInstallRateNo($document->getInstallRateNo());
                $declarations->setJobNo($document->getJobNo());
                $declarations->setStateChangeDate($document->getStateChangeDate());
                $declarations->setStateChangeUser($document->getStateChangeUser());
                $declarations->setReceipt($document->getReceipt());
                $declarations->setEstabNo($document->getEstabNo());
                $declarations->setDocStateNo($document->getDocStateNo());
                $declarations->setFormNo($document->getFormNo());
                $declarations->setVersionNo($document->getVersionNo());
                $declarations->setLicBaseNo($document->getLicBaseNo());
                $declarations->setPayAgreeNo($document->getPayAgreeNo());
                $declarations->setDocInstMonth($document->getDocInstMonth());
                $declarations->setDocInstYear($document->getDocInstYear());
                $declarations->setEntryUser($document->getEntryUser());
                $declarations->setMvRegisNo($document->getMvRegisNo());
                $declarations->setTaxCentreNo($document->getTaxCentreNo());
                $declarations->setDocTpPaymentDate($document->getDocTpPaymentDate());
                $declarations->setLicenseNo($document->getLicenseNo());
                $declarations->setExtDocNo($document->getExtDocNo());
                $declarations->setReason($document->getReason());
                $declarations->setBatchNo($document->getBatchNo());
                $declarations->setTpInstallRateNo($document->getTpInstallRateNo());
                $declarations->setDeliveredDate($document->getDeliveredDate());
                $declarations->setDistributedToIrdEmpNo($document->getDistributedToIrdEmpNo());
                $declarations->setDistributedDate($document->getDistributedDate());
                $declarations->setComments($document->getComments());
                $declarations->setReceiptPrinted($document->getReceiptPrinted());
                $declarations->setDocTpWeek($document->getDocTpWeek());
                $declarations->setDeliveredByIrdEmpNo($document->getDeliveredByIrdEmpNo());
                $declarations->setPhysicalObjectNo($document->getPhysicalObjectNo());
                $declarations->setDocSubject($document->getDocSubject());
                $declarations->setLostByIrdEmpNo($document->getLostByIrdEmpNo());
                $declarations->setLostDate($document->getLostDate());
                $declarations->setTaxPayerName($document->getTaxPayerName());
                $declarations->setNotifDate($document->getNotifDate());
                $declarations->setTitreNo($document->getTitreNo());
                $declarations->setTitreOrigine($document->getTitreOrigine());
                $em->persist($declarations);
                $em->flush();
            }
        }

        return $this->redirectToRoute('declarations_list');

    }

    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $doctypeno = 1;
        $docstateno = 2;

        // $documents = $sigtas_em->getRepository(Document::class)->findBy(
        //     array('docTypeNo' => $doctypeno,'docStateNo' => $docstateno,'docTpYear' => $yearCourr)
        // );

        $documents = $sigtas_em->createQueryBuilder()
            ->select('d')    
            ->from(Document::class, 'd')
            ->where('d.docTypeNo = :dtp')
            ->setParameter('dtp', $doctypeno)
            ->andwhere('d.docStateNo = :dts')
            ->setParameter('dts', $docstateno)
            ->andwhere('d.docTpYear = :year')
            ->setParameter('year', $yearCourr)
            ->andwhere('d.docTpMonth > :month')
            ->setParameter('month', 3)
            ->orderBy('d.docNo', 'ASC')
            ->getQuery()
            ->getResult();

        // dump($documents);
        // die();

        if ($documents) {
            foreach($documents as $document){
                $declarations = new Declarations;
                $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                    array('taxPayerNo' => $document->getTaxPayerNo())
                );
                if ($sigtasInfos) {
                    $nifInfos = $nif_em->getRepository(Clients::class)->findOneBy(
                        array('nif' => $sigtasInfos->getNif())
                    );
                    if ($nifInfos) {
                        $documentNif = $nifInfos->getNif();
                        $documentRs = $nifInfos->getRs();
                    }
                }
                $declarations->setDocNo($document->getDocNo());
                $declarations->setTaxTypeNo($document->getTaxTypeNo());
                $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                    'id' => $document->getTaxTypeNo()
                ]);
                if($taxTypeTrouver)
                {
                    $declarations->setTaxType($taxTypeTrouver->getTaxTypeDesc());
                }
                $declarations->setTaxPeriodNo($document->getTaxPeriodNo());
                $declarations->setTaxPayerNo($document->getTaxPayerNo());
                $declarations->setNif($documentNif);
                $declarations->setRs($documentRs);
                $declarations->setDocTypeNo($document->getDocTypeNo());
                $declarations->setCreatedDate($document->getCreatedDate());
                $declarations->setLetterNo($document->getLetterNo());
                $declarations->setAssessNo($document->getAssessNo());
                $declarations->setReceivedDate($document->getReceivedDate());
                $declarations->setPrintedDate($document->getPrintedDate());
                $declarations->setDocTpStartDate($document->getDocTpStartDate());
                $declarations->setDocTpEndDate($document->getDocTpEndDate());
                $declarations->setDocTpDueDate($document->getDocTpDueDate());
                $declarations->setDocTpMonth($document->getDocTpMonth());
                $declarations->setDocTpYear($document->getDocTpYear());
                $declarations->setPersonalText($document->getPersonalText());
                $declarations->setIrdFileNo($document->getIrdFileNo());
                $declarations->setInstallRateNo($document->getInstallRateNo());
                $declarations->setJobNo($document->getJobNo());
                $declarations->setStateChangeDate($document->getStateChangeDate());
                $declarations->setStateChangeUser($document->getStateChangeUser());
                $declarations->setReceipt($document->getReceipt());
                $declarations->setEstabNo($document->getEstabNo());
                $declarations->setDocStateNo($document->getDocStateNo());
                $declarations->setFormNo($document->getFormNo());
                $declarations->setVersionNo($document->getVersionNo());
                $declarations->setLicBaseNo($document->getLicBaseNo());
                $declarations->setPayAgreeNo($document->getPayAgreeNo());
                $declarations->setDocInstMonth($document->getDocInstMonth());
                $declarations->setDocInstYear($document->getDocInstYear());
                $declarations->setEntryUser($document->getEntryUser());
                $declarations->setMvRegisNo($document->getMvRegisNo());
                $declarations->setTaxCentreNo($document->getTaxCentreNo());
                $declarations->setDocTpPaymentDate($document->getDocTpPaymentDate());
                $declarations->setLicenseNo($document->getLicenseNo());
                $declarations->setExtDocNo($document->getExtDocNo());
                $declarations->setReason($document->getReason());
                $declarations->setBatchNo($document->getBatchNo());
                $declarations->setTpInstallRateNo($document->getTpInstallRateNo());
                $declarations->setDeliveredDate($document->getDeliveredDate());
                $declarations->setDistributedToIrdEmpNo($document->getDistributedToIrdEmpNo());
                $declarations->setDistributedDate($document->getDistributedDate());
                $declarations->setComments($document->getComments());
                $declarations->setReceiptPrinted($document->getReceiptPrinted());
                $declarations->setDocTpWeek($document->getDocTpWeek());
                $declarations->setDeliveredByIrdEmpNo($document->getDeliveredByIrdEmpNo());
                $declarations->setPhysicalObjectNo($document->getPhysicalObjectNo());
                $declarations->setDocSubject($document->getDocSubject());
                $declarations->setLostByIrdEmpNo($document->getLostByIrdEmpNo());
                $declarations->setLostDate($document->getLostDate());
                $declarations->setTaxPayerName($document->getTaxPayerName());
                $declarations->setNotifDate($document->getNotifDate());
                $declarations->setTitreNo($document->getTitreNo());
                $declarations->setTitreOrigine($document->getTitreOrigine());
                $em->persist($declarations);
                $em->flush();
            }
        }

        return $this->redirectToRoute('declarations_list');

    }

    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $doctypeno = 1;
        $docstateno = 2;

        $declareQuery = $em->getRepository(Declarations::class)->createQueryBuilder('e')
            ->Where('e.docTypeNo = :doctypeno')
            ->setParameter('doctypeno', $doctypeno)
            ->andWhere('e.docStateNo = :docstateno')
            ->setParameter('docstateno', $docstateno)
            ->orderBy('e.nif', 'ASC');
        
        if ($date_du && $date_au) {
            $declareQuery
                ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($nifFilter) {
            $declareQuery
                ->andWhere('e.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $declareQuery
                ->andWhere('e.rs LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }

        $declareQuery->getQuery();

        $paginator  = $this->get('knp_paginator');
        $declares = $paginator->paginate(
            $declareQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Declarations:list.html.twig', array(
            'declares' => $declares,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function editAction()
    {
        return $this->render('DBundle:Declarations:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('DBundle:Declarations:delete.html.twig', array(
            // ...
        ));
    }

    public function excelAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'DGE - Liste des declarations au ' . $createdAt . '.xlsx';

        $doctypeno = 1;
        $docstateno = 2;

        $dossierQuery = $em->getRepository(Declarations::class)->createQueryBuilder('e')
            ->Where('e.docTypeNo = :doctypeno')
            ->setParameter('doctypeno', $doctypeno)
            ->andWhere('e.docStateNo = :docstateno')
            ->setParameter('docstateno', $docstateno)
            ->orderBy('e.nif', 'ASC')
            ->getQuery()
            ->getArrayResult();

        // dump($dossierQuery);
        // die();
        // $dossierQuery = $em->getRepository(Declarations::class)->findBy(array(),array('id' => 'DESC'));

        // $dossierQuery = $em->getRepository(Declarations::class)->findAll();  // Error: Call to a member function format() on null

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les declarations de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIF ')
            ->setCellValue('B1', 'Raison sociale ')
            ->setCellValue('C1', 'Impôts ')
            ->setCellValue('D1', 'Mois ')
            ->setCellValue('E1', 'Année ')
            ->setCellValue('F1', 'Date prévue  ')
            ->setCellValue('G1', 'Date réception ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        foreach ($dossierQuery as $dossier) {
            $phpExcelObject->setActiveSheetIndex(0)
                // ->setCellValue('A' . $count, $dossier["nif"])
                // ->setCellValue('B' . $count, $dossier["rs"])
                // ->setCellValue('C' . $count, $dossier["taxType"])
                // ->setCellValue('D' . $count, $dossier["docTpMonth"])
                // ->setCellValue('E' . $count, $dossier["docTpYear"])
                // ->setCellValue('F' . $count, $dossier["docTpDueDate"])
                // ->setCellValue('G' . $count, $dossier["receivedDate"]);
                ->setCellValue('A' . $count, $dossier->getNif())
                ->setCellValue('B' . $count, $dossier->getRs())
                ->setCellValue('C' . $count, $dossier->getTaxType())
                ->setCellValue('D' . $count, $dossier->getDocTpMonth())
                ->setCellValue('E' . $count, $dossier->getDocTpYear())
                ->setCellValue('F' . $count, $dossier->getDocTpDueDate()->format('d-m-Y'))
                ->setCellValue('G' . $count, $dossier->getReceivedDate()->format('d-m-Y'));
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Liste des déclarations');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }

}
