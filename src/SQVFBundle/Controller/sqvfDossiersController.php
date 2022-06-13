<?php

namespace SQVFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use DBundle\Entity\ExercicesVerifies;
use DBundle\Entity\sqvfDossiers;
use SQVFBundle\Entity\sqvf_centre_fiscal;
use SQVFBundle\Entity\sqvf_dossiers;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle_montant;
use SQVFBundle\Entity\sqvf_dossiers_etapes;
use SQVFBundle\Entity\sqvf_etapes;
use SQVFBundle\Entity\sqvf_nif;
use SQVFBundle\Entity\sqvf_type_impots;
use SQVFBundle\Entity\sqvf_type_notification;
use SQVFBundle\Entity\sqvf_type_notification_definitive;
use SQVFBundle\Entity\sqvf_type_notification_redressement;

class sqvfDossiersController extends Controller
{
    public function sqvfDossiersAction(Request $request)
    {
        // Créer sqvfDossiers à partir de sqvf_dossiers et afficher sqvfDossiers
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $services = $em->getRepository(ExercicesVerifies::class)->allTruncate();
        $nifFilter = $request->query->get('nif');

        $newDossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)  // 51 = DGE
            ->orderBy('nd.id', 'ASC')
            ->getQuery()->getResult();
        foreach ($newDossiers as $dossier) {
            $cb = new sqvfDossiers;
            $cb->setIdDossier($dossier->getId());
            $cb->setIdUser($dossier->getIdUser());
            $cb->setIdCentreFiscal($dossier->getIdCentreFiscal());
            $cb->setIdTypeNotification($dossier->getIdTypeNotification());
            $cb->setIdNotificationRedressement($dossier->getIdNotificationRedressement());
            $cb->setIdNotificationDefinitive($dossier->getIdNotificationDefinitive());
            $cb->setNif($dossier->getNif());
            $cb->setUniqid($dossier->getUniqid());
            $cb->setTypeControle($dossier->getTypeControle());
            $cb->setDateDebutOperation($dossier->getDateDebutOperation());
            $cb->setDateCreation($dossier->getDateCreation());
            $cb->setDateDebutIntervention($dossier->getDateDebutIntervention());
            $cb->setDateFinIntervention($dossier->getDateFinIntervention());
            $cb->setEtapeCourante($dossier->getEtapeCourante());
            $cb->setArchive($dossier->getArchive());
            $cb->setNewUniqid($dossier->getNewUniqid());
            $cb->setCreateTime($dossier->getCreateTime());
            $cb->setUpdateTime($dossier->getUpdateTime());
            
            $nifInfos = $sqvf_em->getRepository(sqvf_nif::class)->findOneBy(array(
                'numero' => $dossier->getNif()
            ));
            if($nifInfos)
            {
                $cb->setRs($nifInfos->getRaisonSociale());
                $cb->setAdresse($nifInfos->getAdresse());
            }
            $centreFisc = $sqvf_em->getRepository(sqvf_centre_fiscal::class)->findOneBy(array(
                'id' => $dossier->getIdCentreFiscal()
            ));
            if($centreFisc)
            {
                $cb->setCentreFiscal($centreFisc->getBureau());
            }
            $em->persist($cb);
            $em->flush();
        }

