<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use DBundle\Entity\User;
use DBundle\Entity\SaiSetting;
use DBundle\Entity\Service;
use DBundle\Entity\Sortant;
use DBundle\Entity\SortantObservation;
use DBundle\Entity\CourrierDispatching;
use DBundle\Entity\SortantObjet;
use DBundle\Entity\PourInfo;
use DBundle\Entity\Attribution;
use DBundle\Entity\CategorieCourierSortant;

use DBundle\Form\SortantObservationType;
use DBundle\Form\SortantType;
use DBundle\Form\SortantObjetType;
use DBundle\Repository\CategorieCourierSortantRepository;
use DBundle\Repository\SortantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Event\PaginationEvent;
use NIFBundle\Entity\Clients as NIFOnlineClients;

use SIGTASBundle\Entity\Document;
use SIGTASBundle\Entity\DocCourrier;
use SIGTASBundle\Entity\TaxationOffice;
use SIGTASBundle\Entity\Assessment;
use SIGTASBundle\Entity\TAX_ACCOUNT;
use SIGTASBundle\Entity\TaxPayer;
use SIGTASBundle\Entity\Enterprise;
use SIGTASBundle\Entity\Paiment;
use SIGTASBundle\Entity\CarteStat;
use SIGTASBundle\Entity\Assessement;
use SIGTASBundle\Entity\Division;
use SIGTASBundle\Entity\DocCourrierTitre;
use SIGTASBundle\Entity\DocCourrierObjet;
use SIGTASBundle\Entity\SECTOR_ACTIVITY;

class SortantController extends Controller
{

