<?php

namespace SQVFBundle\Controller;

use DBundle\Entity\DossiersSQVF;
use DBundle\Entity\ExercicesVerifies;
use DBundle\Entity\sqvfDossiers;
use DBundle\Entity\sqvfDossiersAnneeControle;
use DBundle\Entity\sqvfDossiersAnneeControleMontant;
use DBundle\Entity\sqvfNif;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use SQVFBundle\Entity\sqvf_documents_fichiers;
use SQVFBundle\Entity\sqvf_dossiers;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle;
use SQVFBundle\Entity\sqvf_nif;
use SQVFBundle\Entity\sqvf_type_notification_definitive;
use SQVFBundle\Entity\sqvf_type_notification;
use SQVFBundle\Entity\sqvf_type_notification_redressement;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle_montant;
use SQVFBundle\Entity\sqvf_dossiers_etapes;
use SQVFBundle\Entity\sqvf_etapes;
use SQVFBundle\Entity\sqvf_type_impots;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

class sqvfDatabaseController extends Controller
{
    public function sqvfDossiersAnneeControleAction()
    {   
        // Créer sqvfDossiersAnneeControle à partir de sqvf_dossiers_annee_controle
        // en partant de DossiersSQVF 
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        // $dossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(
        //     ['idDossier' => 2719, 'idDossier' => 5421]
        // );

        $dossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->createQueryBuilder('d')
            ->where('d.idDossier = :idD1')
            ->setParameter('idD1', 2719)
            ->orWhere('d.idDossier = :idD2')
            ->setParameter('idD2', 5421)
            ->orderBy('d.idDossier', 'ASC')
            ->getQuery()
            ->getResult();
        // dump($dossiers);  // c'est bon
        // die();
        foreach ($dossiers as $dossier) {
            // die('ok');  // c'est bon
            $sqvfDACs = $em->getRepository(sqvfDossiersAnneeControle::class)->findOneBy(
                array('idDossier' => $dossier->getIdDossier(), 'idDossierAnneeControle' => $dossier->getId()),
                array('id' => 'ASC')
            );
            if (!$sqvfDACs)
            {
                // foreach ($sqvfDACs as $sqvfDAC) {
                    $cb = new sqvfDossiersAnneeControle;
                    $cb->setIdDossierAnneeControle($dossier->getId() );
                    $cb->setIdDossier($dossier->getIdDossier());
                    $cb->setIdTypeImpot($dossier->getIdTypeImpot());
                    $cb->setAnneeControle($dossier->getAnneeControle());
                    $em->persist($cb);
                    $em->flush();
                // }
            }
            $sDACs = $em->getRepository(DossiersSQVF::class)->findOneBy(
                array('idDossier' => $dossier->getIdDossier()),
                array('id' => 'ASC')
            );
            if(!$sDACs){
                $dos = new DossiersSQVF;
                $dos->setIdDossier($dossier->getIdDossier() );
                $em->persist($dos);
                $em->flush();
            }
        }
        return $this->redirectToRoute('sqvfDossiersAnneeControleList');
    }