        return $this->redirectToRoute('sqvfDossiersList');

    }

    public function sqvfDossiersListAction(Request $request)
    {
        // Lister sqvfDossiers
        $em = $this->getDoctrine()->getManager();

        $nifFilter = $request->query->get('nif');

        $dossierQuery = $em->getRepository(sqvfDossiers::class)
            ->createQueryBuilder('tac')
            ->orderBy('tac.id', 'DESC');

        if ($nifFilter) {
            $dossierQuery
                ->andWhere('tac.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:sqvfDossiersList.html.twig', array(
            "dossiers" => $pagination,
            'nifFilter' => $request->query->get('nif'),
        ));
    }

    public function sqvf_dossiers_listAction(Request $request)
    {
        // Afficher $sqvf_em->sqvf_Dossiers uniquement 
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $services = $em->getRepository(ExercicesVerifies::class)->evTruncate();
        $user = $this->getUser();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossierQuery = $sqvf_em->getRepository(sqvf_dossiers::class)
            ->createQueryBuilder('tac')
            ->where('tac.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)  // 51 = DGE
            // ->andwhere('tac.id > :lastNumero')
            // ->setParameter('lastNumero', $dossiersLast)
            ->orderBy('tac.id', 'DESC')
            ->getQuery()
            ->getResult();

            foreach ($dossierQuery as $dossier) {
                $nifInfos = $sqvf_em->getRepository(sqvf_nif::class)->findOneBy(array(
                    'numero' => $dossier->getNif()
                ));
                if($nifInfos)
                {
                    $dossier->setRs($nifInfos->getRaisonSociale());
                    $dossier->setAdresse($nifInfos->getAdresse());
                }
                $typeNotif = $sqvf_em->getRepository(sqvf_type_notification::class)->findOneBy([
                    'id' =>$dossier->getIdTypeNotification()
                ]);
                if($typeNotif)
                {
                    $dossier->setTypeNotification($typeNotif->getTitre());
                }
                $typeNotifR = $sqvf_em->getRepository(sqvf_type_notification_redressement::class)->findOneBy([
                    'id' =>$dossier->getIdNotificationRedressement()
                ]);
                if($typeNotifR)
                {
                    $dossier->setNotificationRedressement($typeNotifR->getTitre());
                }
                $typeNotifD = $sqvf_em->getRepository(sqvf_type_notification_definitive::class)->findOneBy([
                    'id' =>$dossier->getIdNotificationDefinitive()
                ]);
                if($typeNotifD)
                {
                    $dossier->setNotificationDefinitive($typeNotifD->getTitre());
                }

                $dossiersEVs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                    'idDossier' => $dossier->getId(),
                ));
                $MontantPrincipal = 0;
                $MontantAmende = 0;
                $MontantTotal = 0;
                foreach ($dossiersEVs as $dossierEV)
                {
                    $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(array(
                        'idDossier' => $dossierEV->getIdDossier(),
                        'idDossierAnneeControle' => $dossierEV->getId()),
                        array('idEtape' => 'ASC','idDossierAnneeControle' => 'ASC')
                    );
                    if($synchros)
                    {
                        $MontantPrincipal == 0;
                        $MontantAmende == 0;
                        $MontantTotal == 0;
                        foreach ($synchros as $synchro)
                        {
                            $ev = new ExercicesVerifies;
                            $ev->setIdDossier($dossierEV->getIdDossier());
                            $ev->setIdDossierAnneeControle($dossierEV->getId());
                            $ev->setAnneeControle($dossierEV->getAnneeControle());
                            $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                'id' => $dossierEV->getIdDossier()
                            ));
                            if($typecontrole)
                            {
                                $ev->setTypeControle($typecontrole->getTypeControle());
                            }
                            $syncros = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                'idDossier' => $dossierEV->getIdDossier()
                            ));
                            if($syncros)
                            {
                                $ev->setDateNotificationDefinitive($syncros->getDateNotificationDefinitive());
                            }
                            $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                'id' => $dossierEV->getIdTypeImpot()
                            ));
                            if($typeImpot)
                            {
                                $ev->setTypeImpot($typeImpot->getTitre());
                            }
        
                            $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
                                'id' => $synchro->getIdEtape()
                            ));
                            if($typeEtape)
                            {
                                $ev->setEtapeCourante($typeEtape->getNom());
                            }
                            $ev->setMontantPrincipal($synchro->getMontantPrincipal());
                            $ev->setMontantAmende($synchro->getMontantAmende());
                            $ev->setMontantTotal($synchro->getMontantTotal());
                            if($synchro->getIdEtape() == 2 or $synchro->getIdEtape() == 4 or $synchro->getIdEtape() == 6 or $synchro->getIdEtape() == 8)
                            {
                                $dossier->setMontantPrincipal($MontantPrincipal += $synchro->getMontantPrincipal());
                                $dossier->setMontantAmende($MontantAmende += $synchro->getMontantAmende());
                                $dossier->setMontantTotal($MontantTotal += $synchro->getMontantTotal());
                            }
                        }
                    }
                }
    
            }
            
        if ($nifFilter) {
            $dossierQuery
                ->andWhere('tac.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $dossierQuery
                ->andWhere('tac.rs LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
        }
    
        $dossierQuery
            ->getQuery();
            // ->getResult();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('SQVFBundle:Dossiers:list.html.twig', array(
            "dossiers" => $pagination,
            // "nbreContrib" => $countFolder,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function sqvfDossiersExcelAction(Request $request)
    {
        // die('export');
        // création du fichier excel à partir de $em->sqvfDossiers
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $dossierQuery = $em->getRepository(sqvfDossiers::class)->findBy(array(),array('id' => 'DESC'));
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les dossiers SQVF de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        // $phpExcelObject->setActiveSheetIndex(0)
        //     ->setCellValue('A1', 'DIRECTION DES GRANDES ENTREPRISES ');

        // $phpExcelObject->setActiveSheetIndex(0)
        // ->setCellValue('A3', 'LISTE DES DOSSIERS ');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Référence ')
            ->setCellValue('B1', 'NIF ')
            ->setCellValue('C1', 'Raison sociale ')
            ->setCellValue('D1', 'Centre fiscal ')
            ->setCellValue('E1', 'Type ')
            ->setCellValue('F1', 'Etape ')
            ->setCellValue('G1', 'Date création ')
            ->setCellValue('H1', 'Montant principal ')
            ->setCellValue('I1', 'Montant amende ')
            ->setCellValue('J1', 'Montant total ');
    
        foreach ($dossierQuery as $dossier) {
            $nifInfos = $sqvf_em->getRepository(sqvf_nif::class)->findOneBy(array(
                'numero' => $dossier->getNif()
            ));
            if($nifInfos)
            {
                $dossier->setRs($nifInfos->getRaisonSociale());
                $dossier->setAdresse($nifInfos->getAdresse());
            }
            // $typeNotif = $sqvf_em->getRepository(sqvf_type_notification::class)->findOneBy([
            //     'id' =>$dossier->getIdTypeNotification()
            // ]);
            // if($typeNotif)
            // {
            //     $dossier->setTypeNotification($typeNotif->getTitre());
            // }
            // $typeNotifR = $sqvf_em->getRepository(sqvf_type_notification_redressement::class)->findOneBy([
            //     'id' =>$dossier->getIdNotificationRedressement()
            // ]);
            // if($typeNotifR)
            // {
            //     $dossier->setNotificationRedressement($typeNotifR->getTitre());
            // }
            // $typeNotifD = $sqvf_em->getRepository(sqvf_type_notification_definitive::class)->findOneBy([
            //     'id' =>$dossier->getIdNotificationDefinitive()
            // ]);
            // if($typeNotifD)
            // {
            //     $dossier->setNotificationDefinitive($typeNotifD->getTitre());
            // }

            // Attention !!! id de sqvfDossiers ne correspond pas à id_dossier, contrairement à sqf_dossiers 
            $dossiersEVs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                'idDossier' => $dossier->getId(),
            ));
            $MontantPrincipal = 0;
            $MontantAmende = 0;
            $MontantTotal = 0;
            foreach ($dossiersEVs as $dossierEV)
            {
                $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(array(
                    'idDossier' => $dossierEV->getIdDossier(),
                    'idDossierAnneeControle' => $dossierEV->getId()),
                    array('idEtape' => 'ASC','idDossierAnneeControle' => 'ASC')
                );
                if($synchros)
                {
                    $MontantPrincipal == 0;
                    $MontantAmende == 0;
                    $MontantTotal == 0;
                    foreach ($synchros as $synchro)
                    {
                        $ev = new ExercicesVerifies;
                        $ev->setIdDossier($dossierEV->getIdDossier());
                        $ev->setIdDossierAnneeControle($dossierEV->getId());
                        $ev->setAnneeControle($dossierEV->getAnneeControle());
                        $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                            'id' => $dossierEV->getIdDossier()
                        ));
                        if($typecontrole)
                        {
                            $ev->setTypeControle($typecontrole->getTypeControle());
                        }
                        $syncros = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                            'idDossier' => $dossierEV->getIdDossier()
                        ));
                        if($syncros)
                        {
                            $ev->setDateNotificationDefinitive($syncros->getDateNotificationDefinitive());
                        }
                        $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                            'id' => $dossierEV->getIdTypeImpot()
                        ));
                        if($typeImpot)
                        {
                            $ev->setTypeImpot($typeImpot->getTitre());
                        }
    
                        $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
                            'id' => $synchro->getIdEtape()
                        ));
                        if($typeEtape)
                        {
                            $ev->setEtapeCourante($typeEtape->getNom());
                        }
                        $ev->setMontantPrincipal($synchro->getMontantPrincipal());
                        $ev->setMontantAmende($synchro->getMontantAmende());
                        $ev->setMontantTotal($synchro->getMontantTotal());
                        // if($synchro->getMontantPrincipal())
                        // {
                        //     $em->persist($ev);
                        //     $em->flush();
                        // }
                        if($synchro->getIdEtape() == 2 or $synchro->getIdEtape() == 4 or $synchro->getIdEtape() == 6 or $synchro->getIdEtape() == 8)
                        {
                            $dossier->setMontantPrincipal($MontantPrincipal += $synchro->getMontantPrincipal());
                            $dossier->setMontantAmende($MontantAmende += $synchro->getMontantAmende());
                            $dossier->setMontantTotal($MontantTotal += $synchro->getMontantTotal());
                        }
                    }
                }
            }
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier->getUniqid())
                ->setCellValue('B' . $count, $dossier->getNif())
                ->setCellValue('C' . $count, $dossier->getRs())
                ->setCellValue('D' . $count, $dossier->getCentreFiscal())
                ->setCellValue('E' . $count, $dossier->getTypeControle())
                ->setCellValue('F' . $count, $dossier->getEtapeCourante())
                ->setCellValue('G' . $count, $dossier->getDateCreation())
                ->setCellValue('H' . $count, $dossier->getMontantPrincipal())
                ->setCellValue('I' . $count, $dossier->getMontantAmende())
                ->setCellValue('J' . $count, $dossier->getMontantTotal());
            $count++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Liste des dossiers');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'SQVF - Liste des dossiers .xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;

    }

}
