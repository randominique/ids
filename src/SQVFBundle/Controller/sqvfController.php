<?php

namespace SQVFBundle\Controller;

use DBundle\Entity\DossiersSQVF;
use DBundle\Entity\ExercicesVerifies;
use DBundle\Entity\sqvfDossiers;
use DBundle\Entity\sqvfDossiersAnneeControle;
use DBundle\Entity\VerificationsEnCours;
use SQVFBundle\Entity\sqvf_agents_verificateurs;
use SQVFBundle\Entity\sqvf_centre_fiscal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use SQVFBundle\Entity\sqvf_documents_fichiers;
use SQVFBundle\Entity\sqvf_dossiers;
use SQVFBundle\Entity\sqvf_dossiers_agent_verificateur;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle;
use SQVFBundle\Entity\sqvf_nif;
use SQVFBundle\Entity\sqvf_type_notification_definitive;
use SQVFBundle\Entity\sqvf_type_notification;
use SQVFBundle\Entity\sqvf_type_notification_redressement;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle_montant;
use SQVFBundle\Entity\sqvf_dossiers_annules;
use SQVFBundle\Entity\sqvf_dossiers_etapes;
use SQVFBundle\Entity\sqvf_etapes;
use SQVFBundle\Entity\sqvf_type_impots;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;

