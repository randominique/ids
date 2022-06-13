<?php

namespace DBundle\Controller;

use DBundle\Entity\Declarations;
use NIFBundle\Entity\Clients;
use SIGTASBundle\Entity\Document;
use SIGTASBundle\Entity\TaxPayer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DeclarationsController extends Controller
{
    public function listAction()
    {
        return $this->render('DBundle:Declarations:list.html.twig', array(
            // ...
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

    public function updateAction()
    {
        return $this->render('DBundle:Declarations:update.html.twig', array(
            // ...
        ));
    }

    public function createAction(Request $request)
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

        $documents = $sigtas_em->getRepository(Document::class)->findBy(
            array('docTypeNo' => $doctypeno,'docStateNo' => $docstateno,'docTpYear' => $yearCourr)
        );
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
                    /*
                    $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                        'taxPayerNo' => $sigtasInfos->taxPayerNo
                    ]);
                    if($enterprise)
                    {
                        $taxpayer->secteurActivite = $enterprise->secteurActivite->sectorActDesc;
                    }else{
                        $taxpayer->secteurActivite = '-'; 
                    }
                    $documentSensitive = $sigtasInfos->sensitive;
                    $documentRegimeFiscal = $sigtasInfos->regimeFiscal;
                    */
                }
                $declarations->setDocNo($document->getDocNo());
                $declarations->setTaxTypeNo($document->getTaxTypeNo());
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

        $declareQuery = $em->getRepository(Declarations::class)->createQueryBuilder('e')
            ->Where('e.doctypeno = :doctypeno')
            ->setParameter('doctypeno', $doctypeno)
            ->Where('e.docstateno = :docstateno')
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
                ->andWhere('e.raisonSocial LIKE :rs')
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

}