    public function refreshSortant(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $sectorAct = $request->query->get('sectorAct');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $categorie = $request->query->get('categorie');

        $courrierSortants = $em->getRepository(Sortant::class)->createQueryBuilder('ce')
            ->addOrderBy('ce.createdAt','DESC')
            ->addOrderBy('ce.numeroCourrier', 'DESC')
            ->distinct(true);

        if ($status) {
            $courrierSortants
                ->andWhere('ce.status LIKE :status')
                ->setParameter('status', $status);
        }
        if ($date_du && $date_au) {
            $courrierSortants
                ->andWhere('ce.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $courrierSortants
                ->andWhere('ce.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $courrierSortants
                ->andWhere('ce.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $courrierSortants
                ->andWhere('ce.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }
        if ($categorie) {
                $courrierSortants
                ->andWhere('ce.commentaires LIKE :categorie')
                ->setParameter('categorie', '%' . $categorie . '%');
        }

        $courrierSortants->getQuery();

        $paginator  = $this->get('knp_paginator');
        $courrierSortantPagination = $paginator->paginate(
            $courrierSortants,
            $request->query->getInt('page', 1),
            20
        );

        foreach ($courrierSortantPagination as $key => $courrierSortant) {
            if ($courrierSortant->dispatch = 'Dispatch') {
                $courrierSortant->dispatch = 'Dispatch';
            } else {
                $courrierSortant->dispatch = $courrierSortant->getService();
            }
        }

        return $courrierSortantPagination;
    }

    public function listAction(Request $request)
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
            ->from(SortantObservation::class, 'le')
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
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if ($isChefSAI || $isChefDeDirection || $isSystemUser) 
        {            
            $this->setStatAction();
            $sortantQuery = $em->getRepository(Service::class)
                                ->find($user->getService()->getId())
                                ->getSortant();

            // $nb_ce_sai = $sortantQuery->getQuery()->getSingleScalarResult();

            $sortantQueryOk =[];
            foreach( $sortantQuery as  $sortant){                
                if ($nifFilter) {
                    if($nifFilter == $sortant->getNif())
                    {
                        array_push($sortantQueryOk,$sortant);
                    }
                }else{
                    array_push($sortantQueryOk,$sortant);
                }
            }
            $sortantQueryc =[];
            $courrier = "";
            foreach( $sortantQueryOk as  $sortant){                
                $courrier = $sortant->getCourrierId();
                array_push($sortantQueryc,$courrier);
            }

            // Méthode N° 01
            if ($nifFilter && $status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif ($nifFilter && !$status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif (!$nifFilter && $status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }else {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc),
                    array('numeroCourrier' => 'DESC')
                );
            }
            // le filtre Date_du & Date_au ne fonctionne pas
            if ($date_du && $date_au) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'createdAt' => $date_du),
                    array('numeroCourrier' => 'DESC')
                );
            }else
            {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'status'=> "Transmis"),
                    array('numeroCourrier' => 'DESC')
                );
            }


            // Méthode N° 02 ne fonctionne pas du tout
            // $sortantQueryOkey = $em->getRepository(Sortant::class)->createQueryBuilder('ce')
            //     // ->Where('ce.courrierId LIKE :sortantQueryC')
            //     // ->setParameter('sortantQueryC', $sortantQueryc)
            //     ->addOrderBy('ce.createdAt','DESC')
            //     ->addOrderBy('ce.numeroCourrier', 'DESC')
            //     ->distinct(true);

            // if ($nifFilter) {
            //     $sortantQueryOkey
            //         ->andWhere('ce.nif LIKE :nif')
            //         ->setParameter('nif', '%' . $nifFilter . '%');
            // }
            // if ($rsFilter) {
            //     $sortantQueryOkey
            //         ->andWhere('ce.raisonSocial LIKE :rs')
            //         ->setParameter('rs', '%' . $rsFilter . '%');
            // }
            // if ($status) {
            //     $sortantQueryOkey
            //         ->andWhere('ce.status LIKE :status')
            //         ->setParameter('status', $status);
            // }
            //     $sortantQueryOkey
            //         ->andWhere('ce.createdAt BETWEEN :date_du AND :date_au')
            //         ->setParameter('date_du', $date_du)
            //         ->setParameter('date_au', $date_au);
            // }
            // if ($gestionnaireId) {
            //     $sortantQueryOkey
            //         ->andWhere('ce.gestionnaire  = :gestionnaire')
            //         ->setParameter('gestionnaire', $gestionnaireId);
            // }

            // $documentsQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
            //     ->addOrderBy('e.createdAt','DESC')
            //     ->addOrderBy('e.numeroCourrier', 'DESC')
            //     ->distinct(true)

            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQueryOkey,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'imprimer' => 'listAction',
                'categories' => $categories,
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

            else if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId() /*or $sortantDuService  */) {
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

            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));

            if ($isChefDeService) {
                if ($gestionnaireId) {
                    $documentsQuery
                        ->andWhere('e.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaireId);
                }

                $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                    ->where('e.service = :service')
                    ->setParameter('service', $user->getService())
                    ->distinct('e.numero')
                    ->getQuery();

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQuery,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:list.html.twig', array(
                    'courriers' => $sortants,
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
                    'imprimer' => 'listAction',
                    'categories' => $categories,
                ));
            }

            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));
        }
        else {
            $documentsQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e');
            $documentsQuery
                ->andWhere('e.service = :service')
                ->setParameter('service', $user->getService())
                ->distinct('e.numero');
            $sortantQuery = $em->getRepository(Service::class)
                ->find($user->getService()->getId())
                ->getSortant();

            // $nb_ce_sai = $sortantQuery->getQuery()->getSingleScalarResult();

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
                return $this->redirectToRoute('list_sortant_assigne');
            }
            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

                $sortantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getSortant();

                $sortantQueryc =[];
                $courrier = "";
                foreach( $sortantQuery as  $sortant){                
                    $courrier = $sortant->getCourrierId();
                    array_push($sortantQueryc,$courrier);
                }

                // if ($isChefDeService) {
                    // if (!$nifFilter && !$status && !$rsFilter && !$date_du && !$date_au) {
                    //     $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    //         array('courrierId'=> $sortantQueryc, 'status'=> 'Transmis' ),
                    //         array('numeroCourrier' => 'DESC')
                    //     );
                    // }
                // }
                if ($nifFilter && $status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif ($nifFilter && !$status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif (!$nifFilter && $status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }else {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'status'=> 'Transmis'),
                        array('numeroCourrier' => 'DESC')
                    );
                }

                // avant
                // $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy([
                //     'status'=> $status,
                //     'courrierId'=> $sortantQueryc,
                //     'numeroCourrier' => 'DESC',
                // ]);
                    // 'numeroCourrier' => 'DESC');

                // $sortantQueryOkey = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                // ->where('ca.gestionnaire IS null ');
    
                // $sortantQueryOkey = $em->createQueryBuilder()
                // // ->select('(e.createdAt)')
                // ->from(Sortant::class, 'e')
                // ->Where('e.courrierId = :courrierId')
                // ->setParameter('courrierId', $sortantQueryc)
                // ->orderBy('e.numeroCourrier', 'DESC')
                // if ($status){
                //     $sortantQueryOkey
                //     ->andWhere('e.status = :val')
                //     ->setParameter('val', $status)
                // }
                // $sortantQueryOkey
                // ->getQuery();

                    

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQueryOkey,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:list.html.twig', array(
                    'courriers' => $sortants,
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
                    'imprimer' => 'listAction',
                    'categories' => $categories,
                ));
            }

            $sortantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getSortant();
            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQuery,
                $request->query->getInt('page', 1),
                20
            );
            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $sortants,
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
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));
        }

    }

    public function listPdfCheckAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nomBynumCourier = [];
        $attributionList = [];

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'Y-m-d H:i:s');

        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();

        $sai = $em->getRepository(SaiSetting::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;
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

            $sortantCheck = $em->createQueryBuilder();
            $sortantCheck->select('count(sortant.numeroCourrier)');
            $sortantCheck->from(Sortant::class, 'sortant');
            $sortantCount = $sortantCheck->getQuery()->getSingleScalarResult();

            for ($i = 0; $i < $sortantCount; $i++) {
                array_push($nomBynumCourier, " ");
                array_push($attributionList, " ");
            }
            if ($sortantCount > 0) {
                $sortantLast = $em->createQueryBuilder()
                    ->select('MAX(le.numeroCourrier)')
                    ->from(Sortant::class, 'le')
                    ->where('le.yearCourr = 2022')
                    ->getQuery()

                    ->getSingleScalarResult();

                $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                    ->where('nc.numero > :lastNumero')
                    ->setParameter('lastNumero', $sortantLast)
                    ->andWhere('nc.typeCourrier LIKE :typeCourrier')
                    ->setParameter('typeCourrier', "E")
                    ->andWhere('nc.yearCourr LIKE :yearCourr')
                    ->setParameter('yearCourr', 2022)
                    ->orderBy('nc.numero', 'ASC')
                    ->distinct('nc.numero')
                    ->getQuery()
                    ->getResult();

                if ($newCourriers) {

                    foreach ($newCourriers as $key => $newCourrier) {
                        $docCourrier = $this->getCourrier($newCourrier->getDocNo());
                        $newSortant = new Sortant;
                        $newSortant->setRaisonSocial($docCourrier->rs);
                        $newSortant->setNif($docCourrier->nif);
                        $newSortant->setTitre($docCourrier->titre);
                        $newSortant->setObjetCourrier($docCourrier->objet);
                        $newSortant->setNumeroCourrier($docCourrier->getNumero());
                        $newSortant->setAuteur($this->getUser());
                        $newSortant->setUpdatedAt(new \DateTime());
                        $newSortant->setCreatedAt($docCourrier->createdDate);
                        $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newSortant->setStatus('Nouveau');
                        $newSortant->setPriority('Normal');
                        $newSortant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newSortant->setCourrierId($docCourrier->getDocNo());
                        $newSortant->setService($user->getService());
                        $newSortant->setYearCourr($docCourrier->getYearCourr());
                        $newSortant->dispatch = 'Dispatch';
                        $newSortant->setAttribution(null);
                        $newSortant->setCommentaires($docCourrier->commentaires);

                        $em->persist($newSortant);
                        $em->flush();
                    }
                    $courrierSortantPagination = $this->refreshSortant($request);

                    $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->SetAuthor('IDS');
                    $pdf->SetTitle(('Courriers sortants'));
                    $pdf->SetSubject('Courriers sortants');
                    $pdf->setFontSubsetting(true);
                    $pdf->SetFont('helvetica', '', 11, '', true);
                    //$pdf->SetMargins(20,20,40, true);
                    $pdf->AddPage();

                    $filename = 'Courriers Sortants';
                    $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                        'courriers' => $courrierSortantPagination,
                        'date_du'   => $request->query->get('date_du'),
                        'date_au'   => $request->query->get('date_au'),
                        'isChefSAI' => $isChefSAI,
                        'attributions' => $attributions,
                        'isChefDeService' => $isChefDeService,
                        'isMembreSAI' => $isMembreSAI,
                        'isMembreDirection' => $isMembreDirection,
                        'isChefDeDirection' => $isChefDeDirection,
                        'userServiceId' => $userServiceId,
                        'isInspecteur' => $isInspecteur,
                        'sectorActs' => $sectorActs,
                        'usersService' => $responsableQuery,
                        'nifFilter' => $request->query->get('nif'),
                        'rsFilter' => $request->query->get('rs'),
                        'nomBynumCourier' => $nomBynumCourier,
                        'attributionList' => $attributionList,
                        'listAssigne' => false
                    ));
                    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    $pdf->Output($filename . ".pdf", 'I');
                }

                $courrierSortantPagination = $this->refreshSortant($request);

                $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers sortants'));
                $pdf->SetSubject('Courriers sortants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('helvetica', '', 8, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers sortants';
                $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $date_du,
                    'date_au'   => $date_au,
                    'attributions' => $attributions,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'isChefDeDirection' => $isChefDeDirection,
                    'userServiceId' => $userServiceId,
                    'isInspecteur' => $isInspecteur,
                    'sectorActs' => $sectorActs,
                    'usersService' => $responsableQuery,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false
                ));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->Output($filename . ".pdf", 'I');
            } else {
                $documentsQuery = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('e');
                $documentsQuery
                    ->where('e.typeCourrier LIKE :typeCourrier')
                    ->setParameter('typeCourrier', "E")
                    ->andWhere('e.yearCourr LIKE :yearCourr')
                    ->setParameter('yearCourr', $yearCourr)
                    ->orderBy('e.numero', 'ASC')
                    ->distinct('e.numero');

                if ($documentsQuery) {

                    $documentsQuery->getQuery()->getResult();
                    foreach ($documentsQuery->getQuery()->getResult() as $key => $docCourrier) {
                        $courrierInfos = $this->getCourrier($docCourrier->getDocNo());
                        $newSortant = new Sortant;
                        $newSortant->setRaisonSocial($docCourrier->rs);
                        $newSortant->setNif($docCourrier->nif);
                        $newSortant->setAuteur($this->getUser());
                        $newSortant->setUpdatedAt(new \DateTime());
                        $newSortant->setCreatedAt($courrierInfos->createdDate);
                        $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newSortant->setStatus('Nouveau');
                        $newSortant->setPriority('Normal');
                        $newSortant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newSortant->setCourrierId($docCourrier->getDocNo());
                        $newSortant->addService($user->getService());
                        $newSortant->setYearCourr($docCourrier->getYearCourr());
                        $newSortant->setTitre($docCourrier->titre);
                        $newSortant->setObjet($docCourrier->objet);
                        $newSortant->setNumeroCourrier($docCourrier->getNumero());
                        $newSortant->dispatch = 'Dispatch';
                        $newSortant->setCommentaires($docCourrier->commentaires);

                        $em->persist($newSortant);
                        $em->flush();
                    }

                    $courrierSortantPagination = $this->refreshSortant($request);
                    $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->SetAuthor('IDS');
                    $pdf->SetTitle(('Courriers sortants'));
                    $pdf->SetSubject('Courriers sortants');
                    $pdf->setFontSubsetting(true);
                    $pdf->SetFont('helvetica', '', 11, '', true);
                    //$pdf->SetMargins(20,20,40, true);
                    $pdf->AddPage();

                    $filename = 'Courriers sortants';
                    $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                        'courriers' => $courrierSortantPagination,
                        'attributions' => $attributions,
                        'date_du'   => $request->query->get('date_du'),
                        'date_au'   => $request->query->get('date_au'),
                        'isChefSAI' => $isChefSAI,
                        'attributions' => $attributions,
                        'isChefDeService' => $isChefDeService,
                        'isMembreSAI' => $isMembreSAI,
                        'isMembreDirection' => $isMembreDirection,
                        'isChefDeDirection' => $isChefDeDirection,
                        'isInspecteur' => $isInspecteur,
                        'sectorActs' => $sectorActs,
                        'userServiceId' => $userServiceId,
                        'usersService' => $responsableQuery,
                        'nomBynumCourier' => $nomBynumCourier,
                        'attributionList' => $attributionList,
                        'listAssigne' => false
                    ));
                    $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    $pdf->Output($filename . ".pdf", 'I');
                }

                $courrierSortantPagination = $this->refreshSortant($request);
                $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers sortants'));
                $pdf->SetSubject('Courriers sortants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('arial', '', 11, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers sortants';
                $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'attributions' => $attributions,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'isChefDeDirection' => $isChefDeDirection,
                    'userServiceId' => $userServiceId,
                    'isInspecteur' => $isInspecteur,
                    'sectorActs' => $sectorActs,
                    'usersService' => $responsableQuery,
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false
                ));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->Output($filename . ".pdf", 'I');
            }
            if ($date_du && $date_au) {
                $documentsQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au);
            }

            $courrierSortantPagination = $this->refreshSortant($request);

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

            else if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) {
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

            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

                $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                    ->where('e.service = :service')
                    ->setParameter('service', $user->getService())
                    ->distinct('e.numero')
                    ->getQuery();

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQuery,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers sortants'));
                $pdf->SetSubject('Courriers sortants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('arial', '', 11, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers sortants';

                $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                    'courriers' => $sortants,
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
                    'isInspecteur' => $isInspecteur,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false
                ));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->Output($filename . ".pdf", 'I');
            }

            $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetAuthor('IDS');
            $pdf->SetTitle(('Courriers sortants'));
            $pdf->SetSubject('Courriers sortants');
            $pdf->setFontSubsetting(true);
            $pdf->SetFont('arial', '', 11, '', true);
            //$pdf->SetMargins(20,20,40, true);
            $pdf->AddPage();

            $filename = 'Courriers sortants';
            $html = $this->render('DBundle:Sortant:listpdf.html.twig', array(
                'courriers' => $courrierSortantPagination,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'attributions' => $attributions,
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isMembreSAI' => $isMembreSAI,
                'isMembreDirection' => $isMembreDirection,
                'isChefDeDirection' => $isChefDeDirection,
                'userServiceId' => $userServiceId,
                'isInspecteur' => $isInspecteur,
                'sectorActs' => $sectorActs,
                'usersService' => $responsableQuery,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'listAssigne' => false
            ));
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $pdf->Output($filename . ".pdf", 'I');
    }

    public function getCourrier($courrier_docNo)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $sai = $em->getRepository(SaiSetting::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );

        $query = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('d')
            ->where('d.docNo = :docNo')
            ->setParameter('docNo', $courrier_docNo)
            ->getQuery();

        $docCourrier = $query->getOneOrNullResult();

        if ($docCourrier) {
            $docCourrierLastObservation = $em->getRepository(SortantObservation::class)->findOneBy(array('createdAt' => $docCourrier->getDelegationDate()));
            if ($docCourrierLastObservation) {
                $docCourrier->setService($docCourrierLastObservation->getService());
            }

            $courrierTitre = $sigtas_em->getRepository(DocCourrierTitre::class)->findOneBy(
                array('id' => $docCourrier->getDocCourrierTitreNo())
            );
            if ($courrierTitre) {
                $docCourrierTitre = $courrierTitre->getDocCourrierTitre();
            } else {
                $docCourrierTitre = "-";
            }

            $courrierObjet = $sigtas_em->getRepository(DocCourrierObjet::class)->findOneBy(
                array('id' => $docCourrier->getDocCourrierObjectNo())
            );
            if ($courrierObjet) {
                $docCourrierObjet = $courrierObjet->getDocCourrierObjet();
            } else {
                $docCourrierObjet = "-";
            }
            $document = $sigtas_em->getRepository(Document::class)->findOneBy(
                array('docNo' => $docCourrier->getDocNo())
            );
            if ($document) {
                $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                    array('taxPayerNo' => $document->getTaxPayerNo())
                );
                $documentCreatedDate = $document->getCreatedDate();
                $documentSubject = $document->getDocSubject();
                if ($sigtasInfos) {
                    $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                        array('nif' => $sigtasInfos->getNif())
                    );

                    if ($nifInfos) {
                        $documentNif = $nifInfos->getNif();
                        $documentRs = $nifInfos->getRs();
                        $taxpayer = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                            'nif' => $documentNif
                        ]);
                        if ($taxpayer != null) {
                            $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                                'taxPayerNo' => $taxpayer->taxPayerNo
                            ]);
                            /*
                            if($enterprise)
                            {
                                $taxpayer->secteurActivite = $enterprise->secteurActivite->sectorActDesc;
                            }else{
                                $taxpayer->secteurActivite = '-'; 
                            }
                            */
                            $documentSensitive = $taxpayer->sensitive;
                            $documentRegimeFiscal = $taxpayer->regimeFiscal;
                        } else {
                            $documentSensitive = '-';
                            $documentSecteurActivite = '-';
                            $documentRegimeFiscal = '-';
                        }
                    } else {
                        $documentNif = '-';
                        $documentRs = '-';
                        $documentSensitive = '-';
                        $documentSecteurActivite = '-';
                        $documentRegimeFiscal = '-';
                    }
                }
                $document->rs = $documentRs;
                $document->nif = $documentNif;
                $document->sensitive = $documentSensitive;
                $document->regimeFiscal = $documentRegimeFiscal;
                $document->commentaires = $documentSubject;
            } else {
                $documentNif =  '-';
                $documentRs = '-';
                $documentSensitive = '-';
                $documentSecteurActivite = '-';
                $documentRegimeFiscal = '-';
                $documentCreatedDate = null;
                $documentSubject = null;
            }
            $docCourrier->createdDate = $documentCreatedDate;
            $docCourrier->nif = $documentNif;
            $docCourrier->rs = $documentRs;
            $docCourrier->titre = $docCourrierTitre;
            $docCourrier->objet = $docCourrierObjet;
            $docCourrier->sensitive = $documentSensitive;
            $docCourrier->secteurActivite = $documentRegimeFiscal;
            $docCourrier->dispatch = 'Dispatch';
            $docCourrier->commentaires = $documentSubject;
            $docCourrierSortant = $em->getRepository(Sortant::class)->findOneBy(array('courrierId' => $docCourrier->getDocNo()));
            if ($docCourrierSortant) {
                $docCourrier->setPriority('Normal');
                $docCourrier->setStatus($docCourrierSortant->getStatus());
                $docCourrier->attribution = $docCourrierSortant->getAttribution();
                $sortantService_not_sai = false;
                $leChef = $sai->getService();
                foreach ($docCourrierSortant->getServices() as $service) {
                    if ($service->getNom() != $leChef) {
                        $sortantService_not_sai = true;
                        $leChef = $service->getNom();
                    }
                }
                if ($docCourrierSortant->getGestionnaires()) {
                    $docCourrier->setResponsable($leChef);
                } elseif ($docCourrierSortant->getServices() &&  $sortantService_not_sai) {
                    $docCourrier->setResponsable($leChef);
                } else {
                    $docCourrier->setResponsable($docCourrierSortant->getAuteur());
                }
            }
        }
        return $docCourrier;
    }

    public function showAction(DocCourrier $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $isSystemUser = ($user->getId() == 89) ? true : false;
        $courrier->setObservationContent('ids');
        $courrierDocNo = $courrier->getDocNo();
        $docCourrier = $this->getCourrier($courrierDocNo);
        $docCourrierSortant = $em->getRepository(Sortant::class)->findOneBy(array('courrierId' => $courrierDocNo));
        $courrierIdAuto = $docCourrierSortant->getId();
        if ($sai) {
            $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        }
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $isUserConcerned = false;
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;

        foreach ($docCourrierSortant->getServices() as $service) {
            if ($service->getNom() == $user->getService()) {
                $isUserConcerned = true;
            }
        }
        $defaultData = ['message' => 'Type your message here'];
        $defaultStatus = "Nouveau";
        $defaultPriority = "Normal";
        $doc = $em->getRepository('DBundle:SortantObservation');
        $docCourrierObseration = $doc->findByCourrier($courrierDocNo);

        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat) {
                switch ($stat) {
                    case 'Transmis':
                        if (!$isChefDeService && !$isInspecteur) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierSortant->setStatus($stat);
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-blue">' . $stat . '</span>');
                        $em->persist($observation);
                        $em->flush();
                        break;

                    case 'Assigné':
                        if (!$isChefDeService && !$isInspecteur) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierSortant->setStatus($stat);
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $observation = new SortantObservation;
                        $observation->setStatus('Assigné');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                        $em->persist($observation);
                        $em->flush();
                        break;

                    case 'Traité':
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setStatus('Traité');
                        $observation = new SortantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">Traité</span>');
                        $em->persist($observation);

                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'traite' => 0
                        ]);
                        if ($dispatch) {
                            $dispatch->setTraite(true);
                        }
                        $em->flush();

                        $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->getId()));
                        if($userAssigne)
                        {
                            $userAssigne->setNbrecourrier($userAssigne->getNbrecourrier()-1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }

                        break;
                    case 'Non Traité':
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setStatus('Non Traité');
                        $observation = new SortantObservation;
                        $observation->setStatus('Non Traité');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">Non Traité</span>');
                        $em->persist($observation);
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'traite' => 0
                        ]);
                        if ($dispatch) {
                            $dispatch->setTraite(true);
                        }
                        $em->flush();

                        $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->getId()));
                        if($userAssigne)
                        {
                            $userAssigne->setNbrecourrier($userAssigne->getNbrecourrier()-1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }
                        break;
                    case 'Clôturé':
                        if (!$isChefDeService && !$isInspecteur) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierSortant->setStatus($stat);
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $observation = new SortantObservation;
                        $observation->setStatus('Clôturé');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->getId()));
                        if($userAssigne)
                        {
                            $obseGets = $em->getRepository(SortantObservation::class)->findBy(['sortantIdAuto' => $docCourrierSortant->getId()]);
                            $userGetok =[];
                            foreach( $obseGets as  $user){
                              
                                if($user->getDispatch() == "1"){
                                    if(!in_array($user->getUser(),$userGetok)){
                                        array_push($userGetok,$user->getUser());
                                    } 
                                }
                                                               
                            }

                            foreach($userGetok as $user){
                                $user->setNbrecourrier($user->getNbrecourrier()-1);
                            }
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }

                        return $this->redirectToRoute('list_sortant');

                        break;
                }
                if ($isChefDeDirection or $isChefSAI or $isChefDeService or $isSystemUser)
                {
                    return $this->redirectToRoute('list_sortant', []);
                }else
                {
                    return $this->redirectToRoute('list_sortant_assigne', []);
                }
            }
        }

        if (!$docCourrierObseration) {
            $delay = 0;
            header("Refresh: $delay;");
            $observation = new SortantObservation;
            $observation->setStatus('Nouveau');
            $observation->setService($user->getService());
            $observation->setMessage('Nouveau courrier à dispatcher . . .');
            $observation->setUser($this->getUser());
            $observation->setSortantIdAuto($courrierIdAuto);
            $observation->setCourrier($courrierDocNo);
            $observation->setCreatedAt(new \DateTime);
            $em->persist($observation);
            $em->flush();
            if ($request->request->get('dispatch'))
            {
                foreach ($request->request->get('dispatch') as $service)
                {
                    $dispatchingService = $service;
                    $sortantService = $em->getRepository(Service::class)->findOneBy(array('nom' => $dispatchingService));
                    if ($sortantService && $isChefSAI && $dispatchingService) {
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $docCourrierSortant->setTraitementDate(null);
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $docCourrierSortant->addService($sortantService);
                        $docCourrierSortant->setStatus('Transmis');
                        $observation = new SortantObservation;
                        $observation->setDispatch('1');
                        $observation->setStatus('Transmis');
                        $observation->setService($sortantService);
                        $observation->setUser($sortantService->getChef());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($docCourrierSortant);
                        $em->persist($observation);
                        $em->flush();

                        $userDispatch = $em->getRepository(User::class)->findOneBy(array('id' => $sortantService->getChef()->getId()));
                        if($userDispatch)
                        {
                            $userDispatch->setNbrecourrier($userDispatch->getNbrecourrier()+1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }
                
                    }

                    if ($sortantService && $isChefDeDirection && $dispatchingService) {
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $docCourrierSortant->setTraitementDate(null);
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $docCourrierSortant->addService($sortantService);
                        $docCourrierSortant->setStatus('Transmis');
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($sortantService);
                        $observation->setUser($sortantService->getChef());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($docCourrierSortant);
                        $em->persist($observation);
                        $em->flush();

                        $userDispatch = $em->getRepository(User::class)->findOneBy(array('id' => $sortantService->getChef()->getId()));
                        if($userDispatch)
                        {
                            $userDispatch->setNbrecourrier($userDispatch->getNbrecourrier()+1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }
                
                    }

                }
               
            
                return $this->redirectToRoute('list_sortant_dispatch', ['page' => $_SESSION['page']]);
                
                $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                    'docNo' => $courrierDocNo,
                    'informative' => false
                ]);
                if (!$dispatch) {
                    $dispatch = new CourrierDispatching();
                    $dispatch->setTraite(false);
                    $dispatch->setInformative(false);
                    $dispatch->setCloturer(false);
                    if (!($sortantService->getChef())) {
                        $delay = 0;
                        header("Refresh: $delay;");
                        $dispatch->setGestionnaire($sortantService->getChef());
                        $delay = 0;
                        header("Refresh: $delay;");
                    }
                    $dispatch->setDocNo($docCourrierSortant->getCourrierId());
                    $dispatch->setService($sortantService);
                    $em->persist($dispatch);
                }

                $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->redirectToRoute('list_sortant');
            }
        }

        if ($docCourrierObseration) {
            if (!$isChefDeService && !$isChefSAI && !$isChefDeDirection && !$isInspecteur)
            {
                if ($request->getMethod() == 'GET') {
                    $stat = $request->query->get('changestatus');
                    if ($stat == 'Traité') {
                        $docCourrierSortant->setUpdatedAt(new \DateTime);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $docCourrierSortant->setTraitementDate(new \DateTime);
                        $observation = new SortantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($docCourrierSortant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($docCourrierSortant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
                        $em->persist($observation);
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'traite' => 0
                        ]);
                        if ($dispatch) {
                            $dispatch->setTraite(true);
                        }
                        $em->flush();
                        return $this->redirectToRoute('list_sortant');
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();
                $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);
                return $this->render('DBundle:Sortant:show.html.twig', array(
                    'courrier' => $courrier,
                    'sortant' => $docCourrierSortant,
                    'observations' => $observations,
                    'services' => $services,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
            ));
            }

            if ($isChefSAI && $request->getMethod() == 'GET' or $request->request->get('dispatch')) {
                $form = $this->createFormBuilder($defaultData)
                    ->add('service', EntityType::class, [
                        'placeholder' => 'Choisissez',
                        'class' => Service::class,
                        'choice_label' => 'nom',
                        'multiple' => false,
                        'expanded' => false,
                    ])
                    ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    if (!$isChefSAI) {
                        throw $this->createNotFoundException('Seul le chef du Service Accueil et Information peut dispatcher les courriers...');
                    }
                    $courrier_service = $courrier->getService();
                    if (!$courrier_service || $courrier_service && $isChefSAI) {
                        $serviceName = $request->request->get('form')['service'];
                        $service = $em->getRepository(Service::class)->find($serviceName);
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'informative' => false
                        ]);
                        if (!$dispatch) {
                            $dispatch = new CourrierDispatching();
                            $dispatch->setTraite(false);
                            $dispatch->setInformative(false);
                            $dispatch->setCloturer(false);
                            $dispatch->setGestionnaire($service->getChef());
                            $dispatch->setDocNo($docCourrierSortant->getDocNo());
                            $dispatch->setService($service);
                            $em->persist($dispatch);
                        }
                        $courrier->setService($courrier_service);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $courrier->setStatus('Transmis');
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $courrier->setService($service);
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($service);
                        $observation->setUser($service->getChef());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();
                        return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                    } else {
                        return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                if ($request->request->get('dispatch')) {
                    foreach ($request->request->get('dispatch') as $service) {
                        $dispatchingService = $service;
                        $sortantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));
                        if ($sortantService) {
                            $courrier_service = $courrier->getService();
                            if (!$courrier_service || $courrier_service && $isChefSAI) {
                                $courrier->setService($courrier_service);
                                $courrier->setDelegationDate(new \DateTime);
                                $docCourrierSortant->setTraitementDate(null);
                                $courrier->setStatus('Transmis');
                                $observation = new SortantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($user->getService());
                                $observation->setUser($this->getUser());
                                $observation->setSortantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                                $em->persist($observation);
                                $em->flush();

                                $docCourrierSortant->addService($sortantService);
                                $docCourrierSortant->setStatus('Transmis');
                                $observation = new SortantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($sortantService);
                                $observation->setUser($sortantService->getChef());
                                $observation->setSortantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Action requise :  ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                                $em->persist($docCourrierSortant);
                                $em->persist($observation);
                                $em->flush();
                            }
                        } else {
                            $sortantService = $user->getService();
                        }
                    }
                }

                if ($request->getMethod() == 'POST') {
                    $stat = $request->query->get('changestatus');
                    if ($stat != 'Clôturé') {
                        $observation = new SortantObservation;
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $formulaire_observation = $this->createForm(SortantObservationType::class, $observation);
                        $formulaire_observation->handleRequest($request);

                        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                            $courrier->setUpdatedAt(new \DateTime);
                            $em->persist($observation);
                            $em->flush();
                            return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                        }
                    }
                }

                $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                $services = $em->getRepository(Service::class)->findAll();
                return $this->render('DBundle:Sortant:show.html.twig', array(
                    'courrier' => $courrier,
                    'observations' => $observations,
                    'sortant' => $docCourrierSortant,
                    'services' => $services,
                    'form' => $form->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                ));
            }

            if ($isChefDeDirection && $request->getMethod() == 'GET' or $request->request->get('dispatch')) {
                $form = $this->createFormBuilder($defaultData)
                    ->add('service', EntityType::class, [
                        'placeholder' => 'Choisissez',
                        'class' => Service::class,
                        'choice_label' => 'nom',
                        'multiple' => false,
                        'expanded' => false,
                    ])
                    ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    if (!$isChefDeDirection) {
                        throw $this->createNotFoundException('Seul le chef de Direction peut dispatcher les courriers...');
                    }
                    $courrier_service = $courrier->getService();
                    if (!$courrier_service || $courrier_service && $isChefDeDirection) {
                        $serviceName = $request->request->get('form')['service'];
                        $service = $em->getRepository(Service::class)->find($serviceName);
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'informative' => false
                        ]);
                        if (!$dispatch) {
                            $dispatch = new CourrierDispatching();
                            $dispatch->setTraite(false);
                            $dispatch->setInformative(false);
                            $dispatch->setCloturer(false);
                            $dispatch->setGestionnaire($service->getChef());
                            $dispatch->setDocNo($docCourrierSortant->getDocNo());
                            $dispatch->setService($service);
                            $em->persist($dispatch);
                        }
                        $courrier->setService($courrier_service);
                        $docCourrierSortant->setDelegationDate(new \DateTime);
                        $courrier->setStatus('Transmis');
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $courrier->setService($service);
                        $observation = new SortantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($service);
                        $observation->setUser($service->getChef());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();
                        return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                    } else {
                        return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                if ($request->request->get('dispatch')) {
                    foreach ($request->request->get('dispatch') as $service) {
                        $dispatchingService = $service;
                        $sortantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));
                        if ($sortantService) {
                            $courrier_service = $courrier->getService();
                            if (!$courrier_service || $courrier_service && $isChefDeDirection) {
                                $courrier->setService($courrier_service);
                                $courrier->setDelegationDate(new \DateTime);
                                $docCourrierSortant->setTraitementDate(null);
                                $courrier->setStatus('Transmis');
                                $observation = new SortantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($user->getService());
                                $observation->setUser($this->getUser());
                                $observation->setSortantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                                $em->persist($observation);
                                $em->flush();

                                $docCourrierSortant->addService($sortantService);
                                $docCourrierSortant->setStatus('Transmis');
                                $observation = new SortantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($sortantService);
                                $observation->setUser($sortantService->getChef());
                                $observation->setSortantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Action requise :  ' . $sortantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                                $em->persist($docCourrierSortant);
                                $em->persist($observation);
                                $em->flush();
                            }
                        } else {
                            $sortantService = $user->getService();
                        }
                    }
                }

                if ($request->getMethod() == 'POST') {
                    $stat = $request->query->get('changestatus');
                    if ($stat != 'Clôturé') {
                        $observation = new SortantObservation;
                        $observation->setUser($this->getUser());
                        $observation->setSortantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $formulaire_observation = $this->createForm(SortantObservationType::class, $observation);
                        $formulaire_observation->handleRequest($request);

                        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                            $courrier->setUpdatedAt(new \DateTime);
                            $em->persist($observation);
                            $em->flush();
                            return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                        }
                    }
                }

                $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                $services = $em->getRepository(Service::class)->findAll();
                return $this->render('DBundle:Sortant:show.html.twig', array(
                    'courrier' => $courrier,
                    'observations' => $observations,
                    'sortant' => $docCourrierSortant,
                    'services' => $services,
                    'form' => $form->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                ));
            }

            if ($isChefDeService && $request->request->get('gestionnaire') || $isInspecteur && $request->request->get('gestionnaire')) {                
                if ($request->getMethod() == 'POST') {
                    $priorityGet = $request->request->get('priority');
                    if ($request->request->get('gestionnaire')) {
                        
                        if (!$isChefDeService && !$isInspecteur) {
                            $this->addFlash('error', 'Seul le chef du service "' . $courrier->getService()->getNom() . '" peut attribuer ce courrier à un gestionnaire.');
                        }
                            $gestionnaireData = $request->request->get('gestionnaire');
                            $observationData = $request->request->get('observation');
                          
                            foreach ($request->request->get('gestionnaire') as $gestionnaire) {
                                $gestionnaireData = $gestionnaire;
                                $getUser = $em->getRepository(User::class)->find($gestionnaireData);
                               
                                $attributionDesc = $request->request->get('attribution');
                                $attributionMulti = $request->request->get('attibutionMulti');

                                if ($getUser) {
                                    $leChef = $sai->getService();
                                    foreach ($docCourrierSortant->getServices() as $service) {
                                        if ($service->getNom() != $leChef) {
                                            $sortantService_not_sai = true;
                                            $leChef = $service->getNom();
                                        }
                                    }
                                    $docCourrierSortant->setAttribution($attributionDesc);
                                    $sortant_id = $docCourrierSortant->getId();
                                    $user_id = $gestionnaireData;
                                    $em->getRepository(Sortant::class)->removeGestionnaire($user_id,$sortant_id);
                                    $docCourrierSortant->addGestionnaire($getUser);
                                    $docCourrierSortant->setUpdatedAt(new \DateTime);
                                    $docCourrierSortant->setPriority('Normal');
                                    $em->persist($docCourrierSortant);
                                    $observation = new SortantObservation;
                                    $observation->setService($this->getUser()->getService()->getNom());
                                    $observation->setStatus('Assigné');
                                    $leAttribution = "";
                                    foreach ($attributionMulti as $key => $attribution) {
                                        if ($gestionnaireData == $key) {
                                            $leAttribution = $attribution;
                                        }
                                    }

                                    $observation->setAttribution($leAttribution);
                                    $lObservation = "";
                                    foreach ($observationData as $key => $obs) {
                                        if ($gestionnaireData == $key) {
                                            $lObservation = $obs;
                                        }
                                    }
                                    $observation->setObservations($lObservation);

                                    $observation->setUser($this->getUser());
                                    $observation->setSortantIdAuto($docCourrierSortant->getId());
                                    $observation->setCourrier($courrier->getDocNo());
                                    $observation->setCreatedAt(new \DateTime);
                                    $observation->setMessage('a assigné ce courrier à ' . $getUser->getNom() . ' ' . $getUser->getPrenom()/*.'. Status courrier: <span class="badge bg-yellow">Assigné*/ . '</span>');
                                    $em->persist($observation);

                                    $observation = new SortantObservation;

                                    $observation->setService($this->getUser()->getService()->getNom());
                                    $observation->setStatus('En cours');
                                    $leAttribution = "";
                                    foreach ($attributionMulti as $key => $attribution) {
                                        if ($gestionnaireData == $key) {
                                            $leAttribution = $attribution;
                                        }
                                    }
                                    $observation->setAttribution($leAttribution);
                                    $lObservation = "";
                                    foreach ($observationData as $key => $obs) {
                                        if ($gestionnaireData == $key) {
                                            $lObservation = $obs;
                                        }
                                    }
                                    $observation->setObservations($lObservation);
                                    
                                    $observation->setUser($getUser);
                                    $observation->setSortantIdAuto($docCourrierSortant->getId());
                                    $observation->setCourrier($courrier->getDocNo());
                                    $observation->setCreatedAt(new \DateTime);
                                    $observation->setMessage('Traitement du courrier en cours');
                                    $em->persist($observation);
                                    $em->flush();

                                    $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                                        'docNo' => $courrier->getDocNo(),
                                        'gestionnaire' => $getUser
                                    ]);
                                    if (!$dispatch) {
                                        $dispatch = new CourrierDispatching();
                                        $dispatch->setTraite(false);
                                        $dispatch->setInformative(false);
                                        $dispatch->setCloturer(false);
                                        $dispatch->setGestionnaire($getUser);
                                        $dispatch->setDocNo($docCourrierSortant->getCourrierId());
                                        $dispatch->setService($getUser->getService());
                                        $em->persist($dispatch);
                                        $em->flush();
                                    }

                                    $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $getUser->getId()));
                                    if($userAssigne)
                                    {
                                        $userAssigne->setNbrecourrier($userAssigne->getNbrecourrier()+1);
                                        $em = $this->getDoctrine()->getManager();
                                        $em->flush();
                                    }

                                    $currentDispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                                        'docNo' => $courrier->getDocNo(),
                                        'traite' => 0
                                    ]);
                                    if ($currentDispatch) {
                                        $currentDispatch->setTraite(true);
                                        $em->flush();
                                    }
                                } else {
                                    $this->addFlash('error', 'Utilisateur introuvable.');
                                }
                            }
                            $this->addFlash('success', 'Enregistrer avec succès');
                            return $this->redirectToRoute('list_sortant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();
                $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->render('DBundle:Sortant:show.html.twig', array(
                    'courrier' => $courrier,
                    'sortant' => $docCourrierSortant,
                    'observations' => $observations,
                    'services' => $services,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                ));
            }
        }
        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat != 'Clôturé') {
                $observation = new SortantObservation;
                $observation->setUser($this->getUser());
                $observation->setSortantIdAuto($docCourrierSortant->getId());
                $observation->setCourrier($courrier->getDocNo());
                $observation->setCreatedAt(new \DateTime);
                $formulaire_observation = $this->createForm(SortantObservationType::class, $observation);
                $formulaire_observation->handleRequest($request);

                if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                    $courrier->setUpdatedAt(new \DateTime);
                    $em->persist($observation);
                    $em->flush();
                    return $this->redirectToRoute('show_sortant', ['courrier' => $courrier->getDocNo()]);
                }
            }
        }
        $services = $em->getRepository(Service::class)->findAll();
        $observations =  $em->getRepository(SortantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

        return $this->render('DBundle:Sortant:show.html.twig', array(
            'courrier' => $courrier,
            'sortant' => $docCourrierSortant,
            'observations' => $observations,
            'services' => $services,
            'isChefDeService' => $isChefDeService,
            'isChefSAI' => $isChefSAI,
            'isChefDeDirection' => $isChefDeDirection,
            'isMembreDirection' => $isMembreDirection,
            'isUserConcerned' => $isUserConcerned,
            'isInspecteur' => $isInspecteur,
        ));
    }

    public function newCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new SortantObjet;

        $form = $this->createForm(SortantObjetType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('list_sortant_cat');
        }

        return $this->render('DBundle:Sortant:categorie\new_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(SortantObjet::class)->createQueryBuilder('c')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $categorie = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Sortant:categorie\list_cat.html.twig', array(
            'categories' => $categorie
        ));
    }

    public function editCatAction(Request $request, SortantObjet $nature)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SortantObjetType::class, $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('list_sortant_cat');
        }

        return $this->render('DBundle:Sortant:categorie\edit_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function getCommentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $inputs =  $em->getRepository(Sortant::class)->findOneBy([
            'courrierId' => '2128115'
        ]);

        foreach ($inputs as $key => $input) {
            $commentaires = $sigtas_em->getRepository(Document::class)->findOneBy(
                array('docNo' => $input->getNumeroCourrier())
            );
            if ($commentaires) {
                $comments = $commentaires->getComments();
            }
        }
    }

    public function initializeSortantAction()
    {
        $em = $this->getDoctrine()->getManager();
        $inputs =  $em->getRepository(Sortant::class)->findAll();
        foreach ($inputs as $key => $input) {
            $input->setPriority('Normal');
            $input->setStatus('Nouveau');
            $input->setService(null);
            $input->setDelegationDate(null);
            $input->setTraitementDate(null);
            $input->setAttribution(null);
            $input->setNumeroCourrier($input->getId());
            $em->persist($input);
            $em->flush();
        }
        return $this->redirectToRoute('list_sortant');
    }

    public function autoCompleteNif2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $sortants = $em->getRepository(Sortant::class)->getByNif($nif);
            $output = [];
            foreach ($sortants as $sortant) {
                $createdAt = date_format($sortant->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $sortant->getNif();
                $temp_array['raisonSoncial'] = $sortant->getRaisonSocial();
                $temp_array['useIt'] = $createdAt . ' - ' . $sortant->getNif() . ' - ' . $sortant->getRaisonSocial() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_sortant');
    }
    
    public function autoCompleteRsoc2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $sortants = $em->getRepository(Sortant::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($sortants as $sortant) {
                $createdAt = date_format($sortant->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $sortant->getNif();
                $temp_array['raisonSoncial'] = $sortant->getRaisonSocial();
                $temp_array['useIt'] = $createdAt . ' - ' . $sortant->getNif() . ' - ' . $sortant->getRaisonSocial() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_sortant');
    }

    public function listDispatchAction(Request $request)
    {
        $_SESSION['page'] = $request->query->get('page');
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $isDispatch = true;

        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        $sortantCheck = $em->createQueryBuilder();
        $sortantCheck->select('count(sortant.numeroCourrier)');
        $sortantCheck->from(Sortant::class, 'sortant');
        $sortantCount = $sortantCheck->getQuery()->getSingleScalarResult();

        for ($i = 0; $i < $sortantCount; $i++) {
            array_push($nomBynumCourier, " ");
            array_push($attributionList, " ");
        }
        if ($sortantCount > 0) {
            $sortantLast = $em->createQueryBuilder()
                ->select('MAX(le.numeroCourrier)')
                ->from(Sortant::class, 'le')
                ->where('le.yearCourr = 2022')
                ->getQuery()
                ->getSingleScalarResult();
                if (!$sortantLast)
                {
                    $sortantLast = 0;
                }
            $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                ->where('nc.numero > :lastNumero')
                ->setParameter('lastNumero', $sortantLast)
                ->andWhere('nc.typeCourrier LIKE :typeCourrier')
                ->setParameter('typeCourrier', "E")
                ->andWhere('nc.yearCourr LIKE :yearCourr')
                ->setParameter('yearCourr', 2022)
                ->orderBy('nc.numero', 'ASC')
                ->distinct('nc.numero')
                ->getQuery()
                ->getResult();
            if ($newCourriers) {
                foreach ($newCourriers as $key => $newCourrier) {
                    $docCourrier = $this->getCourrier($newCourrier->getDocNo());
                    $newSortant = new Sortant;
                    $newSortant->setRaisonSocial($docCourrier->rs);
                    $newSortant->setNif($docCourrier->nif);
                    $newSortant->setTitre($docCourrier->titre);
                    $newSortant->setObjetCourrier($docCourrier->objet);
                    $newSortant->setNumeroCourrier($docCourrier->getNumero());
                    $newSortant->setAuteur($this->getUser());
                    $newSortant->setUpdatedAt(new \DateTime());
                    $newSortant->setCreatedAt($docCourrier->createdDate);
                    $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());
                    $newSortant->setStatus('Nouveau');
                    $newSortant->setPriority('Normal');
                    $newSortant->setObservationContent('Nouveau courrier à dispatcher . . .');
                    $newSortant->setCourrierId($docCourrier->getDocNo());
                    $newSortant->addService($user->getService());
                    $newSortant->setYearCourr($docCourrier->getYearCourr());
                    $newSortant->dispatch = 'Dispatch';
                    $newSortant->setAttribution(null);
                    $newSortant->setCommentaires($docCourrier->commentaires);
                    $em->persist($newSortant);
                    $em->flush();
                    $sortant_id = $newSortant->getId();
                    $serviceId = $user->getService()->getId();
                    $em->getRepository(Sortant::class)->removeServiceSortant($serviceId, $sortant_id);
                }

                // il ne faut pas tout de suite procéder à l'affichage après extraction de nouveaux courriers
                // $courrierSortantPagination = $this->refreshSortant($request);
                // return $this->render('DBundle:Sortant:listDispatch.html.twig', array(
                //     'courriers' => $courrierSortantPagination,
                //     'date_du'   => $request->query->get('date_du'),
                //     'date_au'   => $request->query->get('date_au'),
                //     'isChefSAI' => $isChefSAI,
                //     'attributions' => $attributions,
                //     'isChefDeService' => $isChefDeService,
                //     'isMembreSAI' => $isMembreSAI,
                //     'isMembreDirection' => $isMembreDirection,
                //     'isChefDeDirection' => $isChefDeDirection,
                //     'userServiceId' => $userServiceId,
                //     'userId' => $userId,
                //     'isSystemUser' => $isSystemUser,
                //     'isInspecteur' => $isInspecteur,
                //     'sectorActs' => $sectorActs,
                //     'usersService' => $responsableQuery,
                //     'nifFilter' => $request->query->get('nif'),
                //     'rsFilter' => $request->query->get('rs'),
                //     'nomBynumCourier' => $nomBynumCourier,
                //     'attributionList' => $attributionList,
                //     'listAssigne' => false,
                //     'imprimer' => 'listDispatchAction',
                //     'categories' => $categories,
                // ));
            }
        }

        $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusnouveau')
        ->setParameter('statusnouveau', 'Nouveau')
        ->distinct('e.numero')
        ->addOrderBy('e.createdAt', 'DESC')
        ->addOrderBy('e.id', 'DESC');
        
        if ($date_du && $date_au) {
            $sortantQuery
                ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $sortantQuery
                ->andWhere('e.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $sortantQuery
                ->andWhere('e.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $sortantQuery
                ->andWhere('e.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }

        $sortantQuery->getQuery();
        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierSortantPagination = $this->refreshSortant($request);
        return $this->render('DBundle:Sortant:listDispatch.html.twig', array(
            'courriers' => $sortants,
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
            'imprimer' => 'listDispatchAction',
            'categories' => $categories,
        ));
    }
    
    public function listAllAction(Request $request)
    {
        $_SESSION['page'] = $request->query->get('page');
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();

        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $categorie = $request->query->get('categorie');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if ($isChefSAI || $isChefDeDirection || $isSystemUser) {
            $sortantCheck = $em->createQueryBuilder();
            $sortantCheck->select('count(sortant.numeroCourrier)');
            $sortantCheck->from(Sortant::class, 'sortant');
            $sortantCount = $sortantCheck->getQuery()->getSingleScalarResult();

            for ($i = 0; $i < $sortantCount; $i++) {
                array_push($nomBynumCourier, " ");
                array_push($attributionList, " ");
            }
            if ($sortantCount > 0) {
                $sortantLast = $em->createQueryBuilder()
                    ->select('MAX(le.numeroCourrier)')
                    ->from(Sortant::class, 'le')
                    ->where('le.yearCourr = 2022')
                    ->getQuery()
                    ->getSingleScalarResult();
                    if (!$sortantLast)
                    {
                        $sortantLast = 0;
                    }
                $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                    ->where('nc.numero > :lastNumero')
                    ->setParameter('lastNumero', $sortantLast)
                    ->andWhere('nc.typeCourrier LIKE :typeCourrier')
                    ->setParameter('typeCourrier', "E")
                    ->andWhere('nc.yearCourr LIKE :yearCourr')
                    ->setParameter('yearCourr', 2022)
                    ->orderBy('nc.numero', 'ASC')
                    ->distinct('nc.numero')
                    ->getQuery()
                    ->getResult();

                if ($newCourriers) {
                    foreach ($newCourriers as $key => $newCourrier) {
                        $docCourrier = $this->getCourrier($newCourrier->getDocNo());
                        $newSortant = new Sortant;
                        $newSortant->setRaisonSocial($docCourrier->rs);
                        $newSortant->setNif($docCourrier->nif);
                        $newSortant->setTitre($docCourrier->titre);
                        $newSortant->setObjetCourrier($docCourrier->objet);
                        $newSortant->setNumeroCourrier($docCourrier->getNumero());
                        $newSortant->setAuteur($this->getUser());
                        $newSortant->setUpdatedAt(new \DateTime());
                        $newSortant->setCreatedAt($docCourrier->createdDate);
                        $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newSortant->setStatus('Nouveau');
                        $newSortant->setPriority('Normal');
                        $newSortant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newSortant->setCourrierId($docCourrier->getDocNo());
                        $newSortant->addService($user->getService());
                        $newSortant->setYearCourr($docCourrier->getYearCourr());
                        $newSortant->dispatch = 'Dispatch';
                        $newSortant->setAttribution(null);
                        $newSortant->setCommentaires($docCourrier->commentaires);
                        $em->persist($newSortant);
                        $em->flush();
                        $sortant_id = $newSortant->getId();
                        $serviceId = $user->getService()->getId();
                        $em->getRepository(Sortant::class)->removeServiceSortant($serviceId, $sortant_id);
                    }

                    $this->setStatAction();

                    $courrierSortantPagination = $this->refreshSortant($request);
                    return $this->render('DBundle:Sortant:list.html.twig', array(
                        'courriers' => $courrierSortantPagination,
                        'date_du'   => $request->query->get('date_du'),
                        'date_au'   => $request->query->get('date_au'),
                        'isChefSAI' => $isChefSAI,
                        'attributions' => $attributions,
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
                        'imprimer' => 'listAllAction',
                        'categories' => $categories,
                    ));
                }

                $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:list.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $date_du,
                    'date_au'   => $date_au,
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
                    'imprimer' => 'listAllAction',
                    'categories' => $categories,
                ));
            } else {
                $documentsQuery = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('e');
                $documentsQuery
                    ->where('e.typeCourrier LIKE :typeCourrier')
                    ->setParameter('typeCourrier', "E")
                    ->andWhere('e.yearCourr LIKE :yearCourr')
                    ->setParameter('yearCourr', $yearCourr)
                    ->orderBy('e.numero', 'ASC')
                    ->distinct('e.numero');
            
                if ($documentsQuery) {
                    $documentsQuery->getQuery()->getResult();
                    foreach ($documentsQuery->getQuery()->getResult() as $key => $docCourrier) {
                        $courrierInfos = $this->getCourrier($docCourrier->getDocNo());
                        $newSortant = new Sortant;
                        $newSortant->setRaisonSocial($docCourrier->rs);
                        $newSortant->setNif($docCourrier->nif);
                        $newSortant->setAuteur($this->getUser());
                        $newSortant->setUpdatedAt(new \DateTime());
                        $newSortant->setCreatedAt($courrierInfos->createdDate);
                        $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newSortant->setStatus('Nouveau');
                        $newSortant->setPriority('Normal');
                        $newSortant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newSortant->setCourrierId($docCourrier->getDocNo());
                        $newSortant->addService($user->getService());
                        $newSortant->setYearCourr($docCourrier->getYearCourr());
                        $newSortant->setTitre($docCourrier->titre);
                        $newSortant->setObjet($docCourrier->objet);
                        $newSortant->setNumeroCourrier($docCourrier->getNumero());
                        $newSortant->dispatch = 'Dispatch';
                        $newSortant->setCommentaires($docCourrier->commentaires);
                        $em->persist($newSortant);
                        $em->flush();
                    }

                    $courrierSortantPagination = $this->refreshSortant($request);
                    return $this->render('DBundle:Sortant:list.html.twig', array(
                        'courriers' => $courrierSortantPagination,
                        'attributions' => $attributions,
                        'date_du'   => $request->query->get('date_du'),
                        'date_au'   => $request->query->get('date_au'),
                        'isChefSAI' => $isChefSAI,
                        'attributions' => $attributions,
                        'isChefDeService' => $isChefDeService,
                        'isMembreSAI' => $isMembreSAI,
                        'isMembreDirection' => $isMembreDirection,
                        'isChefDeDirection' => $isChefDeDirection,
                        'sectorActs' => $sectorActs,
                        'userServiceId' => $userServiceId,
                        'userId' => $userId,
                        'isSystemUser' => $isSystemUser,
                        'isInspecteur' => $isInspecteur,
                        'usersService' => $responsableQuery,
                        'nifFilter' => $request->query->get('nif'),
                        'rsFilter' => $request->query->get('rs'),
                        'nomBynumCourier' => $nomBynumCourier,
                        'attributionList' => $attributionList,
                        'listAssigne' => false,
                        'imprimer' => 'listAllAction',
                        'categories' => $categories,
                    ));
                }

                $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:list.html.twig', array(
                    'courriers' => $courrierSortantPagination,
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
                    'imprimer' => 'listAllAction',
                    'categories' => $categories,
                ));
            }
            if ($date_du && $date_au) {
                $documentsQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au);
            }

            $courrierSortantPagination = $this->refreshSortant($request);
            if ($sai->getService()->getChef()->getId() == $user->getId() or $user->getService()->getId() == '4') {
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

            else if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId() /*or $sortantDuService  */) {
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

            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

            if ($isChefDeService) {
                if ($gestionnaireId) {
                    $documentsQuery
                        ->andWhere('e.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaireId);
                }

                $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                    ->where('e.service = :service')
                    ->setParameter('service', $user->getService())
                    ->distinct('e.numero')
                    ->getQuery();

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQuery,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:list.html.twig', array(
                    'courriers' => $sortants,
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
                    'imprimer' => 'listAllAction',
                    'categories' => $categories,
                ));
            }

            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'imprimer' => 'listAllAction',
                'categories' => $categories,
            ));
        }else
        {
            $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
            ->Where('e.status <> :statusNouveau')
            ->setParameter('statusNouveau', 'Nouveau')
            ->distinct('e.numero')
            ->addOrderBy('e.createdAt', 'DESC')
            ->addOrderBy('e.id', 'DESC');
            
            if ($date_du && $date_au) {
                $sortantQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au);
            }
            if ($gestionnaireId) {
                $sortantQuery
                    ->andWhere('e.gestionnaire  = :gestionnaire')
                    ->setParameter('gestionnaire', $gestionnaireId);
            }
            if ($nifFilter) {
                $sortantQuery
                    ->andWhere('e.nif LIKE :nif')
                    ->setParameter('nif', '%' . $nifFilter . '%');
            }
            if ($rsFilter) {
                $sortantQuery
                    ->andWhere('e.raisonSocial LIKE :rs')
                    ->setParameter('rs', '%' . $rsFilter . '%');
            }
            $sortantQuery->getQuery();
            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQuery,
                $request->query->getInt('page', 1),
                20
            );
    
            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $sortants,
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
                'imprimer' => 'listAllAction',
                'categories' => $categories,
            ));
    
        }
    }

    public function listAssigneAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $isSystemUser = ($user->getId() == 89) ? true : false;
        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $userServiceId = $user->getService()->getId();
        $userId = $user->getId();
        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
 
        $sortantQuery = $em->getRepository(SortantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
        $sortantQueryc =[];
        $courrier = "";
        foreach( $sortantQuery as  $sortant){                
            $courrier = $sortant->getCourrier();
            array_push($sortantQueryc,$courrier);
        }
        $sortantQueryOk = $em->getRepository(Sortant::class)->findBy(
            ['courrierId'=> $sortantQueryc, 'status' => 'Transmis'],
            ['numeroCourrier' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $attributions = $em->getRepository(Attribution::class)->findAll();

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierSortantPagination = $this->refreshSortant($request);
        return $this->render('DBundle:Sortant:list.html.twig', array(
            'courriers' => $sortants,
            'gestionnaire' => $gestionnaire,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'sectorActs' => $sectorActs,
            'attributions' => $attributions,
            'isChefSAI' => false,
            'isChefDeService' => false,
            'isMembreSAI' => false,
            'usersService' => $responsableQuery,
            'isMembreDirection' => false,
            'isChefDeDirection' => false,
            'userServiceId' => $userServiceId,
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'userId' => $userId,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'nomBynumCourier' => $nomBynumCourier,
            'attributionList' => $attributionList,
            'dispatcher' => false,
            'listAssigne' => true,
            'imprimer' => 'listAssigneAction',
            'categories' => $categories,
        ));
    }    

    public function listPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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

        if ($isChefSAI || $isChefDeDirection || $isSystemUser || $isChefDeService) {            
            $sortantQuery = $em->getRepository(Service::class)
                            ->find($user->getService()->getId())
                            ->getSortant();            
            $sortantQueryOk =[];
            foreach( $sortantQuery as  $sortant){                
                if ($nifFilter) {
                    if($nifFilter == $sortant->getNif())
                    {
                        array_push($sortantQueryOk,$sortant);
                    }
                }else{
                    array_push($sortantQueryOk,$sortant);
                }
            }
        } else {
            $sortantQuery = $em->getRepository(SortantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
            $sortantQueryc =[];
            $courrier = "";
            foreach( $sortantQuery as  $sortant){                
                $courrier = $sortant->getCourrier();
                array_push($sortantQueryc,$courrier);
            }
            $sortantQueryOk = $em->getRepository(Sortant::class)->findBy(['courrierId'=> $sortantQueryc]);
        }
        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers sortants'));
        $pdf->SetSubject('Courriers sortants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers sortants';
        $html = $this->render('DBundle:Sortant:listPdf.html.twig', array(
            'courriers' => $sortants,
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'nomBynumCourier' => $nomBynumCourier,
            'attributionList' => $attributionList,
            'dispatcher' => false,
            'listAssigne' => false
        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    public function listDispatchPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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

        $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusnouveau')
        ->setParameter('statusnouveau', 'Nouveau')
        ->distinct('e.numero')
        ->orderBy('e.id', 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers sortants'));
        $pdf->SetSubject('Courriers sortants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers sortants';
        $html = $this->render('DBundle:Sortant:listDispatchPdf.html.twig', array(
            'courriers' => $sortants,
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'nomBynumCourier' => $nomBynumCourier,
            'attributionList' => $attributionList,
            'dispatcher' => false,
            'listAssigne' => false
        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    public function listAllPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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

        $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
        ->distinct('e.numero')
        ->orderBy('e.id', 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers sortants'));
        $pdf->SetSubject('Courriers sortants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers sortants';
        $html = $this->render('DBundle:Sortant:listAllPdf.html.twig', array(
            'courriers' => $sortants,
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'nomBynumCourier' => $nomBynumCourier,
            'attributionList' => $attributionList,
            'dispatcher' => false,
            'listAssigne' => false
        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I');
    }

    public function sortantExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
            
        $query = $em->getRepository(Service::class)->find($user->getService()->getId())->getSortant();
            
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Liste des courriers sortants")
            ->setSubject("Courriers sortants")
            ->setDescription("Ce fichier contient les courriers sortants")
            ->setKeywords("Sortant")
            ->setCategory("ids.xls");
        $count = 6;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTE DES COURRIERS ENTRANTS ');

        $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A3', 'LISTE DES COURRIERS ENTRANTS ');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A5', 'Numéro ')
            ->setCellValue('B5', 'NIF ')
            ->setCellValue('C5', 'Raison sociale ')
            ->setCellValue('D5', 'Objet ')
            ->setCellValue('E5', 'Commentaires ')
            ->setCellValue('F5', 'Reçu le ');

        foreach ($query as $query) {

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $query->getNumeroCourrier())
                ->setCellValue('B' . $count, $query->getNif())
                ->setCellValue('C' . $count, $query->getRaisonSocial())
                ->setCellValue('D' . $count, $query->getObjet())
                ->setCellValue('E' . $count, $query->getCommentaires())
                ->setCellValue('F' . $count, $query->getCreatedAt());
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        $phpExcelObject->setActiveSheetIndex(0);
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Liste des courriers sortants.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    public function addObsNonTraiteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $observation = new SortantObservation;
        $lObservation = $request->request->get("obsNonTraite");
        $idcourrier = $request->request->get("idcourrier");
        
        $docCourrierSortant = $em->getRepository(Sortant::class)->findOneBy(array('courrierId' => $idcourrier));
        $getUser = $em->getRepository(User::class)->find($this->getUser()->getId());
        $observation->setUser($getUser);
        $observation->setMessage("Le courrier n'a pas été traité. Lire l'observation");
        $observation->setCreatedAt(new \DateTime);
        $observation->setSortantIdAuto($docCourrierSortant->getId());
        $observation->setCourrier($idcourrier);
        $observation->setStatus('Non Traité');
        $observation->setService($this->getUser()->getService()->getNom());
        $observation->setObservations($lObservation);
        $docCourrierSortant->setStatus('Non Traité');
        $em->persist($docCourrierSortant);
        $em->persist($observation);
        $em->flush();
        return $this->redirectToRoute('list_sortant_assigne');
    }

    public function listNouveauAction(Request $request)
    {
        $_SESSION['page'] = $request->query->get('page');
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
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $attributions = $em->getRepository(Attribution::class)->findAll();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
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

        $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusTransmis')
        ->setParameter('statusTransmis', 'Transmis')
        ->distinct('e.numero')
        ->addOrderBy('e.createdAt', 'DESC')
        ->addOrderBy('e.id', 'DESC');
        
        if ($date_du && $date_au) {
            $sortantQuery
                ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $sortantQuery
                ->andWhere('e.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $sortantQuery
                ->andWhere('e.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $sortantQuery
                ->andWhere('e.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }
        $sortantQuery->getQuery();
        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        return $this->render('DBundle:Sortant:list.html.twig', array(
            'courriers' => $sortants,
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
            'imprimer' => 'listDispatchAction'
        ));
    }

    public function listAllServiceAction(Request $request)
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
            ->from(SortantObservation::class, 'le')
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
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        $documentsQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
            ->addOrderBy('e.createdAt','DESC')
            ->addOrderBy('e.numeroCourrier', 'DESC')
            ->distinct(true)
            ->getQuery();

        if ($isChefDeDirection || $isChefSAI || $isChefDeService || $isSystemUser) 
        {            
            $sortantQuery = $em->getRepository(Service::class)
                                ->find($user->getService()->getId())
                                ->getSortant();
            $sortantQueryOk =[];
            foreach( $sortantQuery as  $sortant){                
                if ($nifFilter) {
                    if($nifFilter == $sortant->getNif())
                    {
                        array_push($sortantQueryOk,$sortant);
                    }
                }else{
                    array_push($sortantQueryOk,$sortant);
                }
            }

            $sortantQueryc =[];
            $courrier = "";
            foreach( $sortantQueryOk as  $sortant){                
                $courrier = $sortant->getCourrierId();
                array_push($sortantQueryc,$courrier);
            }

            $sortantQueryOk = $em->getRepository(Sortant::class)->findBy(['courrierId'=> $sortantQueryc],['numeroCourrier' => 'DESC']);

            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQueryOk,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:list.html.twig', array(
                'courriers' => $sortants,
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
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));

        }
    }

    public function listAssigneAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $isSystemUser = ($user->getId() == 89) ? true : false;
        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(SortantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $userServiceId = $user->getService()->getId();
        $userId = $user->getId();
        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
 
        $sortantQuery = $em->getRepository(SortantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
        $sortantQueryc =[];
        $courrier = "";
        foreach( $sortantQuery as  $sortant){                
            $courrier = $sortant->getCourrier();
            array_push($sortantQueryc,$courrier);
        }
        $sortantQueryOk = $em->getRepository(Sortant::class)->findBy(
            ['courrierId'=> $sortantQueryc],
            ['numeroCourrier' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $sortants = $paginator->paginate(
            $sortantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $attributions = $em->getRepository(Attribution::class)->findAll();

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierSortantPagination = $this->refreshSortant($request);
        return $this->render('DBundle:Sortant:list.html.twig', array(
            'courriers' => $sortants,
            'gestionnaire' => $gestionnaire,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'sectorActs' => $sectorActs,
            'attributions' => $attributions,
            'isChefSAI' => false,
            'isChefDeService' => false,
            'isMembreSAI' => false,
            'usersService' => $responsableQuery,
            'isMembreDirection' => false,
            'isChefDeDirection' => false,
            'userServiceId' => $userServiceId,
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'userId' => $userId,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'nomBynumCourier' => $nomBynumCourier,
            'attributionList' => $attributionList,
            'dispatcher' => false,
            'listAssigne' => true,
            'imprimer' => 'listAssigneAction',
            'categories' => $categories,
        ));
    }    

    public function statCatAction(Request $request)
    {
        // $this->setStatAction();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(CategorieCourierSortant::class)->createQueryBuilder('c')->getQuery();
        $paginator  = $this->get('knp_paginator');
        $categorie = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('DBundle:Sortant:categorie\stat_cat.html.twig', array(
            'categories' => $categorie
        ));
    }

    public function setStatAction()
    {
        $em = $this->getDoctrine()->getManager();
        $allCat = $em->getRepository(CategorieCourierSortant::class)->findAll();
        for($i = 1;$i <= 12;$i++){
            foreach($allCat as $item){
                $comment = $item->getNom();
                $month = $i;
                $courrierSortants = $em->getRepository(Sortant::class)->getByComment($comment,$month);
                $theCat = $em->getRepository(CategorieCourierSortant::class)->findOneBy(['nom' => $comment]);
                switch ($i){
                    case 1:
                        $theCat->setNbcourrier01(count($courrierSortants));
                        break;
                    case 2:
                        $theCat->setNbcourrier02(count($courrierSortants));
                        break;
                    case 3:
                        $theCat->setNbcourrier03(count($courrierSortants));
                        break;
                    case 4:
                        $theCat->setNbcourrier04(count($courrierSortants));
                        break;
                    case 5:
                        $theCat->setNbcourrier05(count($courrierSortants));
                        break;
                    case 6:
                        $theCat->setNbcourrier06(count($courrierSortants));
                        break;
                    case 7:
                        $theCat->setNbcourrier07(count($courrierSortants));
                        break;
                    case 8:
                        $theCat->setNbcourrier08(count($courrierSortants));
                        break;
                    case 9:
                        $theCat->setNbcourrier09(count($courrierSortants));
                        break;
                    case 10:
                        $theCat->setNbcourrier10(count($courrierSortants));
                        break;
                    case 11:
                        $theCat->setNbcourrier11(count($courrierSortants));
                        break;
                    case 12:
                        $theCat->setNbcourrier12(count($courrierSortants));
                        break;
                }

                $em->persist($theCat);
            }
        }
        $em->flush();
    }

    public function statParPeriodeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);

        $nomBynumCourier = [];
        $attributionList = [];
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        // $newObs = $em->createQueryBuilder()
        //     ->select('(le.createdAt)')
        //     ->from(SortantObservation::class, 'le')
        //     ->Where('le.status = :val')
        //     ->setParameter('val', $cc)
        //     ->getQuery()
        //     ->getScalarResult();
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierSortant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');
        $categorie = $request->query->get('categorie');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $moisCourr = $defaultYear->format('m');

        if ($isChefSAI || $isChefDeDirection || $isSuperAdmin || $isAdmin || $isSystemUser ) 
        {
            $sortantQueryc = $em->getRepository(Sortant::class)->createQueryBuilder('s')
                                ->addOrderBy('s.createdAt','DESC')
                                ->addOrderBy('s.numeroCourrier','DESC');
            if ($nifFilter && $status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif ($nifFilter && !$status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif (!$nifFilter && $status) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                    array('courrierId'=> $sortantQueryc, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif ($categorie) {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->findByCategorie($thisService, $categorie);
                //     array('courrierId'=> $sortantQueryc, 'commentaires'=> $categorie ),
                //     array('numeroCourrier' => 'DESC')
                // );
            }else {
                $sortantQueryOkey = $em->getRepository(Sortant::class)->createQueryBuilder('s')
                ->addOrderBy('s.createdAt','DESC')
                ->addOrderBy('s.numeroCourrier','DESC');            }

            // $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
            //     array('courrierId'=> $sortantQueryc, 'status'=> 'Transmis' ),
            //     array('numeroCourrier' => 'DESC')
            // );
        
            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQueryOkey,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                'courriers' => $sortants,
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'dispatcher' => false,
                'listAssigne' => false,
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));
            
            $documentsQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                ->addOrderBy('e.createdAt','DESC')
                ->addOrderBy('e.numeroCourrier', 'DESC')
                ->distinct(true)
                ->getQuery();

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

            else if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId() /*or $sortantDuService  */) {
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

            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'listAssigne' => false,
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));

            if ($isChefDeService) {
                if ($gestionnaireId) {
                    $documentsQuery
                        ->andWhere('e.gestionnaire = :gestionnaire')
                        ->setParameter('gestionnaire', $gestionnaireId);
                }

                $sortantQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e')
                    ->where('e.service = :service')
                    ->setParameter('service', $user->getService())
                    ->distinct('e.numero')
                    ->getQuery();

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQuery,
                    $request->query->getInt('page', 1),
                    20
                );

                $status = "Transmis";
                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                    'courriers' => $sortants,
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false,
                    'imprimer' => 'listAction',
                    'categories' => $categories,
                ));
            }

            $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                'courriers' => $courrierSortantPagination,
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
                'sectorActs' => $sectorActs,
                'usersService' => $responsableQuery,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'listAssigne' => false,
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));
        }
        else {
            $documentsQuery = $em->getRepository(Sortant::class)->createQueryBuilder('e');
            $documentsQuery
                ->andWhere('e.service = :service')
                ->setParameter('service', $user->getService())
                ->distinct('e.numero');
            $sortantQuery = $em->getRepository(Service::class)
                ->find($user->getService()->getId())
                ->getSortant();

            if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) 
            {
                $documentsQuery
                ->orderBy('e.numero', 'DESC');
            } 
            else {
                return $this->redirectToRoute('list_sortant_assigne');
            }
            $courrier_assigner = $em->getRepository(Sortant::class)->createQueryBuilder('ca')
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

                $sortantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getSortant();

                $sortantQueryc =[];
                $courrier = "";
                foreach( $sortantQuery as  $sortant){                
                    $courrier = $sortant->getCourrierId();
                    array_push($sortantQueryc,$courrier);
                }

                if ($nifFilter && $status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif ($nifFilter && !$status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'nif'=> $nifFilter),
                        array('numeroCourrier' => 'DESC')
                    );
                }elseif (!$nifFilter && $status) {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'status'=> $status ),
                        array('numeroCourrier' => 'DESC')
                    );
                }else {
                    $sortantQueryOkey = $em->getRepository(Sortant::class)->findBy(
                        array('courrierId'=> $sortantQueryc, 'status'=> 'Transmis'),
                        array('numeroCourrier' => 'DESC')
                    );
                }

                $paginator  = $this->get('knp_paginator');
                $sortants = $paginator->paginate(
                    $sortantQueryOkey,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierSortantPagination = $this->refreshSortant($request);
                return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                    'courriers' => $sortants,
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'dispatcher' => false,
                    'listAssigne' => false,
                    'imprimer' => 'listAction',
                    'categories' => $categories,
                ));
            }

            $sortantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getSortant();
            $paginator  = $this->get('knp_paginator');
            $sortants = $paginator->paginate(
                $sortantQuery,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierSortantPagination = $this->refreshSortant($request);
            return $this->render('DBundle:Sortant:statParPeriode.html.twig', array(
                'courriers' => $sortants,
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'nomBynumCourier' => $nomBynumCourier,
                'attributionList' => $attributionList,
                'dispatcher' => false,
                'listAssigne' => false,
                'imprimer' => 'listAction',
                'categories' => $categories,
            ));
        }
    }
}