class sqvfController extends Controller
{
    public function addDossiersAction(Request $request)
    {
        // Créer DossiersSQVF sans montants à partir de sqvf_dossiers
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $raz1 = $em->getRepository(ExercicesVerifies::class)->DossiersSQVFTruncate();
        $nifFilter = $request->query->get('nif');
        
        $newDossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()
            ->getResult();
        foreach ($newDossiers as $dossier) {
            $cb = new DossiersSQVF;
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

        return $this->redirectToRoute('dossiers');

    }

    public function createExercicesVerifiesAction(Request $request)
    {
        // créer ExercicesVerifies avec sqvf_dossiers comme table-primaire
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $raz2 = $em->getRepository(ExercicesVerifies::class)->evTruncate();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            // ->andWhere('nd.nif = :niF')
            // ->setParameter('niF', 3000004563)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($dossiers as $dossier) {
            $dossiersEVs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                'idDossier' => $dossier->getId(),
            ));
            foreach ($dossiersEVs as $dossierEV)
            {
                $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                    array('idDossier' => $dossier->getId(),'idDossierAnneeControle' => $dossierEV->getId()),
                    array('idEtape' => 'ASC','idDossier' => 'ASC','idDossierAnneeControle' => 'ASC')
                );
                if($synchros)
                {
                    foreach ($synchros as $synchro)
                    {
                        if ($synchro->getIdEtape() == 6)
                        {
                            $ev = new ExercicesVerifies;
                            $ev->setIdDossier($dossierEV->getIdDossier());
                            $ev->setIdDossierAnneeControle($dossierEV->getId());
                            $ev->setUniqid($dossier->getUniqid());
                            $ev->setNif($dossier->getNif());
                            $ev->setAnneeControle($dossierEV->getAnneeControle());
                            $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                'id' => $dossierEV->getIdDossier()
                            ));
                            if($typecontrole)
                            {
                                $ev->setTypeControle($typecontrole->getTypeControle());
                            }
                            $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                'idDossier' => $dossier->getId()
                            ));
                            if($sdes)
                            {
                                $ev->setDateNotificationDefinitive($sdes->getDateNotificationDefinitive());
                            }
                            $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                'id' => $dossierEV->getIdTypeImpot()
                            ));
                            if($typeImpot)
                            {
                                $ev->setTypeImpot($typeImpot->getTitre());
                            }
                            $ev->setIdEtape($synchro->getIdEtape());
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
                            $em->persist($ev);
                            $em->flush();
                        }
                    }
                }
            }
        }

        return $this->redirectToRoute('list_exercicesverifies');

    }

    public function listExercicesVerifiesAction(Request $request)
    {   
        // Lister ExercicesVerifies
        $em = $this->getDoctrine()->getManager();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossierQuery = $em->createQueryBuilder()
            ->select('nd')    
            ->from(ExercicesVerifies::class, 'nd')
            // ->where('nd.idCentreFiscal = :idCF')
            // ->setParameter('idCF', 51)
            ->orderBy('nd.id', 'DESC');

        if ($nifFilter) {
            $dossierQuery
                ->andWhere('nd.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:listExercicesVerifies.html.twig', array(
            "exercicesVerifies" => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function createVerificationsEnCoursAction(Request $request)
    {
        // créer VerificationsEnCours avec DossiersSQVF comme table-primaire
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $raz3 = $em->getRepository(ExercicesVerifies::class)->vecTruncate();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        // au début, on utilise les lignes suivantes
        $dossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            // ->andWhere('nd.nif = :niF')
            // ->setParameter('niF', 3000004563)
            // ->andwhere('nd.id <= :finIdEnr')
            // ->setParameter(':finIdEnr', 200)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()->getResult();

        // ensuite, passez en commentaires evTruncate() & les lignes précédentes et on utilise les lignes suivantes
        // $enLast = $em->createQueryBuilder()
        // ->select('MAX(ev.id)')
        // ->from(VerificationsEnCours::class, 'ev')
        // ->getQuery()
        // ->getScalarResult();

        // $evLast = $em->createQueryBuilder()
        // ->select('ev.uniqid')
        // ->from(VerificationsEnCours::class, 'ev')
        // ->where('ev.id = :finIdEnr')
        // ->setParameter(':finIdEnr', $enLast)
        // ->getQuery()
        // ->getScalarResult();

        // $dossiersSQVFId = $em->createQueryBuilder()
        //     ->select('nc.id')
        //     ->from(DossiersSQVF::class, 'nc')
        //     ->where('nc.uniqid = :evLast')
        //     ->setParameter('evLast', $evLast)
        //     ->orderBy('nc.id', 'ASC')
        //     ->getQuery()
        //     ->getResult();
    
        // $dossiers = $em->createQueryBuilder()
        //     ->select('nd')    
        //     ->from(DossiersSQVF::class, 'nd')
        //     ->where('nd.id > :finIdEnr')
        //     ->setParameter(':finIdEnr', $dossiersSQVFId)
        //     ->orderBy('nd.id', 'ASC')
        //     ->getQuery()->getResult();

        foreach ($dossiers as $dossier) {
            $dossiersVECs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                'idDossier' => $dossier->getId(),
            ));
            foreach ($dossiersVECs as $dossierVEC)
            {
                $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                    array('idDossier' => $dossier->getId(),'idDossierAnneeControle' => $dossierVEC->getId()),
                    array('idEtape' => 'ASC','idDossier' => 'ASC','idDossierAnneeControle' => 'ASC')
                );
                if($synchros)
                {
                    foreach ($synchros as $synchro)
                    {
                        if ($synchro->getIdEtape() == 1 or $synchro->getIdEtape() == 2 or $synchro->getIdEtape() == 3 or $synchro->getIdEtape() == 4) {
                            $vec = new VerificationsEnCours;
                            $vec->setIdUser($dossier->getIdUser());
                            $vec->setIdDossier($dossierVEC->getIdDossier());
                            $vec->setIdDossierAnneeControle($dossierVEC->getId());
                            $vec->setUniqid($dossier->getUniqid());
                            $vec->setNif($dossier->getNif());
                            $vec->setAnneeControle($dossierVEC->getAnneeControle());
                            $agentVerifId = $sqvf_em->getRepository(sqvf_dossiers_agent_verificateur::class)->findOneBy(array(
                                'idDossier' => $dossierVEC->getIdDossier()
                            ));
                            if($agentVerifId)
                            {
                                $agentVerif = $sqvf_em->getRepository(sqvf_agents_verificateurs::class)->findOneBy(array(
                                    'id' => $agentVerifId->getIdAgentVerificateur()
                                ));
                                if($agentVerif)
                                {
                                $vec->setVerificateur($agentVerif->getNom());
                                }
                            }
                            $sqvfDosssiersDB = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                'id' => $dossierVEC->getIdDossier()
                            ));
                            if($sqvfDosssiersDB)
                            {
                                $vec->setIdUser($sqvfDosssiersDB->getIdUser());
                                $vec->setTypeControle($sqvfDosssiersDB->getTypeControle());
                            }
                            $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                'idDossier' => $dossierVEC->getIdDossier()
                            ));
                            if($sdes)
                            {
                                $vec->setNumeroAvisDeVerification($sdes->getAvisDeVerification());
                                $vec->setDateAvisDeVerification($sdes->getDateAvisDeVerification());
                                $vec->setNumeroNotificationPrimitive($sdes->getNotificationPrimitive());
                                $vec->setDateNotificationPrimitive($sdes->getDateNotificationPrimitive());
                            }
                            $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                'id' => $dossierVEC->getIdTypeImpot()
                            ));
                            if($typeImpot)
                            {
                                $vec->setTypeImpot($typeImpot->getTitre());
                            }
                            $vec->setIdEtape($synchro->getIdEtape());
                            $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
                                'id' => $synchro->getIdEtape()
                            ));
                            if($typeEtape)
                            {
                                $vec->setEtapeCourante($typeEtape->getNom());
                            }
                                $vec->setMontantPrincipal($synchro->getMontantPrincipal());
                                $vec->setMontantAmende($synchro->getMontantAmende());
                                $vec->setMontantTotal($synchro->getMontantTotal());
                                $em->persist($vec);
                                $em->flush();
                        }
                    }
                }
            }
        }

        return $this->redirectToRoute('list_verificationencours');

    }

    public function listVerificationsEnCoursAction(Request $request)
    {   
        // Lister VerificationsEnCours
        $em = $this->getDoctrine()->getManager();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossierQuery = $em->createQueryBuilder()
            ->select('nd')    
            ->from(VerificationsEnCours::class, 'nd')
            ->orderBy('nd.id', 'DESC');

        if ($nifFilter) {
            $dossierQuery
                ->andWhere('nd.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:listVerificationsEnCours.html.twig', array(
            "verificationsEnCours" => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function updateDossiersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $evMax = $em->createQueryBuilder()
        ->select('MAX(ev.id)')    
        ->from(DossiersSQVF::class, 'ev')
        ->orderBy('ev.id', 'ASC')
        ->getQuery()
        ->getSingleScalarResult();

        $idLast = $em->getRepository(DossiersSQVF::class)->findOneBy(array(
            'id' => intval($evMax),
        ));

        $dossiersId = $sqvf_em->createQueryBuilder()
            ->select('di')    
            ->from(sqvf_dossiers::class, 'di')
            ->where('di.id = :did')
            ->setParameter('did', $idLast->getIdDossier())
            ->orderBy('di.id', 'ASC')
            ->getQuery()
            ->getResult();

        $newDossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            ->andWhere('nd.id > :ni')
            ->setParameter('ni', $dossiersId)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($newDossiers as $dossier) {
            $cb = new DossiersSQVF;
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

        $dossiers = $em->getRepository(DossiersSQVF::class)->findAll();
        foreach ($dossiers as $dossier) {
            $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy([
                'permalink' => $dossier->getEtapeCourante()
            ]);
            $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                array('idDossier' => $dossier->getIdDossier(), 'idEtape' => $typeEtape->getId()),
                array('idEtape' => 'ASC','idDossier' => 'ASC','idDossierAnneeControle' => 'ASC')
            );
            $montantPrincipal = 0;
            $montantAmende = 0;
            $montantTotal = 0;
            if($synchros)
            {
                foreach ($synchros as $synchro)
                {
                        $montantPrincipal += $synchro->getMontantPrincipal();
                        $montantAmende += $synchro->getMontantAmende();
                        $montantTotal += $synchro->getMontantTotal();
                }
            }
            $dossier->setMontantPrincipal($montantPrincipal);
            $dossier->setMontantAmende($montantAmende);
            $dossier->setMontantTotal($montantTotal);
            $em->flush();
            $montantPrincipal = 0;
            $montantAmende = 0;
            $montantTotal = 0;
        }

        return $this->redirectToRoute('dossiersList');
    }

    public function showDossiersAction(DossiersSQVF $dossier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $exercicesVerifies = $em->getRepository(ExercicesVerifies::class)->findBy(
            ['nif' => $dossier->getNif()],
            // ['idDossier' => $dossier->getIdDossier()],
            ['id' => 'DESC']
        );
        // dump($dossiersEVs);  // ok
        // die();
        // dump($exercicesVerifies);
        // die();
        // foreach ($dossiersEVs as $dossierEV)
        // {
        //     $exercicesVerifies = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
        //         array('idDossier' => $dossier->getIdDossier(),'idDossierAnneeControle' => $dossierEV->getId(), 'idEtape' => 6),
        //         array('idDossierAnneeControle' => 'ASC')
        //     );
        //     if($exercicesVerifies)
        //     {
        //         foreach ($exercicesVerifies as $exercicesVerifie)
        //         {
                    // if ($exercicesVerifie->getIdEtape() == 6)   // critère déjà formulé
                    // {
                        // $ev = new ExercicesVerifies;
                        // $exercicesVerifie->setIdDossier($dossierEV->getIdDossier());
                        // $exercicesVerifie->setIdDossierAnneeControle($dossierEV->getId());
                        // $exercicesVerifie->setUniqid($dossier->getUniqid());
                        // $exercicesVerifie->setNif($dossier->getNif());
                        // $exercicesVerifie->setAnneeControle($dossierEV->getAnneeControle());
                        // $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                        //     'id' => $dossierEV->getIdDossier()
                        // ));
                        // if($typecontrole)
                        // {
                        //     $exercicesVerifie->setTypeControle($typecontrole->getTypeControle());
                        // }
                        // $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                        //     'idDossier' => $dossier->getIdDossier()
                        // ));
                        // if($sdes)
                        // {
                        //     $exercicesVerifie->setDateNotificationDefinitive($sdes->getDateNotificationDefinitive());
                        // }
                        // $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                        //     'id' => $dossierEV->getIdTypeImpot()
                        // ));
                        // if($typeImpot)
                        // {
                        //     $exercicesVerifie->setTypeImpot($typeImpot->getTitre());
                        // }
                        // $exercicesVerifie->setIdEtape($exercicesVerifie->getIdEtape());
                        // $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
                        //     'id' => $exercicesVerifie->getIdEtape()
                        // ));
                        // if($typeEtape)
                        // {
                        //     $exercicesVerifie->setEtapeCourante($typeEtape->getNom());
                        // }
                        // $exercicesVerifie->setMontantPrincipal($exercicesVerifie->getMontantPrincipal());
                        // $exercicesVerifie->setMontantAmende($exercicesVerifie->getMontantAmende());
                        // $exercicesVerifie->setMontantTotal($exercicesVerifie->getMontantTotal());
                        // $em->persist($ev);
                        // $em->flush();
                        // dump($exercicesVerifie);
                        // die();
                    // }
                // }
            // }
            // dump($_SESSION["exVer"]);
            // die();
            $verificationsEnCours = $em->getRepository(VerificationsEnCours::class)->findBy(
                    ['nif' => $dossier->getNif()],
                    ['id' => 'DESC']
                );
        
        //     $verificationsEnCours = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
        //         array('idDossier' => $dossier->getIdDossier(),'idDossierAnneeControle' => $dossierEV->getId(), 'idEtape' => 2),
        //         array('idDossierAnneeControle' => 'ASC')
        //     );
        //     if($verificationsEnCours)
        //     {
        //         foreach ($verificationsEnCours as $verificationsEnCour)
        //         {
        //             // if ($verificationsEnCour->getIdEtape() == 2)
        //             // {
        //                 // $ev = new ExercicesVerifies;
        //                 $verificationsEnCour->setIdDossier($dossierEV->getIdDossier());
        //                 $verificationsEnCour->setIdDossierAnneeControle($dossierEV->getId());
        //                 $verificationsEnCour->setUniqid($dossier->getUniqid());
        //                 $verificationsEnCour->setNif($dossier->getNif());
        //                 $verificationsEnCour->setAnneeControle($dossierEV->getAnneeControle());
                        
        //                 $agentVerifId = $sqvf_em->getRepository(sqvf_dossiers_agent_verificateur::class)->findOneBy(array(
        //                     'idDossier' => $dossierEV->getIdDossier()
        //                 ));
        //                 if($agentVerifId)
        //                 {
        //                     $agentVerif = $sqvf_em->getRepository(sqvf_agents_verificateurs::class)->findOneBy(array(
        //                         'id' => $agentVerifId->getIdAgentVerificateur()
        //                     ));
        //                     if($agentVerif)
        //                     {
        //                     $verificationsEnCour->setVerificateur($agentVerif->getNom());
        //                     }
        //                 }

        //                 $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
        //                     'id' => $dossierEV->getIdDossier()
        //                 ));
        //                 if($typecontrole)
        //                 {
        //                     $verificationsEnCour->setTypeControle($typecontrole->getTypeControle());
        //                 }
        //                 $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
        //                     'idDossier' => $dossier->getIdDossier()
        //                 ));
        //                 if($sdes)
        //                 {
        //                     $verificationsEnCour->setNumeroAvisDeVerification($sdes->getAvisDeVerification());
        //                     $verificationsEnCour->setDateAvisDeVerification($sdes->getDateAvisDeVerification());
        //                     $verificationsEnCour->setNumeroNotificationPrimitive($sdes->getNotificationPrimitive());
        //                     $verificationsEnCour->setDateNotificationPrimitive($sdes->getDateNotificationPrimitive());
        //                 }
        //                 $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
        //                     'id' => $dossierEV->getIdTypeImpot()
        //                 ));
        //                 if($typeImpot)
        //                 {
        //                     $verificationsEnCour->setTypeImpot($typeImpot->getTitre());
        //                 }
        //                 $verificationsEnCour->setIdEtape($exercicesVerifie->getIdEtape());
        //                 $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
        //                     'id' => $verificationsEnCour->getIdEtape()
        //                 ));
        //                 if($typeEtape)
        //                 {
        //                     $verificationsEnCour->setEtapeCourante($typeEtape->getNom());
        //                 }
        //                 $verificationsEnCour->setMontantPrincipal($verificationsEnCour->getMontantPrincipal());
        //                 $verificationsEnCour->setMontantAmende($verificationsEnCour->getMontantAmende());
        //                 $verificationsEnCour->setMontantTotal($verificationsEnCour->getMontantTotal());
        //                 // $em->persist($ev);
        //                 // $em->flush();
        //                 // dump($verificationsEnCour);
        //                 // die();
        //             // }
        //         }
        //     }
        // // }
        // dump($exercicesVerifies, exVer, $verificationsEnCours);
        // die();


        return $this->render('SQVFBundle:Dossiers:show.html.twig', [
            'dossier' => $dossier,
            'exercicesVerifies' => $exercicesVerifies,
            'verificationsEnCours' => $verificationsEnCours,
        ]);

    }

    public function sqvfDossiersExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'SQVF - Liste des dossiers au ' . $createdAt . '.xlsx';
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
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Référence ')
            ->setCellValue('B1', 'NIF ')
            ->setCellValue('C1', 'Raison sociale ')
            ->setCellValue('D1', 'Centre fiscal ')
            ->setCellValue('E1', 'Type de contrôle')
            ->setCellValue('F1', 'Etape courante ')
            ->setCellValue('G1', 'Date création ')
            ->setCellValue('H1', 'Montant principal ')
            ->setCellValue('I1', 'Montant amende ')
            ->setCellValue('J1', 'Montant total ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(11);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth(16);
        $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setWidth(16);
        $phpExcelObject->getActiveSheet()->getColumnDimension('J')->setWidth(16);
        foreach ($dossierQuery as $dossier) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier->getUniqid())
                ->setCellValue('B' . $count, $dossier->getNif())
                ->setCellValue('C' . $count, $dossier->getRs())
                ->setCellValue('D' . $count, $dossier->getCentreFiscal())
                ->setCellValue('E' . $count, $dossier->getTypeControle())
                ->setCellValue('F' . $count, $dossier->getEtapeCourante())
                ->setCellValue('G' . $count, $dossier->getDateCreation()->format('d-m-Y'))
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
            $filename
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);
        return $response;
    }

    public function razDossiersSQVFAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossiers = $em->getRepository(DossiersSQVF::class)->findAll();
        
        foreach ($dossiers as $dossier) {
            
            $MontantPrincipal = 0;
            $MontantAmende = 0;
            $MontantTotal = 0;

            $dossier->setMontantPrincipal($MontantPrincipal);
            $dossier->setMontantAmende($MontantAmende);
            $dossier->setMontantTotal($MontantTotal);
            // $em->persist($dossier);
            $em->flush();
        }
    
        return $this->redirectToRoute('dossiers');
    
    }

    public function dossiersAction(Request $request)
    {
        // List DossiersSQVF simplement
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $dossiers = $em->getRepository(DossiersSQVF::class)->findAll();
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossiers = $em->createQueryBuilder()
            ->select('nd')    
            ->from(DossiersSQVF::class, 'nd')
            ->orderBy('nd.id', 'DESC');

        if ($nifFilter) {
            $dossiers
                ->andWhere('nd.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

        // if ($rsFilter) {
        //     $dossiers
        //         ->andWhere('nd.rs LIKE :rsParam')
        //         ->setParameter('rsParam', '%' . $rsFilter . '%');
        // }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossiers,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:list.html.twig', array(
            "dossiers" => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function dossiersListAction(Request $request)
    {
        // List DossiersSQVF simplement
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $dossiers = $em->getRepository(DossiersSQVF::class)->findAll();
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $dossiers = $em->createQueryBuilder()
            ->select('nd')    
            ->from(DossiersSQVF::class, 'nd')
            ->orderBy('nd.id', 'DESC');

        if ($nifFilter) {
            $dossiers
                ->andWhere('nd.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $dossiers,
            $request->query->getInt('page', 1),
            20
        );
                
        return $this->render('SQVFBundle:Dossiers:list.html.twig', array(
            "dossiers" => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function updateExercicesVerifiesAction(Request $request)
    {
        // mettre à jour ExercicesVerifies avec sqvf_dossiers comme table-primaire
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $evMax = $em->createQueryBuilder()
            ->select('MAX(ev.id)')    
            ->from(ExercicesVerifies::class, 'ev')
            ->orderBy('ev.id', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();

        $idLast = $em->getRepository(ExercicesVerifies::class)->findOneBy(array(
            'id' => intval($evMax),
        ));

        $dossiersId = $sqvf_em->createQueryBuilder()
            ->select('di')    
            ->from(sqvf_dossiers::class, 'di')
            ->where('di.id = :did')
            ->setParameter('did', $idLast->getIdDossier())
            ->orderBy('di.id', 'ASC')
            ->getQuery()
            ->getResult();

        $dossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            ->andWhere('nd.id > :ni')
            ->setParameter('ni', $dossiersId)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($dossiers as $dossier) {
            $dossiersEVs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                'idDossier' => $dossier->getId(),
            ));
            foreach ($dossiersEVs as $dossierEV)
            {
                $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                    array('idDossier' => $dossier->getId(),'idDossierAnneeControle' => $dossierEV->getId()),
                    array('idEtape' => 'ASC','idDossier' => 'ASC','idDossierAnneeControle' => 'ASC')
                );
                if($synchros)
                {
                    foreach ($synchros as $synchro)
                    {
                        if ($synchro->getIdEtape() == 6)
                        {
                            $ev = new ExercicesVerifies;
                            $ev->setIdDossier($dossierEV->getIdDossier());
                            $ev->setIdDossierAnneeControle($dossierEV->getId());
                            $ev->setUniqid($dossier->getUniqid());
                            $ev->setNif($dossier->getNif());
                            $ev->setAnneeControle($dossierEV->getAnneeControle());
                            $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                'id' => $dossierEV->getIdDossier()
                            ));
                            if($typecontrole)
                            {
                                $ev->setTypeControle($typecontrole->getTypeControle());
                            }
                            $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                'idDossier' => $dossier->getId()
                            ));
                            if($sdes)
                            {
                                $ev->setDateNotificationDefinitive($sdes->getDateNotificationDefinitive());
                            }
                            $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                'id' => $dossierEV->getIdTypeImpot()
                            ));
                            if($typeImpot)
                            {
                                $ev->setTypeImpot($typeImpot->getTitre());
                            }
                            $ev->setIdEtape($synchro->getIdEtape());
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
                            $em->persist($ev);
                            $em->flush();
                        }
                    }
                }
            }
        }

        return $this->redirectToRoute('update_verificationencours');

    }

    public function updateVerificationsEnCoursAction(Request $request)
    {
        // mettre à jour VerificationsEnCours avec sqvf_dossiers comme table-primaire
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        // $raz2 = $em->getRepository(ExercicesVerifies::class)->vecTruncate();
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $evMax = $em->createQueryBuilder()
            ->select('MAX(ev.id)')    
            ->from(VerificationsEnCours::class, 'ev')
            ->orderBy('ev.id', 'ASC')
            ->getQuery()
            ->getSingleScalarResult();

        $idLast = $em->getRepository(VerificationsEnCours::class)->findOneBy(array(
            'id' => intval($evMax),
        ));

        $dossiersId = $sqvf_em->createQueryBuilder()
            ->select('di')    
            ->from(sqvf_dossiers::class, 'di')
            ->where('di.id = :did')
            ->setParameter('did', $idLast->getIdDossier())
            ->orderBy('di.id', 'ASC')
            ->getQuery()
            ->getResult();

        $dossiers = $sqvf_em->createQueryBuilder()
            ->select('nd')    
            ->from(sqvf_dossiers::class, 'nd')
            ->where('nd.idCentreFiscal = :idCF')
            ->setParameter('idCF', 51)
            ->andWhere('nd.id > :ni')
            ->setParameter('ni', $dossiersId)
            ->orderBy('nd.id', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($dossiers as $dossier) {
            $dossiersEVs = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findBy(array(
                'idDossier' => $dossier->getId(),
            ));
            foreach ($dossiersEVs as $dossierEV)
            {
                $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(
                    array('idDossier' => $dossier->getId(),'idDossierAnneeControle' => $dossierEV->getId()),
                    array('idEtape' => 'ASC','idDossier' => 'ASC','idDossierAnneeControle' => 'ASC')
                );
                if($synchros)
                {
                    foreach ($synchros as $synchro)
                    {
                        if ($synchro->getIdEtape() == 6)
                        {
                            $ev = new VerificationsEnCours;
                            $ev->setIdDossier($dossierEV->getIdDossier());
                            $ev->setIdDossierAnneeControle($dossierEV->getId());
                            $ev->setUniqid($dossier->getUniqid());
                            $ev->setNif($dossier->getNif());
                            $ev->setAnneeControle($dossierEV->getAnneeControle());
                            $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                'id' => $dossierEV->getIdDossier()
                            ));
                            if($typecontrole)
                            {
                                $ev->setTypeControle($typecontrole->getTypeControle());
                            }
                            $sdes = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                'idDossier' => $dossier->getId()
                            ));
                            if($sdes)
                            {
                                $ev->setDateNotificationDefinitive($sdes->getDateNotificationDefinitive());
                            }
                            $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                'id' => $dossierEV->getIdTypeImpot()
                            ));
                            if($typeImpot)
                            {
                                $ev->setTypeImpot($typeImpot->getTitre());
                            }
                            $ev->setIdEtape($synchro->getIdEtape());
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
                            $em->persist($ev);
                            $em->flush();
                        }
                    }
                }
            }
        }

        return $this->redirectToRoute('updateDossiers');

    }

}