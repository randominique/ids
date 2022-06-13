<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

use DBundle\Entity\Entrant;
use DBundle\Entity\Sortant;
use DBundle\Entity\User;
use DBundle\Entity\Assujettissement;
use DBundle\Entity\CourierEntrant;
use DBundle\Entity\CourierSortant;
use DBundle\Entity\RelanceSetting;
use DBundle\Entity\Communication;
use DBundle\Entity\Contribuables;
use DBundle\Entity\TypeDeDossier;
use DBundle\Entity\TypeDossier;
use DBundle\Form\AssujettissementType;
use DBundle\Form\TypeDossierType;
use NIFBundle\Entity\Clients as NIFOnlineClients;
// use SIGTASBundle\Entity\Clients as TaxPayer;
use SIGTASBundle\Entity\PAIEMENT;
use SIGTASBundle\Entity\DocCourrier;
use SIGTASBundle\Entity\CarteFiscale;
use SIGTASBundle\Entity\TaxationOffice;
use SIGTASBundle\Entity\Document;
use SIGTASBundle\Entity\Assessment;
use SIGTASBundle\Entity\TaxPayer;
use SIGTASBundle\Entity\Enterprise;
use SIGTASBundle\Entity\Paiment;
use SIGTASBundle\Entity\CarteStat;
use SIGTASBundle\Entity\SECTOR_ACTIVITY;
use SIGTASBundle\Entity\TaxType;
use SIGTASBundle\Entity\FiscalRegime;

use Dompdf\Dompdf;
use Dompdf\Options;

use Doctrine\ORM\Query;
use SIGTASBundle\Entity\Titre_perception;

class DefaultController extends Controller
{
    private $db_nif = 'nifonline';
    private $db_sigtas = 'sigtas';