    public function sqvfDossiersAnneeControleListAction(Request $request)
    {   
        // afficher sqvf_dossiers_annee_controle
        $em = $this->getDoctrine()->getManager();
        $dossierQuery = $em->getRepository(sqvfDossiersAnneeControle::class)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:sqvfDossiersAnneeControleList.html.twig', array(
            "dossiers" => $pagination,
            'idDossierFilter' => $request->query->get('idDossier'),
        ));
    }

    public function sqvfDossiersAnneeControleMontantAction(Request $request)
    {   
        // Créer sqvfDossiersAnneeControleMontant à partir de sqvf_dossiers_annee_controle_montant
        // et afficher sqvf_dossiers_annee_controle_montant
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $nifFilter = $request->query->get('nif');

        $dossierQuery = $em->getRepository(sqvfDossiersAnneeControle::class)->findAll();
        
        foreach ($dossierQuery as $dossier) {
            $dossiersMontants = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                array('idDossier' => $dossier->getIdDossier()),
                array('id' => 'ASC')
            );
            foreach ($dossiersMontants as $dossiersMontant) {
                $cb = new sqvfDossiersAnneeControleMontant;
                $cb->setIdDossier($dossiersMontant->getIdDossier());
                $cb->setIdDossierAnneeControle($dossiersMontant->getIdDossierAnneeControle());
                $cb->setIdEtape($dossiersMontant->getIdEtape());
                $cb->setMontantPrincipal($dossiersMontant->getMontantPrincipal());
                $cb->setMontantAmende($dossiersMontant->getMontantAmende());
                $cb->setMontantTotal($dossiersMontant->getMontantTotal());
                $em->persist($cb);
                $em->flush();
            }
        }

        return $this->redirectToRoute('sqvfDossiersAnneeControleMontantList');
    }

    public function sqvfDossiersAnneeControleMontantListAction(Request $request)
    {   
        // die('ok');
        // afficher sqvf_dossiers_annee_controle_montant
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        // $dossierQuery = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findAll();
        $dossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)
            ->createQueryBuilder('d')
            ->where('d.idDossier = :idD1')
            ->setParameter('idD1', 2719)
            ->orWhere('d.idDossier = :idD2')
            ->setParameter('idD2', 5421)
            ->orderBy('d.idDossier', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($dossiers as $dossier) {
            $sDACs = $em->getRepository(DossiersSQVF::class)->findOneBy(
                array('idDossier' => $dossier->getIdDossier()),
                array('id' => 'ASC')
            );
            if(!$sDACs){
                $dos = new DossiersSQVF;
                $dos->setIdDossier($dossier->getIdDossier() );
                $dos->setMP($dossier->getMontantPrincipal());
                $dos->setMontantAmende($dossier->getMontantAmende());
                $dos->setMontantTotal($dossier->getMontantTotal());
                $em->persist($dos);
                $em->flush();
            }
            // else
            // {
            //     $dos->setMontantPrincipal($dossier->getMontantPrincipal());
            //     $dos->setMontantAmende($dossier->getMontantAmende());
            //     $dos->setMontantTotal($dossier->getMontantTotal());
            // }
        }
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossiers,
            $request->query->getInt('page', 1),
            20
        );

        return $this->redirectToRoute('dossiers');
        // return $this->render('SQVFBundle:Dossiers:sqvfDossiersAnneeControleMontantList.html.twig', array(
        //     "dossiers" => $pagination,
        //     'idDossierFilter' => $request->query->get('id dossier'),
        // ));
    }

    public function sqvfNifAction(Request $request)
    {   
        // Créer sqvfNif à partir de sqvf_nif
        // et afficher sqvf_nif
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $nifFilter = $request->query->get('nif');

        $newDossiers = $em->getRepository(DossiersSQVF::class)->findAll();
        
        foreach ($newDossiers as $dossier) {
            $dossiersNifs = $sqvf_em->getRepository(sqvf_nif::class)->findBy(
                array('numero' => $dossier->getNif()),
                array('id' => 'ASC')
            );
            if ($dossiersNifs) {
                $cb = new sqvfNif;
                $cb->setNumero($dossier->getNumero());
                $cb->setRaisonSociale($dossier->getRaisonSociale());
                $cb->setAdresse($dossier->getAdresse());
                $cb->setCentreGestionnaire($dossier->getCentreGestionnaire());
                $cb->setCodeBureau($dossier->getCodeBureau());
                $em->persist($cb);
                $em->flush();
            }
        }
        return $this->redirectToRoute('contribuables');
    }

    public function sqvfNifListAction(Request $request)
    {   
        // afficher sqvf_nif
        $em = $this->getDoctrine()->getManager();

        $dossierQuery = $em->getRepository(sqvfNif::class)->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:sqvfNifList.html.twig', array(
            "dossiers" => $pagination,
            'nifFilter' => $request->query->get('nif'),
        ));
    }

    public function evTruncateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $user = $this->getUser()->getUsername();
        $services = $em->getRepository(ExercicesVerifies::class)->evTruncate();
        return $this->redirectToRoute('dossiers');
    }

    public function createExercicesVerifiesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossiers = $em->getRepository(ExercicesVerifies::class)->createQueryBuilder('d')
            ->where('d.id > :numrec')
            ->setParameter('numrec', 999)
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
        // dump($dossiers);
        // die();
        foreach ($dossiers as $dossier) {
            $uniqid = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                'id' => $dossier->getIdDossier()
            ));
            // dump($uniqid);
            // die();
            if($uniqid)
            {
                $dossier->setUniqid($uniqid->getUniqid());
                $dossier->setNif($uniqid->getNif());
            }
            $em->flush();
        }

        // $dossierQuery = $em->getRepository(ExercicesVerifies::class)
        //     ->createQueryBuilder('tac')
        //     ->orderBy('tac.id', 'DESC');

        $dossierQuery = $em->getRepository(DossiersSQVF::class)
            ->createQueryBuilder('tac')
            ->where('tac.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            ->orderBy('tac.id', 'ASC');

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

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('SQVFBundle:Dossiers:list.html.twig', array(
            "dossiers" => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function sqvfDossiersExcelAction(Request $request)
    {
        // création du fichier excel à partir de DossiersSQVF
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $dossierQuery = $em->getRepository(DossiersSQVF::class)->findBy(array(
            'idCentreFiscal' => 51,
        ),array('id' => 'DESC')
        );
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
            // ->setCellValue('D1', 'Centre fiscal ')
            ->setCellValue('D1', 'Type ')
            ->setCellValue('E1', 'Etape courante ')
            ->setCellValue('F1', 'Date création ')
            ->setCellValue('G1', 'Montant principal ')
            ->setCellValue('H1', 'Montant amende ')
            ->setCellValue('I1', 'Montant total ');
    
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
                        if($synchro->getMontantPrincipal())
                        {
                            $em->persist($ev);
                            $em->flush();
                        }
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
                // ->setCellValue('D' . $count, $dossier->getNotificationDefinitive())
                ->setCellValue('D' . $count, $dossier->getTypeControle())
                ->setCellValue('E' . $count, $dossier->getEtapeCourante())
                ->setCellValue('F' . $count, $dossier->getDateCreation())
                ->setCellValue('G' . $count, $dossier->getMontantPrincipal())
                ->setCellValue('H' . $count, $dossier->getMontantAmende())
                ->setCellValue('I' . $count, $dossier->getMontantTotal());
            $count++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Liste des dossiers');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
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

    public function addDossiersSQVFAction(Request $request)
    {
        // Créer DossiersSQVF à partir de sqvfDossiers
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $dossiersSource = $em->getRepository(sqvfDossiers::class)->findBy(
        array('idCentreFiscal' => 51),
        array('id' => 'ASC')
        );

        // if($dossiersSource) {
            foreach ($dossiersSource as $dossier) {
                $cb = new DossiersSQVF;
                $cb->setIdDossier($dossier->getId());
                $cb->setIdUser($dossier->getIdUser());
                $cb->setIdCentreFiscal($dossier->getIdCentreFiscal());
                $cb->setNif($dossier->getNif());
                $nifInfos = $sqvf_em->getRepository(sqvf_nif::class)->findOneBy(array(
                    'numero' => $dossier->getNif()
                ));
                if($nifInfos)
                {
                    $cb->setRs($nifInfos->getRaisonSociale());
                    $cb->setAdresse($nifInfos->getAdresse());
                }
                $cb->setIdTypeNotification($dossier->getIdTypeNotification());
                $typeNotif = $sqvf_em->getRepository(sqvf_type_notification::class)->findOneBy([
                    'id' =>$dossier->getIdTypeNotification()
                ]);
                if($typeNotif)
                {
                    $cb->setTypeNotification($typeNotif->getTitre());
                }
                $cb->setIdNotificationRedressement($dossier->getIdNotificationRedressement());
                $typeNotifR = $sqvf_em->getRepository(sqvf_type_notification_redressement::class)->findOneBy([
                    'id' =>$dossier->getIdNotificationRedressement()
                ]);
                if($typeNotifR)
                {
                    $cb->setNotificationRedressement($typeNotifR->getTitre());
                }
                $cb->setIdNotificationDefinitive($dossier->getIdNotificationDefinitive());
                $typeNotifD = $sqvf_em->getRepository(sqvf_type_notification_definitive::class)->findOneBy([
                    'id' =>$dossier->getIdNotificationDefinitive()
                ]);
                if($typeNotifD)
                {
                    $cb->setNotificationDefinitive($typeNotifD->getTitre());
                }
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
                            if($synchro->getMontantPrincipal())
                            {
                                $em->persist($ev);
                                $em->flush();
                            }
                            if($synchro->getIdEtape() == 2 or $synchro->getIdEtape() == 4 or $synchro->getIdEtape() == 6 or $synchro->getIdEtape() == 8)
                            {
                                $cb->setMontantPrincipal($MontantPrincipal += $synchro->getMontantPrincipal());
                                $cb->setMontantAmende($MontantAmende += $synchro->getMontantAmende());
                                $cb->setMontantTotal($MontantTotal += $synchro->getMontantTotal());
                            }
                        }
                    }
                }
    
                $em->persist($cb);
                $em->flush();
            }
        // }

        $dossierQuery = $em->getRepository(DossiersSQVF::class)
            ->createQueryBuilder('tac')
            ->orderBy('tac.id', 'DESC');

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
        ));
    }

}