    public function indexAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $nifReq = $request->query->get('nif');
            $rsReq = $request->query->get('rs');
            $sectActReq = $request->query->get('sectAct');
        }

        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nifFilter = $request->query->get('nif');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $doctypeno = 1;
        $docstateno = 2;
        $qb = $sigtas_em->createQueryBuilder();
        $qb->select('count(account.nif)');
        $qb->from(TaxPayer::class, 'account');
        $qb->where('account.inactifDate IS NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        $qbd = $em->createQueryBuilder('c');
        $qbd->select('c.taxPayerNo');
        $qbd->from(Contribuables::class, 'c');
        $qbd->where('c.inactifDate IS NULL');
        $res = $qbd->getQuery()->getArrayResult();

        $Allnif = array();
        foreach ($res as  $res) {
            foreach ($res as  $res) {
                $enterprise = $sigtas_em->getRepository(Enterprise::class)->findBytaxPayerNo($res);
                array_push($Allnif, $enterprise);
            }
        }

        // Courriers entrants
        $qb = $em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from(Entrant::class, 'e');
        $qb
            ->andWhere('e.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr);
        $countCE = $qb->getQuery()->getSingleScalarResult();

        // Courriers sortants
        $qb = $em->createQueryBuilder();
        $qb->select('count(s.id)');
        $qb->from(Sortant::class, 's');
        $qb
            ->andWhere('s.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr);
        $countCS = $qb->getQuery()->getSingleScalarResult();

        // Courrier de type R (R ne signifie pas Relance, mais recouvrement ???)
        // $qb = $sigtas_em->createQueryBuilder();
        // $qb->select('count(r.docNo)');
        // $qb->from(DocCourrier::class, 'r');
        // $qb->where('r.typeCourrier = :relance');
        // $qb->setParameter('relance', "R");
        // $countCourrierRelance = $qb->getQuery()->getSingleScalarResult();

        // déclarations faites (dépôt)
        // $Documents = $sigtas_em->createQueryBuilder()
        //     ->select('count(d.receivedDate)')
        //     ->from(Document::class, 'd')
        //     ->where('d.receivedDate IS NOT null')
        //     ->andWhere('d.docTypeNo = :dtp')
        //     ->setparameter('dtp', $doctypeno)
        //     ->andWhere('d.docStateNo = :docstateno')
        //     ->setparameter('dts', $docstateno)
        //     ->orderBy('d.receivedDate', 'DESC');
        // $depot = $Documents->getQuery()->getSingleScalarResult();
        // ;

        // Taxations d'office réalisées
        // $Assessments = $sigtas_em->createQueryBuilder()
        //     ->select('count(o.toReceivedDate)')
        //     ->from(Assessment::class, 'o')
        //     ->where('o.toReceivedDate IS NOT null')
        //     ->orderBy('o.toReceivedDate', 'DESC');
        // $to_etablis = $Assessments->getQuery()->getSingleScalarResult();;

        //Taxations d'office à etablir
        // $Assessments = $sigtas_em->createQueryBuilder()
        //     ->select('count(t.toReceivedDate)')
        //     ->from(Assessment::class, 't')
        //     ->where('t.toReceivedDate IS null')
        //     ->orderBy('t.toReceivedDate', 'DESC');
        // $to_a_etablir = $Assessments->getQuery()->getSingleScalarResult();;

        // $recentlyUser = $em->getRepository(User::class)->findBy([],['id' => 'desc'],50);

        $countsector = [];
        $Listsector = [];
        //$test[0]=44;
        $array = array();
        //sCountsector 
        $sectorAct = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $listsector = array();
        $listSectNo = array();
        $countlist = array();
        foreach ($sectorAct as $sectorAct) {
            array_push($listsector, $sectorAct->getId());
        }

        foreach ($Allnif as $Allnif) {
            foreach ($Allnif as $Allnif) {
                array_push($listSectNo, $Allnif->getSectorActNo());
            }
        }
        for ($i = 0; $i < count($listsector); $i++) {
            $k = 0;
            for ($j = 0; $j < count($listSectNo); $j++) {
                if ($listsector[$i] === $listSectNo[$j]) {
                    $k = $k + 1;
                }
            }
            array_push($countsector, $k);
        }

        $listcolor = [];
        $sectorAct = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        foreach ($sectorAct as $sectorAct) {
            array_push($Listsector, $sectorAct->getSectorActDesc());

            $random_color_part1 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $random_color_part2 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $random_color_part3 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $color = "#" . $random_color_part1 . $random_color_part2 . $random_color_part3;
            array_push($listcolor, $color);
        }

        return $this->render('DBundle:Default:index.html.twig', [
            'clients_nif' => $count,
            // 'totalCourrier' => $countCourrier,
            'courrier_sortant' => $countCS,
            'courrierEntrant' => $countCE,
            // 'courrierRelance' => $countCourrierRelance,
            // 'recentlyUser' => $recentlyUser,
            // 'to_etablis' => $to_etablis,
            // 'to_a_etablir' => $to_a_etablir,
            // 'depot' => $depot,
            'countsector' => json_encode($countsector),
            'Listsector' => json_encode($Listsector),
            'listcolor' => json_encode($listcolor),
            // 'nb_ce_traite' => $nb_CE_Traite,
            // 'nb_ce_transmis' => $nb_CE_Transmis,
            // 'nb_ce_SAI' => $nb_CE_SAI,
        ]);
    }

    public function listeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();

        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $chefs = $em->getRepository(Service::class)->findAll();
        $responsableQuery1 = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $responsableQuery = [];
        foreach($chefs as $chef){
            $thisChef = $chef->getChef();
                array_push($responsableQuery,$thisChef);
        }
        foreach($responsableQuery1 as $responsableQuery2){
            if(!in_array($responsableQuery2,$responsableQuery)){
                array_push($responsableQuery,$responsableQuery2);
            }
        }
        
        $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $userServiceId = $user->getService()->getId();
        $isSystemUser = ($user->getId() == 89) ? true : false;
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
            ->addOrderBy('e.createdAt','DESC')
            ->addOrderBy('e.numeroCourrier', 'DESC')
            ->distinct(true)
            ->getQuery();

        if ($isChefSAI || $isChefDeDirection || $isSystemUser) 
        {            
            $entrantQuery = $em->getRepository(Service::class)
                                ->find($user->getService()->getId())
                                ->getEntrant();
            $entrantQueryOk =[];
            foreach( $entrantQuery as  $entrant){                
                if ($nifFilter) {
                    if($nifFilter == $entrant->getNif())
                    {
                        array_push($entrantQueryOk,$entrant);
                    }
                }else{
                    array_push($entrantQueryOk,$entrant);
                }
            }

            $entrantQueryc =[];
            $courrier = "";
            foreach( $entrantQueryOk as  $entrant){                
                $courrier = $entrant->getCourrierId();
                array_push($entrantQueryc,$courrier);
            }

            // Méthode N° 01
            if ($nifFilter && $status) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif ($nifFilter && !$status) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'nif'=> $nifFilter),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif (!$nifFilter && $status) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }else {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc),
                    array('numeroCourrier' => 'DESC')
                );
            }
            // le filtre Date_du & Date_au ne fonctionne pas
            if ($date_du && $date_au) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'createdAt' => $date_du),
                    array('numeroCourrier' => 'DESC')
                );
            }else
            {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'status'=> "Transmis"),
                    array('numeroCourrier' => 'DESC')
                );
            }


            // Méthode N° 02 ne fonctionne pas du tout
            // $entrantQueryOkey = $em->getRepository(Entrant::class)->createQueryBuilder('ce')
            //     // ->Where('ce.courrierId LIKE :entrantQueryC')
            //     // ->setParameter('entrantQueryC', $entrantQueryc)
            //     ->addOrderBy('ce.createdAt','DESC')
            //     ->addOrderBy('ce.numeroCourrier', 'DESC')
            //     ->distinct(true);

            // if ($nifFilter) {
            //     $entrantQueryOkey
            //         ->andWhere('ce.nif LIKE :nif')
            //         ->setParameter('nif', '%' . $nifFilter . '%');
            // }
            // if ($rsFilter) {
            //     $entrantQueryOkey
            //         ->andWhere('ce.raisonSocial LIKE :rs')
            //         ->setParameter('rs', '%' . $rsFilter . '%');
            // }
            // if ($status) {
            //     $entrantQueryOkey
            //         ->andWhere('ce.status LIKE :status')
            //         ->setParameter('status', $status);
            // }
            //     $entrantQueryOkey
            //         ->andWhere('ce.createdAt BETWEEN :date_du AND :date_au')
            //         ->setParameter('date_du', $date_du)
            //         ->setParameter('date_au', $date_au);
            // }
            // if ($gestionnaireId) {
            //     $entrantQueryOkey
            //         ->andWhere('ce.gestionnaire  = :gestionnaire')
            //         ->setParameter('gestionnaire', $gestionnaireId);
            // }
    
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQueryOkey,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $entrants,
                'gestionnaire' => $gestionnaire,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'sectorActs' => $sectorActs,
                'attributions' => $attributions,
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isMembreSAI' => $isMembreSAI,
                'usersService' => $responsableQuery,
                'isMembreDirection' => $isMembreDirection,
                'isChefDeDirection' => $isChefDeDirection,
                'userServiceId' => $userServiceId,
                'userId' => $userId,
                'isSystemUser' => $isSystemUser,
                'isInspecteur' => $isInspecteur,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'dispatcher' => false,
                'listAssigne' => false,
                'imprimer' => 'listAction'
            ));
            
            if ($date_du && $date_au) {
                $documentsQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au);
            }
            if ($sai->getService()->getChef()->getId() == $user->getId() or $user->getService()->getId() == '1') {
                if ($type) {
                    switch ($type) {
                        case 'a_traiter':
                            $documentsQuery
                                ->andWhere('e.service = :service')
                                ->setParameter('service', $sai->getService()->getChef()->getId());
                            break;

                        case 'a_dispatcher':
                            $documentsQuery
                                ->andWhere('e.status = :status')
                                ->setParameter('status', null);
                            break;/*
                             case 'pour_info':
                                 $documentsQuery
                                 ->join('e.pourInfo', 'i')
                                 ->andWhere('i.service = :pourinfo')
                                 ->setParameter('pourinfo', $user->getService()->getId());
                             break;*/
                    }
                } else {
                    /*
                         $query
                         ->andWhere('e.status = :status')
                         ->setParameter('status', 'Nouveau');
                         */
                }
                if ($serviceId) {
                    $documentsQuery
                        ->andWhere('e.service = :serviceId')
                        ->setParameter('serviceId', $serviceId);
                }
            }

            else if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId() /*or $entrantDuService  */) {
                if ($type) {
                    switch ($type) {
                        case 'a_traiter':
                            $documentsQuery
                                ->andWhere('e.service = :service')
                                ->setParameter('service', $user->getService()->getId());
                            break;

                        case 'pour_info':
                            $documentsQuery
                                ->distinct()
                                ->join('e.pourInfo', 'i')
                                ->andWhere('i.service = :pourinfo')
                                ->setParameter('pourinfo', $user->getService()->getId());
                            break;
                    }
                } else {
                    $documentsQuery
                        ->andWhere('e.service = :service')
                        ->setParameter('service', $user->getService()->getId());
                }
            }

            $courrier_assigner = $em->getRepository(Entrant::class)->createQueryBuilder('ca')
                ->where('ca.gestionnaire IS null ');
            if (!$courrier_assigner) {

                $documentsQuery
                    ->andWhere('e.gestionnaire  = :gestionnaire')
                    ->setParameter('gestionnaire', $user->getId());
            }

            switch ($priority) {
                case 'Très Urgent':
                    $documentsQuery
                        ->andWhere('e.priority = :TRESURGENT')
                        ->setParameter('TRESURGENT', 'Très Urgent');
                    break;

                case 'Urgent':
                    $documentsQuery
                        ->andWhere('e.priority = :URGENT')
                        ->setParameter('URGENT', 'Urgent');
                    break;

                case 'Normal':
                    $documentsQuery
                        ->andWhere('e.priority = :NORMAL')
                        ->setParameter('NORMAL', 'Normal');
                    break;
            }

            if (!$type) {
                switch ($status) {
                    case 'Transmis':
                        $documentsQuery
                            ->andWhere('e.status = :Transmis');
                        $documentsQuery->setParameter('Transmis', 'Transmis');
                        break;

                    case 'Assigné':
                        $documentsQuery
                            ->andWhere('e.status = :Assigne');
                        $documentsQuery->setParameter('Assigne', 'Assigné');
                        break;

                    case 'en_cours':
                        $documentsQuery
                            ->andWhere('e.status = :en_cours');
                        $documentsQuery->setParameter('en_cours', '%Assigné%');
                        break;

                    case 'Traité':
                        $documentsQuery
                            ->andWhere('e.status LIKE :traite')
                            ->setParameter('traite', '%Traité%');
                        break;

                    case 'Clôturé':
                        $documentsQuery
                            ->andWhere('e.status LIKE :closed')
                            ->setParameter('closed', '%Ferme%');
                        break;
                }
            }

            if ($request->query->get('date_du') && $request->query->get('date_au')) {
                $documentsQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $request->query->get('date_du'))
                    ->setParameter('date_au', $request->query->get('date_au'));
            }

            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $entrants,
                'gestionnaire' => $gestionnaire,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'sectorActs' => $sectorActs,
                'attributions' => $attributions,
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isMembreSAI' => $isMembreSAI,
                'usersService' => $responsableQuery,
                'isMembreDirection' => $isMembreDirection,
                'isChefDeDirection' => $isChefDeDirection,
                'userServiceId' => $userServiceId,
                'userId' => $userId,
                'isSystemUser' => $isSystemUser,
                'isInspecteur' => $isInspecteur,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'listAssigne' => false,
                'imprimer' => 'listAction'

            ));

            if ($isChefDeService) {
                if ($gestionnaireId) {
                    $documentsQuery
                        ->andWhere('e.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaireId);
                }

                $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
                    ->where('e.service = :service')
                    ->setParameter('service', $user->getService())
                    ->distinct('e.numero')
                    ->getQuery();

                $paginator  = $this->get('knp_paginator');
                $entrants = $paginator->paginate(
                    $entrantQuery,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                return $this->render('DBundle:Entrant:list.html.twig', array(
                    'courriers' => $entrants,
                    'gestionnaire' => $gestionnaire,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'sectorActs' => $sectorActs,
                    'attributions' => $attributions,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'usersService' => $responsableQuery,
                    'isMembreDirection' => $isMembreDirection,
                    'isChefDeDirection' => $isChefDeDirection,
                    'userServiceId' => $userServiceId,
                    'userId' => $userId,
                    'isSystemUser' => $isSystemUser,
                    'isInspecteur' => $isInspecteur,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false,
                    'imprimer' => 'listAction'

                ));
            }

            $courrierEntrantPagination = $this->refreshEntrant($request);
            
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $courrierEntrantPagination,
                /*
                'form_search' => $form_search->createView(),
                'form' => $form->createView(),
                'yearCourr' => $yearCourr,
                */
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'attributions' => $attributions,
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isMembreSAI' => $isMembreSAI,
                'isMembreDirection' => $isMembreDirection,
                'isChefDeDirection' => $isChefDeDirection,
                'userServiceId' => $userServiceId,
                'userId' => $userId,
                'isSystemUser' => $isSystemUser,
                'isInspecteur' => $isInspecteur,
                'sectorActs' => $sectorActs,
                'usersService' => $responsableQuery,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'listAssigne' => false,
                'imprimer' => 'listAction'
            ));
        }
        else {
            $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e');
            $documentsQuery
                ->andWhere('e.service = :service')
                ->setParameter('service', $user->getService())
                ->distinct('e.numero');
            // dump($documentsQuery);
            // die();
            if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) 
            {
                if (!$isInspecteur)
                {
                    if ($type) {
                        switch ($type) {
                            case 'a_traiter':
                                $documentsQuery
                                    ->andWhere('e.service = :service')
                                    ->setParameter('service', $user->getService()->getId());
                                break;

                            case 'pour_info':
                                $documentsQuery
                                    ->join('e.pourInfo', 'i')
                                    ->andWhere('i.service = :pourinfo')
                                    ->setParameter('pourinfo', $user->getService()->getId());
                                break;
                        }
                    } else {
                        $documentsQuery
                            ->andWhere('e.service = :service')
                            ->setParameter('service', $user->getService()->getId());
                        }
                }
                $documentsQuery
                ->orderBy('e.numero', 'DESC');
            } 
            else {
                return $this->redirectToRoute('list_entrant_assigne');
            }
            $courrier_assigner = $em->getRepository(Entrant::class)->createQueryBuilder('ca')
                ->where('ca.gestionnaire IS null ');
            if (!$courrier_assigner) {
                $documentsQuery
                    ->andWhere('e.gestionnaire  = :gestionnaire')
                    ->setParameter('gestionnaire', $user->getId());
            }

            switch ($priority) {
                case 'Très Urgent':
                    $documentsQuery
                        ->andWhere('e.priority = :TRESURGENT')
                        ->setParameter('TRESURGENT', 'Très Urgent');
                    break;

                case 'Urgent':
                    $documentsQuery
                        ->andWhere('e.priority = :URGENT')
                        ->setParameter('URGENT', 'Urgent');
                    break;

                case 'Normal':
                    $documentsQuery
                        ->andWhere('e.priority = :NORMAL')
                        ->setParameter('NORMAL', 'Normal');
                    break;
            }

            if (!$type) {
                switch ($status) {
                    case 'Transmis':
                        $documentsQuery
                            ->andWhere('e.status = :Transmis');
                        $documentsQuery->setParameter('Transmis', 'Transmis');
                        break;

                    case 'Assigné':
                        $documentsQuery
                            ->andWhere('e.status = :Assigne')
                            ->setParameter('Assigne', 'Assigné');
                        break;

                    case 'en_cours':
                        $documentsQuery
                            ->andWhere('e.status = :en_cours');
                        $documentsQuery->setParameter('en_cours', '%Assigné%');
                        break;

                    case 'Traité':
                        $documentsQuery
                            ->andWhere('e.status LIKE :traite')
                            ->setParameter('traite', '%Traité%');
                        break;

                    case 'Clôturé':
                        $documentsQuery
                            ->andWhere('e.status LIKE :closed')
                            ->setParameter('closed', '%Ferme%');
                        break;
                }
            }

            if ($request->query->get('date_du') && $request->query->get('date_au')) {
                $documentsQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $request->query->get('date_du'))
                    ->setParameter('date_au', $request->query->get('date_au'));
            }

            if ($isChefDeService) {
                if ($gestionnaireId) {
                    $documentsQuery
                        ->andWhere('e.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaireId);
                }

                $entrantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();

                $entrantQueryc =[];
                $courrier = "";
                foreach( $entrantQuery as  $entrant){                
                    $courrier = $entrant->getCourrierId();
                    array_push($entrantQueryc,$courrier);
                }

                // if ($isChefDeService) {
                    // if (!$nifFilter && !$status && !$rsFilter && !$date_du && !$date_au) {
                    //     // die('1');
                    //     $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    //         array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis' ),
                    //         array('numeroCourrier' => 'DESC')
                    //     );
                    // }
                // }
                // dump($nifFilter, $status, $rsFilter, $date_du, $date_au);
                // die();
                if ($nifFilter && $status) {
                    die('2');
                    $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                        array('courrierId'=> $entrantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif ($nifFilter && !$status) {
                    die('3');
                    $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                        array('courrierId'=> $entrantQueryc, 'nif'=> $nifFilter),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif (!$nifFilter && $status) {
                    die('4');
                    $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                        array('courrierId'=> $entrantQueryc, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }else {
                    // die('5');
                    $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                        array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis'),
                        array('numeroCourrier' => 'DESC')
                    );
                }

                // avant
                // dump($status);
                // die();
                // $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy([
                //     'status'=> $status,
                //     'courrierId'=> $entrantQueryc,
                //     'numeroCourrier' => 'DESC',
                // ]);
                    // 'numeroCourrier' => 'DESC');

                // $entrantQueryOkey = $em->getRepository(Entrant::class)->createQueryBuilder('e')
                // ->where('ca.gestionnaire IS null ');
    
                // $entrantQueryOkey = $em->createQueryBuilder()
                // // ->select('(e.createdAt)')
                // ->from(Entrant::class, 'e')
                // ->Where('e.courrierId = :courrierId')
                // ->setParameter('courrierId', $entrantQueryc)
                // ->orderBy('e.numeroCourrier', 'DESC')
                // if ($status){
                //     $entrantQueryOkey
                //     ->andWhere('e.status = :val')
                //     ->setParameter('val', $status)
                // }
                // $entrantQueryOkey
                // ->getQuery();

                    

                $paginator  = $this->get('knp_paginator');
                $entrants = $paginator->paginate(
                    $entrantQueryOkey,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                return $this->render('DBundle:Entrant:list.html.twig', array(
                    'courriers' => $entrants,
                    'gestionnaire' => $gestionnaire,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'sectorActs' => $sectorActs,
                    'attributions' => $attributions,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'usersService' => $responsableQuery,
                    'isMembreDirection' => $isMembreDirection,
                    'isChefDeDirection' => $isChefDeDirection,
                    'userServiceId' => $userServiceId,
                    'userId' => $userId,
                    'isSystemUser' => $isSystemUser,
                    'isInspecteur' => $isInspecteur,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'dispatcher' => false,
                    'listAssigne' => false,
                    'imprimer' => 'listAction'
                ));
            }

            $entrantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
            dump($entrantQuery);
            die();
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQuery,
                $request->query->getInt('page', 1),
                20
            );
            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $entrants,
                'gestionnaire' => $gestionnaire,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'sectorActs' => $sectorActs,
                'attributions' => $attributions,
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isMembreSAI' => $isMembreSAI,
                'usersService' => $responsableQuery,
                'isMembreDirection' => $isMembreDirection,
                'isChefDeDirection' => $isChefDeDirection,
                'userServiceId' => $userServiceId,
                'userId' => $userId,
                'isSystemUser' => $isSystemUser,
                'isInspecteur' => $isInspecteur,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'dispatcher' => false,
                'listAssigne' => false,
                'imprimer' => 'listAction'
            ));
        }

    }

    public function contribuablesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $secteur = $request->query->get('secteur');
        $gestionnaire = $request->query->get('gestionnaire');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findBy(array(),array('sectorActDesc'=>'ASC'));
        $secteurEnClair = Null;

        $qr = $em->getRepository(Contribuables::class)
            ->createQueryBuilder('n')
            ->where('n.inactifDate IS null');
    
        if($gestionnaire)
            {
                $gestionnaireId = $em->getRepository(user::class)->findOneBy([
                    'id' => $gestionnaire
                ]);
                $gestionnaire_nom = $gestionnaireId->getNom();
                $qr
                ->andWhere('n.gestionnaire LIKE :gestionnaire')
                ->setParameter('gestionnaire', '%'.$gestionnaire_nom.'%');
            }else {
                $gestionnaire_nom = Null;
            }
    
        if($nifFilter)
        {
            $qr
            ->andWhere('n.nif LIKE :nifParam')
            ->setParameter('nifParam', '%'.$nifFilter.'%');
        }

        if($rsFilter)
        {
            $qr
            ->andWhere('n.nif LIKE :nifParam')
            ->setParameter('nifParam', '%'.$rsFilter.'%');
        }

        if($secteur)
        {
            $sectorActNoEntry = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                'id' => $secteur
            ]);
            $secteurEnClair = $sectorActNoEntry->getSectorActDesc();
            $sectEntry = $em->getRepository(Contribuables::class)->findBy([
                'secteurActivite' => $secteurEnClair
            ]);
            $qr
                ->andWhere('n.secteurActivite LIKE :sector')
                ->setParameter('sector', $secteurEnClair);
        }

            $qr->orderBy('n.nif', 'ASC')
                ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        );       
        $responsableQuery =$em->getRepository(User::class)->findBy(array(
            'service' => $this->getUser()->getService()->getId(),
        ));
        $allUsers = $em->getRepository(User::class)->findAll();
        return $this->render('DBundle:Default:DFU\liste.html.twig', [
            'contribuables' => $pagination,
            'secteur' => $secteurEnClair,
            'nifFilter'   => $request->query->get('nif'),
            'rsFilter'   => $request->query->get('rs'),
            'sectorActs' => $sectorActs,
            'usersService' => $responsableQuery,
            'allUsers' => $allUsers,
            'gestionnaire' => $gestionnaire_nom,
        ]);
    }

    public function setContribuableAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuable = new Contribuables;
        
        $qr = $sigtas_em->getRepository(TaxPayer::class)
            ->createQueryBuilder('n')
            ->where('n.inactifDate IS null')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $qr,
                $request->query->getInt('page', 1),
                50
            );       
            
            foreach ($pagination->getItems() as $key => $contribuable) 
            {
                // Récupération des informations RS et NomCOmmmercial Phone email dans la base NIF
                $fiscalRegime = $sigtas_em->getRepository(FiscalRegime::class)->findOneBy([
                    'fiscalRegimeNo' => $contribuable->getRegimeFiscal(),
                ]);
                if($fiscalRegime)
                {
                    $contribuable->regimeFiscalName = $fiscalRegime->getFiscalRegimeDesc();
                }

                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $contribuable->getNif()),
                    array('nif' => 'ASC')
                );

                if($nifInfos!=null)
                {
                    $sigtasRs = $nifInfos->getRs();
                    $sigtasNc = $nifInfos->getNomcommercial();
                    $sigtasMail = $nifInfos->getEmail();
                    $sigtasPhone = $nifInfos->getContactPhone();
                    //$sigtasStartDate = $nifInfos->getStartDate(); // cette rubrique n'est pas dans NIFOnline

                    if($sigtasRs == null)
                    $sigtasRs = 'NA';
                    if($sigtasNc == null)
                    $sigtasNc = 'NA';
                    if($sigtasMail ==null)
                    $sigtasMail = 'NA';
                    if($sigtasPhone == null)
                    $sigtasPhone = 'NA';

                }else // s'il n'y a pas de correspondance
                {
                    $sigtasRs = 'NA';
                    $sigtasNc = 'NA';
                    $sigtasMail = 'NA';
                    $sigtasPhone = 'NA';
                }

                if($nifInfos)
                {
                    $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                        'taxPayerNo' => $contribuable->getTaxPayerNo()
                        // 'taxPayerNo' => $contribuable->getTaxPayerNo()
                    ]);
                    if($enterprise)
                    {
                        $secteurActiviteQuery = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                            'id' =>$enterprise->getSectorActNo()
                        ]);
                        if($secteurActiviteQuery)
                        {
                            $enterpriseSect1 =$secteurActiviteQuery->getSectorActDesc();
                            // $contribuable->sectorActivite = $secteurActiviteQuery->getSectorActDesc();
                        }else{
                            $enterpriseSect1 = null;
                        }
                        // $enterpriseSect2 = $enterpriseSect1;   // je ne vois pas l'utilité de cette variable $enterpriseSect2
                        $enterpriseStartDate = $enterprise->getStartDate();
                        $enterpriseEntryDate = $enterprise->getEntryDate();
                        $fisc_yr_start = $enterprise->getFiscYrStart();
                        $fisc_yr_end = $enterprise->getFiscYrEnd();
                    }else{
                        $enterpriseStartDate = null;
                    }$enterpriseSect = $enterpriseSect1;

                }else{
                    $enterpriseSect= null;
                    $enterpriseStartDate = null;
                }
                $contribuable->setSigtasRs($sigtasRs);
                $contribuable->setSigtasMail($sigtasMail);
                $contribuable->setSigtasNc($sigtasNc);
                $contribuable->setSigtasPhone($sigtasPhone);
                $contribuable->sectorActivite = $enterpriseSect;
                // $contribuable->gestionnaire = $gestionnaire;
                $contribuable->startDate = $enterpriseStartDate;
                $contribuable->entryDate = $enterpriseEntryDate;
                $contribuable->fisc_yr_start = $fisc_yr_start;
                $contribuable->fisc_yr_end = $fisc_yr_end;
                // $contribuable->secteur = $secteur;
                $em->persist($contribuable);
                $em->flush();
        
                //assignation des valeurs de RS Mail NomCommercial Phone
            }

        // $contribuable = $query->getOneOrNullResult();

        // $contribuables = new Contribuables;
        // $contribuables->Nif = $query->getNif();
        // $contribuables->setTaxpayerNo = $query->getTaxpayerNo();
        // $em->persist($contribuables);
        // $em->flush();

        // dump($contribuables);
        // die();

        return $contribuable;

    }

    public function getContribuable($contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $query = $sigtas_em->getRepository(TaxPayer::class)
            ->createQueryBuilder('s')
            ->where('s.nif = :nif')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $contribuable = $query->getOneOrNullResult();

        if ($contribuable) {
            $taxpayer = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $contribuable_nif
            ]);
            if ($taxpayer != null) {
                $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                    'taxPayerNo' => $taxpayer->taxPayerNo
                ]);
                if ($enterprise) {
                    // $taxpayer->secteurActivite = $enterprise->secteurActivite->sectorActDesc;
                } else {
                    $taxpayer->secteurActivite = '-';
                }
            }
            // else{
            //     $taxpayer->secteurActivite = '-'; 
            // }

            $contribuable->setSigtas($taxpayer);
        }

        return $contribuable;
    }

    public function findContribuableAction($contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        // dump($contribuable_nif);
        die('ok');
        $raisonsociale = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(array('nif' => $contribuable_nif));
        if ($raisonsociale) {
            $rs = $raisonsociale->getRs();
        } else {
            $rs = null;
        }
        die($rs);
        // ->createQueryBuilder('s')
        // ->where('s.nif = :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->getQuery();

        // $contribuable = $query->getOneOrNullResult();

        // if($contribuable){
        //     $taxpayer = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
        //         'nif' => $contribuable_nif
        //     ]);
        //     if($taxpayer!=null){
        //         $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
        //             'taxPayerNo' => $taxpayer->taxPayerNo
        //         ]);
        //         if($enterprise)
        //         {
        //             // $taxpayer->secteurActivite = $enterprise->secteurActivite->sectorActDesc;
        //         }else{
        //             $taxpayer->secteurActivite = '-'; 
        //         }
        //     }
        //     // else{
        //     //     $taxpayer->secteurActivite = '-'; 
        //     // }

        //     $contribuable->setSigtas($taxpayer);
        // }        

        // return $contribuable;
    }

    public function showContribuableAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuable = $this->getContribuable($contribuable_nif);

        $CarteFiscaleQuery = $sigtas_em->getRepository(CarteFiscale::class)
            ->createQueryBuilder('cf')
            ->where('cf.nif = ' . $contribuable_nif . '')
            ->andWhere('cf.carteTypeNo = :A')
            ->setParameter('A', "A")
            ->orderBy('cf.carteTypeNo', 'ASC')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        $TofficeQuery = $sigtas_em->getRepository('SIGTASBundle:TaxationOffice');
        $TOQuery = $TofficeQuery->selectAll($contribuable_nif);

        $RARQuery = $sigtas_em->getRepository('SIGTASBundle:Titre_perception');
        $RARsQuery = $RARQuery->selectAll($contribuable_nif);

        //Courrier Entrant
        $entrantQuery = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('e')
            ->where('e.nif = :nif')
            ->andWhere('e.typeCourrier = :entrant')
            ->setParameter('entrant', "E")
            ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
            ->getQuery();

        // Courrier sortant
        $sortantQuery = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('s')
            ->where('s.nif = :nif')
            ->andWhere('d.typeCourrier = :sortant')
            ->setParameter('sortant', "S")
            ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
            ->getQuery();

        $qe = $em->createQueryBuilder('e')
            ->select('count(e.numeroCourrier)')
            ->from(Entrant::class, 'e')
            ->where('e.nif = :nif')
            ->setParameter('nif', $contribuable_nif);
            $countCourrierEntrant = $qe->getQuery()->getSingleScalarResult();

        $qs = $em->createQueryBuilder('s')
            ->select('count(s.numeroCourrier)')
            ->from(Sortant::class, 's')
            ->where('s.nif = :nif')
            ->setParameter('nif', $contribuable_nif);
            $countCourrierSortant = $qs->getQuery()->getSingleScalarResult();

        // Paiement
        $pQuery = $sigtas_em->getRepository('SIGTASBundle:PAIEMENT');
        $paiementQuery = $pQuery->selectAll($contribuable_nif);

        // Historique de communication
        // $comQuery = $sigtas_em->getRepository(PAIEMENT::class)
        // ->createQueryBuilder('p')
        // ->where('p.nif = :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->orderBy('p.')
        // ->getQuery();

        // Récupérations des informations RS et NomCOmmmercial Phone email dans la base NIF

        //recupération des éléments par correspndances NIF
        $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
            array('nif' => $contribuable->getNif()),
            array('nif' => 'ASC')
        );

        if ($nifInfos != null) {
            $sigtasRs = $nifInfos->getRs();
            $sigtasNc = $nifInfos->getNomcommercial();
            $sigtasMail = $nifInfos->getEmail();
            $sigtasPhone = $nifInfos->getContactPhone();
            $sigtasNomDirigeant = $nifInfos->getNomDirigeant();
            if ($sigtasRs == null)
                $sigtasRs = '-';
            if ($sigtasNc == null)
                $sigtasNc = '-';
            if ($sigtasMail == null)
                $sigtasMail = '-';
            if ($sigtasPhone == null)
                $sigtasPhone = '-';
        } else // s'il n'y a pas de correspondance
        {
            $sigtasRs = '-';
            $sigtasNc = '-';
            $sigtasMail = '-';
            $sigtasPhone = '-';
            $sigtasNomDirigeant = '-';
        }

        if ($nifInfos) {
            $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                'taxPayerNo' => $contribuable->getTaxPayerNo()
            ]);
            if ($enterprise) {
                $secteurActiviteQuery = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                    'id' => $enterprise->getSectorActNo()
                ]);
                if ($secteurActiviteQuery) {
                    $contribuable->sectorActivite = $secteurActiviteQuery->getSectorActDesc();
                }
                $entryDateQuery = $enterprise->getEntryDate();
                $fiscYrStartQuery = $enterprise->getFiscYrStart();
                $fiscYrEndQuery = $enterprise->getFiscYrEnd();
                $startDateQuery = $enterprise->getStartDate();
            }
        }
        //assignation des valeurs de RS Mail NomCommercial Phone
        // $contribuable->secteurActivite = $
        $contribuable->startDate = $startDateQuery;
        $contribuable->entryDate = $entryDateQuery;
        $contribuable->fiscYrStart = $fiscYrStartQuery;
        $contribuable->fiscYrEnd = $fiscYrEndQuery;
        $contribuable->nomDirigeant = $sigtasNomDirigeant;
        $contribuable->setSigtasRs($sigtasRs);
        $contribuable->setSigtasMail($sigtasMail);
        $contribuable->setSigtasNc($sigtasNc);
        $contribuable->setSigtasPhone($sigtasPhone);
        //$contribuable->setSigtas($taxpayer);
        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $requete,
                    $request->query->getInt('page', 1),
                    50
                );
         */
        return $this->render('DBundle:Default:DFU\show.html.twig', [
            'contribuable' => $contribuable,
            'carteFiscales' => $CarteFiscaleQuery,
            'tos' => $TOQuery,
            'RARs' => $RARsQuery,
            'entrants' => $countCourrierEntrant,
            'sortants' => $countCourrierSortant,
            'paiements' => $paiementQuery,

            //'relances' => $relanceQuery,
        ]);
    }

    public function pdfContribuableAction(Request $request, $contribuable_nif)
    {

        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuable = $this->getContribuable($contribuable_nif);
        // Historique de carte fiscale
        // $CarteFiscaleQuery = $sigtas_em->getRepository(CarteStat::class)->findBy(
        //     array('nif' => $contribuable_nif),
        //     array('annee' => 'DESC')
        // );

        // $CarteFiscaleQuery = $sigtas_em->getRepository(CarteStat::class)
        // ->createQueryBuilder('c')
        // ->where('c.nif LIKE :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->orderBy('c.annee', 'DESC')
        // ->getQuery()
        // ->getResult();

        $CarteFiscaleQuery = $sigtas_em->getRepository(CarteFiscale::class)->findBynif($contribuable_nif);
        //->createQueryBuilder('c')
        //->where('c.nif = :nif')
        //->setParameter('nif', $contribuable_nif)
        //->getQuery();

        // Historique de carte fiscale
        $CarteFiscal = $sigtas_em->getRepository('SIGTASBundle:CarteFiscale');
        $CarteFiscaleQuery = $CarteFiscal->selectAll($contribuable_nif);
        // Hisorique de taxation d'office
        $TofficeQuery = $sigtas_em->getRepository('SIGTASBundle:TaxationOffice');
        $TOQuery = $TofficeQuery->selectAll($contribuable_nif);

        //Courrier Entrant
        $entrantQuery = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('e')
            ->where('e.nif = :nif')
            ->andWhere('e.typeCourrier = :entrant')
            ->setParameter('entrant', "E")
            ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
            ->getQuery();

        // Courrier sortant
        $sortantQuery = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('s')
            ->where('s.nif = :nif')
            ->andWhere('d.typeCourrier = :sortant')
            ->setParameter('sortant', "S")
            ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
            ->getQuery();

        $qb = $em->createQueryBuilder('e');
        $qb->select('count(e.numeroCourrier)');
        $qb->from(Entrant::class, 'e');
        $qb->where('e.nif = :nif');
        $qb->setParameter('nif', $contribuable_nif);
        $countCourrierEntrant = $qb->getQuery()->getSingleScalarResult();

        $qb = $em->createQueryBuilder('s');
        $qb->select('count(s.numeroCourrier)');
        $qb->from(Sortant::class, 's');
        $qb->where('s.nif = :nif');
        $qb->setParameter('nif', $contribuable_nif);
        $countCourrierSortant = $qb->getQuery()->getSingleScalarResult();

        // Paiement
        // $paiementQuery = $sigtas_em->getRepository(PAIEMENT::class)->findBynif($contribuable_nif);
        $pQuery = $sigtas_em->getRepository('SIGTASBundle:PAIEMENT');
        $paiementQuery = $pQuery->selectAll($contribuable_nif);
        //->createQueryBuilder('c')
        //->where('c.nif = :nif')
        //->setParameter('nif', $contribuable_nif)
        //->getQuery();

        // $paiementQuery = $sigtas_em->getRepository(PAIEMENT::class)
        // ->createQueryBuilder('p')
        // ->where('p.nif = :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->orderBy('p.dateDePaiement', 'DESC')
        // ->getQuery()
        // ->getResult();

        // Historique de communication
        // $comQuery = $sigtas_em->getRepository(PAIEMENT::class)
        // ->createQueryBuilder('p')
        // ->where('p.nif = :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->orderBy('p.')
        // ->getQuery();

        // Récupérations des informations RS et NomCOmmmercial Phone email dans la base NIF

        //recupération des éléments par correspndances NIF
        $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
            array('nif' => $contribuable->getNif()),
            array('nif' => 'ASC')
        );

        //S'il y a correspondance assigner mettre les inforamtions dans des variables
        //à faire en array si maitriser
        if ($nifInfos != null) {
            $sigtasRs = $nifInfos->getRs();
            $sigtasNc = $nifInfos->getNomcommercial();
            $sigtasMail = $nifInfos->getEmail();
            $sigtasPhone = $nifInfos->getContactPhone();
            $sigtasNomDirigeant = $nifInfos->getNomDirigeant();
            if ($sigtasRs == null)
                $sigtasRs = '-';
            if ($sigtasNc == null)
                $sigtasNc = '-';
            if ($sigtasMail == null)
                $sigtasMail = '-';
            if ($sigtasPhone == null)
                $sigtasPhone = '-';
        } else // s'il n'y a pas de correspondance
        {
            $sigtasRs = '-';
            $sigtasNc = '-';
            $sigtasMail = '-';
            $sigtasPhone = '-';
            $sigtasNomDirigeant = '-';
        }

        if ($nifInfos) {
            $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                'taxPayerNo' => $contribuable->getId()
            ]);
            if ($enterprise) {
                $secteurActiviteQuery = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                    'id' => $enterprise->getSectorActNo()
                ]);
                if ($secteurActiviteQuery) {
                    $contribuable->sectorActivite = $secteurActiviteQuery->getSectorActDesc();
                }
            }
        }
        //assignation des valeurs de RS Mail NomCommercial Phone
        $contribuable->NomDirigeant = $sigtasNomDirigeant;
        $contribuable->setSigtasRs($sigtasRs);
        $contribuable->setSigtasMail($sigtasMail);
        $contribuable->setSigtasNc($sigtasNc);
        $contribuable->setSigtasPhone($sigtasPhone);
        //$contribuable->setSigtas($taxpayer);
        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $requete,
                    $request->query->getInt('page', 1),
                    50
                );
         */

        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'ourcodeworld_pdf_demo';
        $html = $this->render('DBundle:Default:DFU\showpdf.html.twig', [
            'contribuable' => $contribuable,
            'carteFiscales' => $CarteFiscaleQuery,
            'tos' => $TOQuery,
            'entrants' => $countCourrierEntrant,
            'sortants' => $countCourrierSortant,
            'paiements' => $paiementQuery,


            //'relances' => $relanceQuery,
        ]);
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    // pas dans le routing actuellement
    public function printItAction(Request $request)
    {

        $snappy = $this->get('knp_snappy.pdf');
        //$url = 'http://ourcodeworld.com';

        $html = $this->renderView('DBundle:Default:DFU\pdf.html.twig', array(
            "title" => "Awesome PDF Title"
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response(
            //$snappy->getOutput($url),
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }

    public function relancesAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $RelanceSetting = $em->getRepository(RelanceSetting::class)->findOneBy([], ['id' => 'desc']);
        if (!$RelanceSetting) {
            throw new Exception("Vous devez d'abord faire un parametre de relance", 1);
        }
        $query = $em->getRepository(CourierSortant::class)
            ->createQueryBuilder('c')
            ->where('c.nif = :nif')
            ->andWhere('c.categorie = :categorie')
            ->setParameter('nif', $contribuable_nif)
            ->setParameter('categorie', $RelanceSetting->getId())
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        $contribuable = $this->getContribuable($contribuable_nif);

        return $this->render('DBundle:Default:DFU\relance.html.twig', [
            'relances' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuable
        ]);
    }

    public function correspondanceAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $contribuable = $this->getContribuable($contribuable_nif);
        //
        $query = $em->getRepository(CourierEntrant::class)
            ->createQueryBuilder('d')
            ->where('d.nif = :nif')
            ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:DFU\correspondance.html.twig', [
            'correspondances' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuable
        ]);
    }

    public function correspondanceSortantAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $contribuable = $this->getContribuable($contribuable_nif);
        $query = $em->getRepository(CourierSortant::class)
            ->createQueryBuilder('c')
            ->where('c.nif = :nif')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:DFU\correspondance_sortant.html.twig', [
            'correspondances' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuable
        ]);
    }

    public function historiqueDeDialogueAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $contribuable = $this->getContribuable($contribuable_nif);
        $query = $em->getRepository(Communication::class)
            ->createQueryBuilder('c')
            ->where('c.nif = :nif')
            ->orderBy('c.date', 'desc')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:DFU\historique_de_dialogue.html.twig', [
            'dialogues' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuable
        ]);
    }

    public function historiqueTaxationOfficeAction(Request $request, $contribuable_nif)
    {
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuable = $this->getContribuable($contribuable_nif);

        $query = $sigtas_em->getRepository(TaxationOffice::class)
            ->createQueryBuilder('t')
            ->where('t.nif = :nif')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:DFU\historique_taxation_office.html.twig', [
            'taxation_offices' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuable
        ]);
    }

    public function carteFiscaleAction(Request $request, $contribuable_nif)
    {
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $query = $sigtas_em->getRepository(CarteFiscale::class)
            ->createQueryBuilder('c')
            ->where('c.nif = :nif')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:DFU\carte-fiscale.html.twig', [
            'cartefiscales' => $pagination,
            'contribuable_nif' => $contribuable_nif
        ]);
    }


    public function historiquePaiementAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');


        $contribuableNif = $this->getContribuable($contribuable_nif);
        $query = $sigtas_em->getRepository(PAIEMENT::class)
            ->createQueryBuilder('p')
            ->where('p.nif = :nif')
            ->setParameter('nif', $contribuable_nif)
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            50
        );

        foreach ($pagination->getItems() as $key => $contribuable) {
            $sigtas = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy([
                'nif' => $contribuable->getNif()
            ]);
            $contribuable->setContribuable($sigtas);
        }

        return $this->render('DBundle:Default:DFU\historique-paiement.html.twig', [
            'paiements' => $pagination,
            'contribuable_nif' => $contribuable_nif,
            'contribuable' => $contribuableNif
        ]);
    }

    public function newAssujettissementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $Assujettissement = new Assujettissement();

        $form = $this->createForm(AssujettissementType::class, $Assujettissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Assujettissement);
            $em->flush();

            return $this->redirectToRoute('listeTypeAssujettissement');
        }

        return $this->render('DBundle:Default:Assujettissement\new_assujettissement.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function listeTypeAssujettissementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qr = $em->getRepository(Assujettissement::class)
            ->createQueryBuilder('a')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('DBundle:Default:Assujettissement\assujettissement.html.twig', [
            'assujettissements' => $pagination
        ]);
    }

    public function autoCompleteNifAction(Request $request)
    {
        $query = $request->query->get('query');
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $contribuables = $nif_em->getRepository(NIFOnlineClients::class)
            ->createQueryBuilder('n')
            ->where('n.nif LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->getQuery()->setMaxResults(50)->getResult();

        $jsonData = new \stdClass();
        $jsonData->query = 'Unit';
        $jsonData->suggestions = [];

        foreach ($contribuables as $key => $contribuable) {
            $row = new \stdClass();
            $row->value = $contribuable->getNif();
            //get raison social
            $row->data = $contribuable->getRs();
            //build json or data structure to transform to json
            $jsonData->suggestions[] = $row;
        }
        //data to json
        $json = json_encode($jsonData);
        //return json
        return new Response($json);
    }

    public function autoCompleteRsAction(Request $request)
    {
        // die('ok');
        $query = $request->query->get('query');
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $contribuables = $nif_em->getRepository(NIFOnlineClients::class)
            ->createQueryBuilder('n')
            ->where('n.rs LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->getQuery()->setMaxResults(50)->getResult();

        $jsonData = new \stdClass();
        $jsonData->query = 'Unit';
        $jsonData->suggestions = [];

        foreach ($contribuables as $key => $contribuable) {
            $row = new \stdClass();
            $row->value = $contribuable->getRs();
            //get raison social
            $row->data = $contribuable->getNif();
            //build json or data structure to transform to json
            $jsonData->suggestions[] = $row;
        }
        //data to json
        $json = json_encode($jsonData);
        //return json
        return new Response($json);
    }

    public function getContribuableAction(Request $request)
    {
        die('ok');
        $contribuable_nif = $request->query->get('nif');
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $contribuable = $this->getContribuable($contribuable_nif);

        $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
            array('nif' => $contribuable->getNif()),
            array('nif' => 'ASC')
        );

        //S'il y a correspondance assigner mettre les inforamtions dans des variables
        //à faire en array si maitriser
        if ($nifInfos != null) {
            $sigtasRs = $nifInfos->getRs();
            $sigtasNc = $nifInfos->getNomcommercial();
            $sigtasMail = $nifInfos->getEmail();
            $sigtasPhone = $nifInfos->getContactPhone();

            if ($sigtasRs == null)
                $sigtasRs = '-';
            if ($sigtasNc == null)
                $sigtasNc = '-';
            if ($sigtasMail == null)
                $sigtasMail = '-';
            if ($sigtasPhone == null)
                $sigtasPhone = '-';
        } else // s'il n'y a pas de correspondance
        {
            $sigtasRs = '-';
            $sigtasNc = '-';
            $sigtasMail = '-';
            $sigtasPhone = '-';
        }

        //assignation des valeurs de RS Mail NomCommercial Phone
        $contribuable->setSigtasRs($sigtasRs);
        $contribuable->setSigtasMail($sigtasMail);
        $contribuable->setSigtasNc($sigtasNc);
        $contribuable->setSigtasPhone($sigtasPhone);

        if (!$contribuable) {
            return new Response("contribuable introuvable");
        }
        $response = '';
        $response .= '<table class="table table-striped table-hover"><tbody>';
        $response .= '<tr><th>NIF</th><td>' . $contribuable->getNif() . '</td></tr>';
        if ($contribuable->getSigtasRs()) {
            $response .= '<tr><th>Nom ou raison sociale</th><td>' . $contribuable->getSigtasRs() . '</td></tr>';
        } else {
            $response .= '<tr><th>Nom ou raison sociale</th><td>' . $contribuable->getSigtasNc() . '</td></tr>';
        }
        $response .= '<tr><th>Email</th><td>' . $contribuable->getSigtasMail() . '</td></tr>';
        $response .= '<tr><th>Contact phone</th><td>' . $contribuable->getSigtasPhone() . '</td></tr>';
        $response .= '<tr><th>Adresse</th><td>' . $contribuable->getAdresse() . '</td></tr>';

        $response .= '</tbody></table>';

        return new Response($response);
    }

    // pubic function getDocument($contribuable_nif){
    //     $sigtas_em = $this->getDoctrine()->getManager('sigtas');
    //     $contribuable = $this->getContribuable($contribuable_nif);
    //     //all in sigtas are using taxpayerno
    //     $query = $sigtas_em->getRepository(DocCourrier::class)
    //     ->createQueryBuilder('d')
    //     ->where('d.nif = :nif')
    //     ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
    //     ->getQuery();

    //     $paginator  = $this->get('knp_paginator');
    //     $pagination = $paginator->paginate(
    //         $query,
    //         $request->query->getInt('page', 1),
    //         50
    //     ); 

    //     return $this->render('DBundle:Default:DFU\correspondance.html.twig', [
    //         'correspondances' => $pagination,
    //         'contribuable_nif' => $contribuable_nif,
    //         'contribuable' => $contribuable
    //     ]);
    // }

    public function razMvtAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('e');
        $qb->select('e.id');
        $qb->from(Entrant::class, 'e');
        $qb->where('e.status IS Ferme');
        $res = $qb->getQuery();
        foreach ($res as  $res) {
            // $entrant = new Entrant;
            $res->setStatus('Nouveau');
            $em->persist($res);
            $em->flush();
        }
        return $this->redirectToRoute('list_entrant');

    }

    public function entrantParCategorieAction(Request $request)
    {
        // die('cat');
        $em = $this->getDoctrine()->getManager();

        $entrants = $em->getRepository(Entrant::class)
            ->createQueryBuilder('e')
            ->where('e.commentaires IS NOT null')
            ->orderBy('e.commentaires', 'ASC')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $entrants,
                $request->query->getInt('page', 1),
                50
            );

        return $this->render('DBundle:Default:listeParCategorie.html.twig', [
            'courriersEntrants' => $pagination,
        ]);
    }

    public function dfuShowContribuableAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuable = $this->getContribuable($contribuable_nif);

        $typeDossierQuery = $em->getRepository(TypeDossier::class)
            ->createQueryBuilder('cf')
            ->where('cf.nif = ' . $contribuable_nif . '')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

            // $observation = new TypeDossier;
            // $observation->setNif($contribuable_nif);
            // $observation->setCreatedBy($this->getUser());
            // $observation->setCreatedAt(new \DateTime);
            // $formulaire_observation = $this->createForm(TypeDossierType::class, $observation);
            // $formulaire_observation->handleRequest($request);
    
            // if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
            //     // $contribuable->setUpdatedAt(new \DateTime);
            //     $em->persist($observation);
            //     $em->flush();
            //     return $this->redirectToRoute('dfushow', ['contribuable' => $contribuable->getNif()]);
            // }
            // $observations =  $em->getRepository(TypeDossier::class)->findBy(['contribuable' => $contribuable->getNif()]);
            // $defaultData = ['message' => 'Type your message here'];
    
            // $courrier->setObservationContent('Observations');
            // $assigne_form = $this->createFormBuilder($defaultData)
            //     ->add('gestionnaire', EntityType::class, [
            //         'placeholder' => 'Choisissez',
            //         'class' => User::class,
            //         'query_builder' => function ($er) {
            //             return $er->createQueryBuilder('u')
            //                 ->where('u.service = ' . $this->getUser()->getService()->getId() . '')
            //                 ->orderBy('u.nom', 'ASC');
            //         },
            //         'choice_label' => function ($gestionnaire) {
            //             $name = $gestionnaire->getNom() . ' ' . $gestionnaire->getPrenom() /*.'('.$gestionnaire->getService()->getNom().')*/;
            //             return $name;
            //         },
            //         'multiple' => false,
            //         'expanded' => false,
            //     ])
            //     ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']])
            //     ->getForm();
    
            // $assigne_form->handleRequest($request);
    
            // if ($request->getMethod() == 'GET') {
            //     $stat = $request->query->get('changestatus');
            //     if ($stat) {
    
            //         switch ($stat) {
    
            //             case 'en_attente':
            //                 if (!$isChefDeService) {
            //                     throw $this->createNotFoundException('Page introuvable.');
            //                 }
            //                 $courrier->setStatus($stat);
            //                 $courrier->setUpdatedAt(new \DateTime);
            //                 $observation = new TacheObservation;
            //                 $observation->setUser($this->getUser());
            //                 $observation->setCourrier($courrier);
            //                 $observation->setCreatedAt(new \DateTime);
            //                 $observation->setMessage('a changé le status: <span class="badge bg-blue">' . $stat . '</span>');
            //                 $em->persist($observation);
            //                 break;
    
            //             case 'En cours':
            //                 if (!$isChefDeService) {
            //                     throw $this->createNotFoundException('Page introuvable.');
            //                 }
            //                 $courrier->setStatus($stat);
            //                 $courrier->setUpdatedAt(new \DateTime);
            //                 $observation = new TacheObservation;
            //                 $observation->setUser($this->getUser());
            //                 $observation->setCourrier($courrier);
            //                 $observation->setCreatedAt(new \DateTime);
            //                 $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
            //                 $em->persist($observation);
            //                 break;
    
            //             case 'Traité':
            //                 $courrier->setStatus($stat);
            //                 $courrier->setUpdatedAt(new \DateTime);
            //                 $observation = new TacheObservation;
            //                 $observation->setUser($this->getUser());
            //                 $observation->setCourrier($courrier);
            //                 $observation->setCreatedAt(new \DateTime);
            //                 $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
            //                 $em->persist($observation);
            //                 break;
    
            //             case 'Clôturé':
    
            //                 if (!$isChefDeService) {
            //                     throw $this->createNotFoundException('Page introuvable.');
            //                 }
    
            //                 $courrier->setStatus($stat);
            //                 $courrier->setUpdatedAt(new \DateTime);
            //                 $observation = new TacheObservation;
            //                 $observation->setUser($this->getUser());
            //                 $observation->setCourrier($courrier);
            //                 $observation->setCreatedAt(new \DateTime);
            //                 $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
            //                 $em->persist($observation);
            //                 break;
            //         }
    
            //         $em->flush();
            //         return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
            //     }
    
    
        // $typedos = $em->getRepository(TypeDeDossier::class)->findOneBy(
        //     array('id' => $typeDossierQuery->getIdNature()),
        // );
        // if($typedos)
        // {
        //     $contribuable->set
        // }

        $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
            array('nif' => $contribuable->getNif()),
            array('nif' => 'ASC')
        );

        if ($nifInfos != null) {
            $sigtasRs = $nifInfos->getRs();
            $sigtasNc = $nifInfos->getNomcommercial();
            $sigtasMail = $nifInfos->getEmail();
            $sigtasPhone = $nifInfos->getContactPhone();
            $sigtasNomDirigeant = $nifInfos->getNomDirigeant();
            if ($sigtasRs == null)
                $sigtasRs = '-';
            if ($sigtasNc == null)
                $sigtasNc = '-';
            if ($sigtasMail == null)
                $sigtasMail = '-';
            if ($sigtasPhone == null)
                $sigtasPhone = '-';
        } else
        {
            $sigtasRs = '-';
            $sigtasNc = '-';
            $sigtasMail = '-';
            $sigtasPhone = '-';
            $sigtasNomDirigeant = '-';
        }

        if ($nifInfos) {
            $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                'taxPayerNo' => $contribuable->getTaxPayerNo()
            ]);
            if ($enterprise) {
                $secteurActiviteQuery = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                    'id' => $enterprise->getSectorActNo()
                ]);
                if ($secteurActiviteQuery) {
                    $contribuable->sectorActivite = $secteurActiviteQuery->getSectorActDesc();
                }
                $entryDateQuery = $enterprise->getEntryDate();
                $fiscYrStartQuery = $enterprise->getFiscYrStart();
                $fiscYrEndQuery = $enterprise->getFiscYrEnd();
                $startDateQuery = $enterprise->getStartDate();
            }
        }
        $contribuable->startDate = $startDateQuery;
        $contribuable->entryDate = $entryDateQuery;
        $contribuable->fiscYrStart = $fiscYrStartQuery;
        $contribuable->fiscYrEnd = $fiscYrEndQuery;
        $contribuable->nomDirigeant = $sigtasNomDirigeant;
        $contribuable->setSigtasRs($sigtasRs);
        $contribuable->setSigtasMail($sigtasMail);
        $contribuable->setSigtasNc($sigtasNc);
        $contribuable->setSigtasPhone($sigtasPhone);

        return $this->render('DBundle:Default:DFU\dfushow.html.twig', [
            'contribuable' => $contribuable,
            'typeDossiers' => $typeDossierQuery,
        ]);
    }

    public function editContribuableAction(Request $request, $contribuable_nif)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contrib = $em->getRepository(Contribuables::class)
            ->createQueryBuilder('cb')
            ->where('cb.nif = ' . $contribuable_nif . '')
            // ->orderBy('cb.carteTypeNo', 'ASC')
            ->getQuery()
            ->getResult();

        // dump($contrib);
        // die();
        // $contribuable = $this->getContribuable($contribuable_nif);

        // $CarteFiscaleQuery = $sigtas_em->getRepository(CarteFiscale::class)
        //     ->createQueryBuilder('cf')
        //     ->where('cf.nif = ' . $contribuable_nif . '')
        //     ->andWhere('cf.carteTypeNo = :A')
        //     ->setParameter('A', "A")
        //     ->orderBy('cf.carteTypeNo', 'ASC')
        //     ->getQuery()
        //     ->getResult(Query::HYDRATE_ARRAY);

        // $TofficeQuery = $sigtas_em->getRepository('SIGTASBundle:TaxationOffice');
        // $TOQuery = $TofficeQuery->selectAll($contribuable_nif);

        // $RARQuery = $sigtas_em->getRepository('SIGTASBundle:Titre_perception');
        // $RARsQuery = $RARQuery->selectAll($contribuable_nif);

        // //Courrier Entrant
        // $entrantQuery = $sigtas_em->getRepository(DocCourrier::class)
        //     ->createQueryBuilder('e')
        //     ->where('e.nif = :nif')
        //     ->andWhere('e.typeCourrier = :entrant')
        //     ->setParameter('entrant', "E")
        //     ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
        //     ->getQuery();

        // // Courrier sortant
        // $sortantQuery = $sigtas_em->getRepository(DocCourrier::class)
        //     ->createQueryBuilder('s')
        //     ->where('s.nif = :nif')
        //     ->andWhere('d.typeCourrier = :sortant')
        //     ->setParameter('sortant', "S")
        //     ->setParameter('nif', $contribuable->getSigtas()->taxPayerNo)
        //     ->getQuery();

        // $qb = $em->createQueryBuilder('e');
        // $qb->select('count(e.numeroCourrier)');
        // $qb->from(Entrant::class, 'e');
        // $qb->where('e.nif = :nif');
        // $qb->setParameter('nif', $contribuable_nif);
        // $countCourrierEntrant = $qb->getQuery()->getSingleScalarResult();

        // $qb = $em->createQueryBuilder('s');
        // $qb->select('count(s.numeroCourrier)');
        // $qb->from(Sortant::class, 's');
        // $qb->where('s.nif = :nif');
        // $qb->setParameter('nif', $contribuable_nif);
        // $countCourrierSortant = $qb->getQuery()->getSingleScalarResult();

        // // Paiement
        // $pQuery = $sigtas_em->getRepository('SIGTASBundle:PAIEMENT');
        // $paiementQuery = $pQuery->selectAll($contribuable_nif);

        // Historique de communication
        // $comQuery = $sigtas_em->getRepository(PAIEMENT::class)
        // ->createQueryBuilder('p')
        // ->where('p.nif = :nif')
        // ->setParameter('nif', $contribuable_nif)
        // ->orderBy('p.')
        // ->getQuery();

        // Récupérations des informations RS et NomCOmmmercial Phone email dans la base NIF

        //recupération des éléments par correspndances NIF
        // $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
        //     array('nif' => $contribuable->getNif()),
        //     array('nif' => 'ASC')
        // );

        // if ($nifInfos != null) {
        //     $sigtasRs = $nifInfos->getRs();
        //     $sigtasNc = $nifInfos->getNomcommercial();
        //     $sigtasMail = $nifInfos->getEmail();
        //     $sigtasPhone = $nifInfos->getContactPhone();
        //     $sigtasNomDirigeant = $nifInfos->getNomDirigeant();
        //     if ($sigtasRs == null)
        //         $sigtasRs = '-';
        //     if ($sigtasNc == null)
        //         $sigtasNc = '-';
        //     if ($sigtasMail == null)
        //         $sigtasMail = '-';
        //     if ($sigtasPhone == null)
        //         $sigtasPhone = '-';
        // } else // s'il n'y a pas de correspondance
        // {
        //     $sigtasRs = '-';
        //     $sigtasNc = '-';
        //     $sigtasMail = '-';
        //     $sigtasPhone = '-';
        //     $sigtasNomDirigeant = '-';
        // }

        // if ($nifInfos) {
        //     $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
        //         'taxPayerNo' => $contribuable->getTaxPayerNo()
        //     ]);
        //     if ($enterprise) {
        //         $secteurActiviteQuery = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
        //             'id' => $enterprise->getSectorActNo()
        //         ]);
        //         if ($secteurActiviteQuery) {
        //             $contribuable->sectorActivite = $secteurActiviteQuery->getSectorActDesc();
        //         }
        //         $entryDateQuery = $enterprise->getEntryDate();
        //         $fiscYrStartQuery = $enterprise->getFiscYrStart();
        //         $fiscYrEndQuery = $enterprise->getFiscYrEnd();
        //         $startDateQuery = $enterprise->getStartDate();
        //     }
        // }
        // //assignation des valeurs de RS Mail NomCommercial Phone
        // // $contribuable->secteurActivite = $
        // $contribuable->startDate = $startDateQuery;
        // $contribuable->entryDate = $entryDateQuery;
        // $contribuable->fiscYrStart = $fiscYrStartQuery;
        // $contribuable->fiscYrEnd = $fiscYrEndQuery;
        // $contribuable->nomDirigeant = $sigtasNomDirigeant;
        // $contribuable->setSigtasRs($sigtasRs);
        // $contribuable->setSigtasMail($sigtasMail);
        // $contribuable->setSigtasNc($sigtasNc);
        // $contribuable->setSigtasPhone($sigtasPhone);
        //$contribuable->setSigtas($taxpayer);
        /*
                $paginator  = $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $requete,
                    $request->query->getInt('page', 1),
                    50
                );
         */
        // return $this->render('DBundle:Communication:index.html.twig', array(

            return $this->render('DBundle:contribuables:edit.html.twig', [
            'contribuable' => $contrib,
            // 'carteFiscales' => $CarteFiscaleQuery,
            // 'tos' => $TOQuery,
            // 'RARs' => $RARsQuery,
            // 'entrants' => $countCourrierEntrant,
            // 'sortants' => $countCourrierSortant,
            // 'paiements' => $paiementQuery,

            //'relances' => $relanceQuery,
        ]);
    }
    
    public function statistiquesAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $nifReq = $request->query->get('nif');
            $rsReq = $request->query->get('rs');
            $sectActReq = $request->query->get('sectAct');
        }

        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nifFilter = $request->query->get('nif');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $doctypeno = 1;
        $docstateno = 2;
        $qb = $sigtas_em->createQueryBuilder();
        $qb->select('count(account.nif)');
        $qb->from(TaxPayer::class, 'account');
        $qb->where('account.inactifDate IS NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        //Nombre de courrier total
        $qb = $sigtas_em->createQueryBuilder();
        $qb->select('count(c.docNo)');
        $qb->from(DocCourrier::class, 'c');

        $countCourrier = $qb->getQuery()->getSingleScalarResult();
        // $resp = $sigtas_em->getRepository('SIGTASBundle:Clients');
        // $rest=$resp->selectquery();
        // dump($rest);die();
        $qbd = $sigtas_em->createQueryBuilder('c');
        $qbd->select('c.taxPayerNo');
        $qbd->from(TaxPayer::class, 'c');
        $qbd->where('c.inactifDate IS NULL');
        $res = $qbd->getQuery()->getArrayResult();

        $Allnif = array();
        foreach ($res as  $res) {
            foreach ($res as  $res) {
                $enterprise = $sigtas_em->getRepository(Enterprise::class)->findBytaxPayerNo($res);
                array_push($Allnif, $enterprise);
            }
        }

        // Courriers entrants
        $qb = $em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from(Entrant::class, 'e');
        $qb
            ->andWhere('e.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr);
        $countCourrierEntrant = $qb->getQuery()->getSingleScalarResult();

        // Courriers entrants traités
        $qb = $em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from(Entrant::class, 'e');
        $qb
            ->Where('e.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->andWhere('e.status = :statut')
            ->setParameter('statut', 'Traité');
        $nb_CE_Traite = $qb->getQuery()->getSingleScalarResult();

        // Courriers entrants transmis
        $qb = $em->createQueryBuilder();
        $qb->select('count(e.id)');
        $qb->from(Entrant::class, 'e');
        $qb
            ->Where('e.yearCourr = :yearCourr')
            ->setParameter('yearCourr', '2021')//$yearCourr
            ->andWhere('e.status = :statut')
            ->setParameter('statut', 'Transmis');
        $nb_CE_Transmis = $qb->getQuery()->getSingleScalarResult();

        // Courriers entrants par service
        // $qb = $em->createQueryBuilder();
        // $qb->select('count(e.id)');
        // $qb->from(Entrant::class, 'e');
        // $qb
        //     ->Where('e.yearCourr = :yearCourr')
        //     ->setParameter('yearCourr', '2021')//$yearCourr
        //     ->andWhere('e.services = :service')
        //     ->setParameter('service', '2');
        // $nb_CE_SAI = $qb->getQuery()->getSingleScalarResult();

        // $entrantQuery = $em->getRepository(Service::class)
        //     ->find($user->getService()->getId())
        //     ->getEntrant();

        // dump($entrantQuery);
        // die();
        // $entrantQueryOk =[];
        // foreach( $entrantQuery as  $entrant){                
        //     if ($nifFilter) {
        //         if($nifFilter == $entrant->getNif()) {
        //             array_push($entrantQueryOk,$entrant);
        //         }
        //     }else
        //     {
        //         array_push($entrantQueryOk,$entrant);
        //     }
        // }

        // dump($entrantQueryOk);
        // die();
        // $entrantQueryc =[];
        // $courrier = "";
        // foreach( $entrantQueryOk as  $entrant){                
        //     $courrier = $entrant->getCourrierId();
        //     array_push($entrantQueryc,$courrier);
        // }

        // dump($entrantQueryOk, $entrantQueryc);
        // die();

        // Courriers sortants
        $qb = $em->createQueryBuilder();
        $qb->select('count(s.id)');
        $qb->from(Sortant::class, 's');
        $qb
            ->andWhere('s.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr);
        $countCourrierSortant = $qb->getQuery()->getSingleScalarResult();

        // Courrier de type R (R ne signifie pas Relance, mais recouvrement ???)
        $qb = $sigtas_em->createQueryBuilder();
        $qb->select('count(r.docNo)');
        $qb->from(DocCourrier::class, 'r');
        $qb->where('r.typeCourrier = :relance');
        $qb->setParameter('relance', "R");
        $countCourrierRelance = $qb->getQuery()->getSingleScalarResult();

        // déclarations faites (dépôt)
        $Documents = $sigtas_em->createQueryBuilder()
            ->select('count(d.receivedDate)')
            ->from(Document::class, 'd')
            ->where('d.receivedDate IS NOT null')
            ->andWhere('d.docTypeNo = :dtp')
            ->setparameter('dtp', $doctypeno)
            ->andWhere('d.docStateNo = :dts')
            ->setparameter('dts', $docstateno)
            ->orderBy('d.receivedDate', 'DESC');
        $depot = $Documents->getQuery()->getSingleScalarResult();
        // ;

        // Taxations d'office réalisées
        $Assessments = $sigtas_em->createQueryBuilder()
            ->select('count(o.toReceivedDate)')
            ->from(Assessment::class, 'o')
            ->where('o.toReceivedDate IS NOT null')
            ->orderBy('o.toReceivedDate', 'DESC');
        $to_etablis = $Assessments->getQuery()->getSingleScalarResult();;

        //Taxations d'office à etablir
        $Assessments = $sigtas_em->createQueryBuilder()
            ->select('count(t.toReceivedDate)')
            ->from(Assessment::class, 't')
            ->where('t.toReceivedDate IS null')
            ->orderBy('t.toReceivedDate', 'DESC');
        $to_a_etablir = $Assessments->getQuery()->getSingleScalarResult();;

        $recentlyUser = $em->getRepository(User::class)->findBy([],['id' => 'desc'],50);

        $countsector = [];
        $Listsector = [];
        //$test[0]=44;
        $array = array();
        //sCountsector 
        $sectorAct = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $listsector = array();
        $listSectNo = array();
        $countlist = array();
        foreach ($sectorAct as $sectorAct) {
            array_push($listsector, $sectorAct->getId());
        } //dump($listsector);die();

        foreach ($Allnif as $Allnif) {
            foreach ($Allnif as $Allnif) {
                array_push($listSectNo, $Allnif->getSectorActNo());
            }
        }
        for ($i = 0; $i < count($listsector); $i++) {
            $k = 0;
            for ($j = 0; $j < count($listSectNo); $j++) {
                if ($listsector[$i] === $listSectNo[$j]) {
                    $k = $k + 1;
                }
            }
            array_push($countsector, $k);
        }

        $listcolor = [];
        $sectorAct = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        foreach ($sectorAct as $sectorAct) {
            array_push($Listsector, $sectorAct->getSectorActDesc());

            $random_color_part1 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $random_color_part2 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $random_color_part3 = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
            $color = "#" . $random_color_part1 . $random_color_part2 . $random_color_part3;
            array_push($listcolor, $color);
        }

        return $this->render('DBundle:Default:index.html.twig', [
            'clients_nif' => $count,
            'totalCourrier' => $countCourrier,
            'courrier_sortant' => $countCourrierSortant,
            'courrierEntrant' => $countCourrierEntrant,
            'courrierRelance' => $countCourrierRelance,
            'recentlyUser' => $recentlyUser,
            'to_etablis' => $to_etablis,
            'to_a_etablir' => $to_a_etablir,
            'depot' => $depot,
            'countsector' => json_encode($countsector),
            'Listsector' => json_encode($Listsector),
            'listcolor' => json_encode($listcolor),
            'nb_ce_traite' => $nb_CE_Traite,
            'nb_ce_transmis' => $nb_CE_Transmis,
            // 'nb_ce_SAI' => $nb_CE_SAI,
        ]);

    }

    public function autoCompleteNif4Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $cbs = $em->getRepository(Contribuables::class)->getByNif($nif);
            $output = [];
            foreach ($cbs as $cb) {
                $temp_array = array();
                $temp_array['thisNif'] = $cb->getNif();
                $temp_array['raisonSoncial'] = $cb->getRaisonSociale();
                $temp_array['useIt'] = $cb->getNif() . ' - ' . $cb->getRaisonSociale() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }
    
    public function autoCompleteRsoc4Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $entrants = $em->getRepository(Contribuables::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($entrants as $entrant) {
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSociale();
                $temp_array['useIt'] = $entrant->getNif() . ' - ' . $entrant->getRaisonSociale() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }

    public function autoCompleteNif5Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $entrants = $sigtas_em->getRepository(Titre_perception::class)->getByNif($nif);
            $output = [];
            foreach ($entrants as $entrant) {
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSociale();
                $temp_array['useIt'] = $entrant->getNif() . ' - ' . $entrant->getRaisonSociale() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }
    
    public function autoCompleteRsoc5Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $entrants = $sigtas_em->getRepository(Titre_perception::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($entrants as $entrant) {
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSociale();
                $temp_array['useIt'] = $entrant->getNif() . ' - ' . $entrant->getRaisonSociale() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }

    public function setContribuablesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $contribuables = $em->getRepository(Contribuables::class)->findAll();
        
        // $qr = $sigtas_em->getRepository(TaxPayer::class)
        //     ->createQueryBuilder('n')
        //     ->where('n.inactifDate IS null')
        //     ->getQuery();

        //     $paginator  = $this->get('knp_paginator');
        //     $pagination = $paginator->paginate(
        //         $qr,
        //         $request->query->getInt('page', 1),
        //         50
        //     );       
            
        foreach ($contribuables as $contribuable) 
        {
                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $contribuable->getNif()),
                    array('nif' => 'ASC')
                );
                if($nifInfos!=null)
                {
                $contribuable->setTelephone($nifInfos->getContactPhone());
                $em->flush();
            }
        }
        return $contribuable;

    }

}
