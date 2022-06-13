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
use DBundle\Entity\Entrant;
use DBundle\Entity\EntrantObservation;
use DBundle\Entity\CourrierDispatching;
use DBundle\Entity\EntrantObjet;
use DBundle\Entity\PourInfo;
use DBundle\Entity\Attribution;
use DBundle\Entity\CategorieCourierEntrant;

use DBundle\Form\EntrantObservationType;
use DBundle\Form\EntrantType;
use DBundle\Form\EntrantObjetType;
use DBundle\Repository\CategorieCourierEntrantRepository;
use DBundle\Repository\EntrantRepository;
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

class EntrantController extends Controller
{

    public function refreshEntrant(Request $request)
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

        $courrierEntrants = $em->getRepository(Entrant::class)->createQueryBuilder('ce')
            ->addOrderBy('ce.createdAt','DESC')
            ->addOrderBy('ce.numeroCourrier', 'DESC')
            ->distinct(true);

        if ($status) {
            $courrierEntrants
                ->andWhere('ce.status LIKE :status')
                ->setParameter('status', $status);
        }
        if ($date_du && $date_au) {
            $courrierEntrants
                ->andWhere('ce.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $courrierEntrants
                ->andWhere('ce.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $courrierEntrants
                ->andWhere('ce.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $courrierEntrants
                ->andWhere('ce.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }
        if ($categorie) {
                $courrierEntrants
                ->andWhere('ce.commentaires LIKE :categorie')
                ->setParameter('categorie', '%' . $categorie . '%');
        }

        $courrierEntrants->getQuery();

        $paginator  = $this->get('knp_paginator');
        $courrierEntrantPagination = $paginator->paginate(
            $courrierEntrants,
            $request->query->getInt('page', 1),
            20
        );

        foreach ($courrierEntrantPagination as $key => $courrierEntrant) {
            if ($courrierEntrant->dispatch = 'Dispatch') {
                $courrierEntrant->dispatch = 'Dispatch';
            } else {
                $courrierEntrant->dispatch = $courrierEntrant->getService();
            }
        }

        return $courrierEntrantPagination;
    }

    public function listAction(Request $request)
    {
        $listAction = true;
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');
        $categorie = $request->query->get('categorie');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if ($isChefSAI || $isChefDeDirection || $isSuperAdmin || $isSystemUser ) 
        {            
            $this->setStatAction();
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

            if ($nifFilter) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'nif'=> $nifFilter),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif ($categorie) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'commentaires'=> $categorie),
                    array('numeroCourrier' => 'DESC')
                );
            }elseif (!$nifFilter && $status) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'status'=> $status ),
                    array('numeroCourrier' => 'DESC')
                );
            }else {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis'),
                    array('numeroCourrier' => 'DESC')
                );
            }

            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQueryOkey,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
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
            
            $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
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

            $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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

                $status = "Transmis";
                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierEntrantPagination = $this->refreshEntrant($request);
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

            $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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
            $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e');
            $documentsQuery
                ->andWhere('e.service = :service')
                ->setParameter('service', $user->getService())
                ->distinct('e.numero');
            $entrantQuery = $em->getRepository(Service::class)
                ->find($user->getService()->getId())
                ->getEntrant();

            if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) 
            {
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
                    //     $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    //         array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis' ),
                    //         array('numeroCourrier' => 'DESC')
                    //     );
                    // }
                // }
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
                        array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis'),
                        array('numeroCourrier' => 'DESC')
                    );
                }

                $paginator  = $this->get('knp_paginator');
                $entrants = $paginator->paginate(
                    $entrantQueryOkey,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierEntrantPagination = $this->refreshEntrant($request);
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

            $entrantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQuery,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
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

    public function listPdfCheckAction(Request $request)
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

        $cc = "Assigné";
        $now = new \DateTime();
        date_format($now, 'Y-m-d H:i:s');

        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

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

            $entrantCheck = $em->createQueryBuilder();
            $entrantCheck->select('count(entrant.numeroCourrier)');
            $entrantCheck->from(Entrant::class, 'entrant');
            $entrantCount = $entrantCheck->getQuery()->getSingleScalarResult();

            for ($i = 0; $i < $entrantCount; $i++) {
                array_push($nomBynumCourier, " ");
                array_push($attributionList, " ");
            }
            if ($entrantCount > 0) {
                $entrantLast = $em->createQueryBuilder()
                    ->select('MAX(le.numeroCourrier)')
                    ->from(Entrant::class, 'le')
                    ->where('le.yearCourr = 2022')
                    ->getQuery()

                    ->getSingleScalarResult();

                $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                    ->where('nc.numero > :lastNumero')
                    ->setParameter('lastNumero', $entrantLast)
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
                        $newEntrant = new Entrant;
                        $newEntrant->setRaisonSocial($docCourrier->rs);
                        $newEntrant->setNif($docCourrier->nif);
                        $newEntrant->setTitre($docCourrier->titre);
                        $newEntrant->setObjetCourrier($docCourrier->objet);
                        $newEntrant->setNumeroCourrier($docCourrier->getNumero());
                        $newEntrant->setAuteur($this->getUser());
                        $newEntrant->setUpdatedAt(new \DateTime());
                        $newEntrant->setCreatedAt($docCourrier->createdDate);
                        $newEntrant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newEntrant->setStatus('Nouveau');
                        $newEntrant->setPriority('Normal');
                        $newEntrant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newEntrant->setCourrierId($docCourrier->getDocNo());
                        $newEntrant->setService($user->getService());
                        $newEntrant->setYearCourr($docCourrier->getYearCourr());
                        $newEntrant->dispatch = 'Dispatch';
                        $newEntrant->setAttribution(null);
                        $newEntrant->setCommentaires($docCourrier->commentaires);

                        $em->persist($newEntrant);
                        $em->flush();
                    }
                    $courrierEntrantPagination = $this->refreshEntrant($request);

                    $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->SetAuthor('IDS');
                    $pdf->SetTitle(('Courriers entrants'));
                    $pdf->SetSubject('Courriers entrants');
                    $pdf->setFontSubsetting(true);
                    $pdf->SetFont('helvetica', '', 11, '', true);
                    //$pdf->SetMargins(20,20,40, true);
                    $pdf->AddPage();

                    $filename = 'Courriers Entrants';
                    $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
                        'courriers' => $courrierEntrantPagination,
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
                        'isSuperAdmin' => $isSuperAdmin,
                        'isAdmin' => $isAdmin,
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

                $courrierEntrantPagination = $this->refreshEntrant($request);

                $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers entrants'));
                $pdf->SetSubject('Courriers entrants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('helvetica', '', 8, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers entrants';
                $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
                    'courriers' => $courrierEntrantPagination,
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
                        $newEntrant = new Entrant;
                        $newEntrant->setRaisonSocial($docCourrier->rs);
                        $newEntrant->setNif($docCourrier->nif);
                        $newEntrant->setAuteur($this->getUser());
                        $newEntrant->setUpdatedAt(new \DateTime());
                        $newEntrant->setCreatedAt($courrierInfos->createdDate);
                        $newEntrant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newEntrant->setStatus('Nouveau');
                        $newEntrant->setPriority('Normal');
                        $newEntrant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newEntrant->setCourrierId($docCourrier->getDocNo());
                        $newEntrant->addService($user->getService());
                        $newEntrant->setYearCourr($docCourrier->getYearCourr());
                        $newEntrant->setTitre($docCourrier->titre);
                        $newEntrant->setObjet($docCourrier->objet);
                        $newEntrant->setNumeroCourrier($docCourrier->getNumero());
                        $newEntrant->dispatch = 'Dispatch';
                        $newEntrant->setCommentaires($docCourrier->commentaires);

                        $em->persist($newEntrant);
                        $em->flush();
                    }

                    $courrierEntrantPagination = $this->refreshEntrant($request);
                    $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $pdf->SetAuthor('IDS');
                    $pdf->SetTitle(('Courriers entrants'));
                    $pdf->SetSubject('Courriers entrants');
                    $pdf->setFontSubsetting(true);
                    $pdf->SetFont('helvetica', '', 11, '', true);
                    //$pdf->SetMargins(20,20,40, true);
                    $pdf->AddPage();

                    $filename = 'Courriers entrants';
                    $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
                        'courriers' => $courrierEntrantPagination,
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
                        'isSuperAdmin' => $isSuperAdmin,
                        'isAdmin' => $isAdmin,
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

                $courrierEntrantPagination = $this->refreshEntrant($request);
                $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers entrants'));
                $pdf->SetSubject('Courriers entrants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('arial', '', 11, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers entrants';
                $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
                    'courriers' => $courrierEntrantPagination,
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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

            $courrierEntrantPagination = $this->refreshEntrant($request);

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
                $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('Courriers entrants'));
                $pdf->SetSubject('Courriers entrants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('arial', '', 11, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();

                $filename = 'Courriers entrants';

                $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
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
                    'isInspecteur' => $isInspecteur,
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
            $pdf->SetTitle(('Courriers entrants'));
            $pdf->SetSubject('Courriers entrants');
            $pdf->setFontSubsetting(true);
            $pdf->SetFont('arial', '', 11, '', true);
            //$pdf->SetMargins(20,20,40, true);
            $pdf->AddPage();

            $filename = 'Courriers entrants';
            $html = $this->render('DBundle:Entrant:listpdf.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
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

        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);

        $query = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('d')
            ->where('d.docNo = :docNo')
            ->setParameter('docNo', $courrier_docNo)
            ->getQuery();

        $docCourrier = $query->getOneOrNullResult();

        if ($docCourrier) {
            $docCourrierLastObservation = $em->getRepository(EntrantObservation::class)->findOneBy(array('createdAt' => $docCourrier->getDelegationDate()));
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
            $docCourrierEntrant = $em->getRepository(Entrant::class)->findOneBy(array('courrierId' => $docCourrier->getDocNo()));
            if ($docCourrierEntrant) {
                $docCourrier->setPriority('Normal');
                $docCourrier->setStatus($docCourrierEntrant->getStatus());
                $docCourrier->attribution = $docCourrierEntrant->getAttribution();
                $entrantService_not_sai = false;
                $leChef = $sai->getService();
                foreach ($docCourrierEntrant->getServices() as $service) {
                    if ($service->getNom() != $leChef) {
                        $entrantService_not_sai = true;
                        $leChef = $service->getNom();
                    }
                }
                if ($docCourrierEntrant->getGestionnaires()) {
                    $docCourrier->setResponsable($leChef);
                } elseif ($docCourrierEntrant->getServices() &&  $entrantService_not_sai) {
                    $docCourrier->setResponsable($leChef);
                } else {
                    $docCourrier->setResponsable($docCourrierEntrant->getAuteur());
                }
            }
        }
        return $docCourrier;
    }

    public function showAction(DocCourrier $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);

        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $isSystemUser = ($user->getId() == 89) ? true : false;
        $courrier->setObservationContent('ids');
        $courrierDocNo = $courrier->getDocNo();
        $docCourrier = $this->getCourrier($courrierDocNo);
        $docCourrierEntrant = $em->getRepository(Entrant::class)->findOneBy(array('courrierId' => $courrierDocNo));
        $courrierIdAuto = $docCourrierEntrant->getId();
        if ($sai) {
            $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        }
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $isUserConcerned = false;
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        foreach ($docCourrierEntrant->getServices() as $service) {
            if ($service->getNom() == $user->getService()) {
                $isUserConcerned = true;
            }
        }
        $defaultData = ['message' => 'Type your message here'];
        $defaultStatus = "Nouveau";
        $defaultPriority = "Normal";
        $doc = $em->getRepository('DBundle:EntrantObservation');
        $docCourrierObseration = $doc->findByCourrier($courrierDocNo);

        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat) {
                switch ($stat) {
                    case 'Transmis':
                        if (!$isChefDeService && !$isInspecteur) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
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
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Assigné');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                        $em->persist($observation);
                        $em->flush();
                        break;

                    case 'Traité':
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setStatus('Traité');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
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
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setStatus('Non Traité');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Non Traité');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
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
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Clôturé');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->getId()));
                        if($userAssigne)
                        {
                            $obseGets = $em->getRepository(EntrantObservation::class)->findBy(['entrantIdAuto' => $docCourrierEntrant->getId()]);
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

                        return $this->redirectToRoute('list_entrant');

                        break;
                }
                if ($isChefDeDirection or $isChefSAI or $isChefDeService or $isSystemUser)
                {
                    return $this->redirectToRoute('list_entrant', []);
                }else
                {
                    return $this->redirectToRoute('list_entrant_assigne', []);
                }
            }
        }

        if (!$docCourrierObseration) {
            $delay = 0;
            header("Refresh: $delay;");
            $observation = new EntrantObservation;
            $observation->setStatus('Nouveau');
            $observation->setService($user->getService());
            $observation->setMessage('Nouveau courrier à dispatcher . . .');
            $observation->setUser($this->getUser());
            $observation->setEntrantIdAuto($courrierIdAuto);
            $observation->setCourrier($courrierDocNo);
            $observation->setCreatedAt(new \DateTime);
            $em->persist($observation);
            $em->flush();
            if ($request->request->get('dispatch'))
            {
                foreach ($request->request->get('dispatch') as $service)
                {
                    $dispatchingService = $service;
                    $entrantService = $em->getRepository(Service::class)->findOneBy(array('nom' => $dispatchingService));
                    if ($entrantService && $isChefSAI && $dispatchingService) {
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $docCourrierEntrant->setTraitementDate(null);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $docCourrierEntrant->addService($entrantService);
                        $docCourrierEntrant->setStatus('Transmis');
                        $observation = new EntrantObservation;
                        $observation->setDispatch('1');
                        $observation->setStatus('Transmis');
                        $observation->setService($entrantService);
                        $observation->setUser($entrantService->getChef());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($docCourrierEntrant);
                        $em->persist($observation);
                        $em->flush();

                        $userDispatch = $em->getRepository(User::class)->findOneBy(array('id' => $entrantService->getChef()->getId()));
                        if($userDispatch)
                        {
                            $userDispatch->setNbrecourrier($userDispatch->getNbrecourrier()+1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }
                
                    }

                    if ($entrantService && $isChefDeDirection && $dispatchingService) {
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $docCourrierEntrant->setTraitementDate(null);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $docCourrierEntrant->addService($entrantService);
                        $docCourrierEntrant->setStatus('Transmis');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($entrantService);
                        $observation->setUser($entrantService->getChef());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($docCourrierEntrant);
                        $em->persist($observation);
                        $em->flush();

                        $userDispatch = $em->getRepository(User::class)->findOneBy(array('id' => $entrantService->getChef()->getId()));
                        if($userDispatch)
                        {
                            $userDispatch->setNbrecourrier($userDispatch->getNbrecourrier()+1);
                            $em = $this->getDoctrine()->getManager();
                            $em->flush();
                        }
                
                    }

                }
            
                return $this->redirectToRoute('list_entrant_dispatch', ['page' => $_SESSION['page']]);
                
                $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                    'docNo' => $courrierDocNo,
                    'informative' => false
                ]);
                if (!$dispatch) {
                    $dispatch = new CourrierDispatching();
                    $dispatch->setTraite(false);
                    $dispatch->setInformative(false);
                    $dispatch->setCloturer(false);
                    if (!($entrantService->getChef())) {
                        $delay = 0;
                        header("Refresh: $delay;");
                        $dispatch->setGestionnaire($entrantService->getChef());
                        $delay = 0;
                        header("Refresh: $delay;");
                    }
                    $dispatch->setDocNo($docCourrierEntrant->getCourrierId());
                    $dispatch->setService($entrantService);
                    $em->persist($dispatch);
                }

                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->redirectToRoute('list_entrant');
            }
        }

        if ($docCourrierObseration) {
            if (!$isChefDeService && !$isChefSAI && !$isChefDeDirection && !$isInspecteur)
            {
                if ($request->getMethod() == 'GET') {
                    $stat = $request->query->get('changestatus');
                    if ($stat == 'Traité') {
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $docCourrierEntrant->setTraitementDate(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
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
                        return $this->redirectToRoute('list_entrant');
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();
                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);
                return $this->render('DBundle:Entrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'entrant' => $docCourrierEntrant,
                    'observations' => $observations,
                    'services' => $services,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
                            $dispatch->setDocNo($docCourrierEntrant->getDocNo());
                            $dispatch->setService($service);
                            $em->persist($dispatch);
                        }
                        $courrier->setService($courrier_service);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $courrier->setStatus('Transmis');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $courrier->setService($service);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($service);
                        $observation->setUser($service->getChef());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();
                        return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                    } else {
                        return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                if ($request->request->get('dispatch')) {
                    foreach ($request->request->get('dispatch') as $service) {
                        $dispatchingService = $service;
                        $entrantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));
                        if ($entrantService) {
                            $courrier_service = $courrier->getService();
                            if (!$courrier_service || $courrier_service && $isChefSAI) {
                                $courrier->setService($courrier_service);
                                $courrier->setDelegationDate(new \DateTime);
                                $docCourrierEntrant->setTraitementDate(null);
                                $courrier->setStatus('Transmis');
                                $observation = new EntrantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($user->getService());
                                $observation->setUser($this->getUser());
                                $observation->setEntrantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                                $em->persist($observation);
                                $em->flush();

                                $docCourrierEntrant->addService($entrantService);
                                $docCourrierEntrant->setStatus('Transmis');
                                $observation = new EntrantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($entrantService);
                                $observation->setUser($entrantService->getChef());
                                $observation->setEntrantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Action requise :  ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                                $em->persist($docCourrierEntrant);
                                $em->persist($observation);
                                $em->flush();
                            }
                        } else {
                            $entrantService = $user->getService();
                        }
                    }
                }

                if ($request->getMethod() == 'POST') {
                    $stat = $request->query->get('changestatus');
                    if ($stat != 'Clôturé') {
                        $observation = new EntrantObservation;
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $formulaire_observation = $this->createForm(EntrantObservationType::class, $observation);
                        $formulaire_observation->handleRequest($request);

                        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                            $courrier->setUpdatedAt(new \DateTime);
                            $em->persist($observation);
                            $em->flush();
                            return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                        }
                    }
                }

                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                $services = $em->getRepository(Service::class)->findAll();
                return $this->render('DBundle:Entrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'observations' => $observations,
                    'entrant' => $docCourrierEntrant,
                    'services' => $services,
                    'form' => $form->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
                            $dispatch->setDocNo($docCourrierEntrant->getDocNo());
                            $dispatch->setService($service);
                            $em->persist($dispatch);
                        }
                        $courrier->setService($courrier_service);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $courrier->setStatus('Transmis');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                        $em->persist($observation);
                        $em->flush();

                        $courrier->setService($service);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($service);
                        $observation->setUser($service->getChef());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">Action requise :  ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();
                        return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                    } else {
                        return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                if ($request->request->get('dispatch')) {
                    foreach ($request->request->get('dispatch') as $service) {
                        $dispatchingService = $service;
                        $entrantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));
                        if ($entrantService) {
                            $courrier_service = $courrier->getService();
                            if (!$courrier_service || $courrier_service && $isChefDeDirection) {
                                $courrier->setService($courrier_service);
                                $courrier->setDelegationDate(new \DateTime);
                                $docCourrierEntrant->setTraitementDate(null);
                                $courrier->setStatus('Transmis');
                                $observation = new EntrantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($user->getService());
                                $observation->setUser($this->getUser());
                                $observation->setEntrantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Dispatch effectué à : ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                                $em->persist($observation);
                                $em->flush();

                                $docCourrierEntrant->addService($entrantService);
                                $docCourrierEntrant->setStatus('Transmis');
                                $observation = new EntrantObservation;
                                $observation->setStatus('Transmis');
                                $observation->setService($entrantService);
                                $observation->setUser($entrantService->getChef());
                                $observation->setEntrantIdAuto($courrierIdAuto);
                                $observation->setCourrier($courrierDocNo);
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('<span class="text-green">Action requise :  ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                                $em->persist($docCourrierEntrant);
                                $em->persist($observation);
                                $em->flush();
                            }
                        } else {
                            $entrantService = $user->getService();
                        }
                    }
                }

                if ($request->getMethod() == 'POST') {
                    $stat = $request->query->get('changestatus');
                    if ($stat != 'Clôturé') {
                        $observation = new EntrantObservation;
                        $observation->setUser($this->getUser());
                        $observation->setEntrantIdAuto($courrierIdAuto);
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $formulaire_observation = $this->createForm(EntrantObservationType::class, $observation);
                        $formulaire_observation->handleRequest($request);

                        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                            $courrier->setUpdatedAt(new \DateTime);
                            $em->persist($observation);
                            $em->flush();
                            return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                        }
                    }
                }

                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                $services = $em->getRepository(Service::class)->findAll();
                return $this->render('DBundle:Entrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'observations' => $observations,
                    'entrant' => $docCourrierEntrant,
                    'services' => $services,
                    'form' => $form->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
                                    foreach ($docCourrierEntrant->getServices() as $service) {
                                        if ($service->getNom() != $leChef) {
                                            $entrantService_not_sai = true;
                                            $leChef = $service->getNom();
                                        }
                                    }
                                    $docCourrierEntrant->setAttribution($attributionDesc);
                                    $entrant_id = $docCourrierEntrant->getId();
                                    $user_id = $gestionnaireData;
                                    $em->getRepository(Entrant::class)->removeGestionnaire($user_id,$entrant_id);
                                    $docCourrierEntrant->addGestionnaire($getUser);
                                    $docCourrierEntrant->setUpdatedAt(new \DateTime);
                                    $docCourrierEntrant->setPriority('Normal');
                                    $em->persist($docCourrierEntrant);
                                    $observation = new EntrantObservation;
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
                                    $observation->setEntrantIdAuto($docCourrierEntrant->getId());
                                    $observation->setCourrier($courrier->getDocNo());
                                    $observation->setCreatedAt(new \DateTime);
                                    $observation->setMessage('a assigné ce courrier à ' . $getUser->getNom() . ' ' . $getUser->getPrenom()/*.'. Status courrier: <span class="badge bg-yellow">Assigné*/ . '</span>');
                                    $em->persist($observation);

                                    $observation = new EntrantObservation;

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
                                    $observation->setEntrantIdAuto($docCourrierEntrant->getId());
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
                                        $dispatch->setDocNo($docCourrierEntrant->getCourrierId());
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
                            return $this->redirectToRoute('list_entrant', ['courrier' => $courrier->getDocNo()]);
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();
                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->render('DBundle:Entrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'entrant' => $docCourrierEntrant,
                    'observations' => $observations,
                    'services' => $services,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isChefDeDirection' => $isChefDeDirection,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                    'isInspecteur' => $isInspecteur,
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
                ));
            }
        }
        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat != 'Clôturé') {
                $observation = new EntrantObservation;
                $observation->setUser($this->getUser());
                $observation->setEntrantIdAuto($docCourrierEntrant->getId());
                $observation->setCourrier($courrier->getDocNo());
                $observation->setCreatedAt(new \DateTime);
                $formulaire_observation = $this->createForm(EntrantObservationType::class, $observation);
                $formulaire_observation->handleRequest($request);

                if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                    $courrier->setUpdatedAt(new \DateTime);
                    $em->persist($observation);
                    $em->flush();
                    return $this->redirectToRoute('show_entrant', ['courrier' => $courrier->getDocNo()]);
                }
            }
        }
        $services = $em->getRepository(Service::class)->findAll();
        $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

        return $this->render('DBundle:Entrant:show.html.twig', array(
            'courrier' => $courrier,
            'entrant' => $docCourrierEntrant,
            'observations' => $observations,
            'services' => $services,
            'isChefDeService' => $isChefDeService,
            'isChefSAI' => $isChefSAI,
            'isChefDeDirection' => $isChefDeDirection,
            'isMembreDirection' => $isMembreDirection,
            'isUserConcerned' => $isUserConcerned,
            'isInspecteur' => $isInspecteur,
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
        ));
    }

    public function newCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new EntrantObjet;

        $form = $this->createForm(EntrantObjetType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('list_entrant_cat');
        }

        return $this->render('DBundle:Entrant:categorie\new_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function listCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(EntrantObjet::class)->createQueryBuilder('c')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $categorie = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Entrant:categorie\list_cat.html.twig', array(
            'categories' => $categorie
        ));
    }

    public function editCatAction(Request $request, EntrantObjet $nature)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EntrantObjetType::class, $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('list_entrant_cat');
        }

        return $this->render('DBundle:Entrant:categorie\edit_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function getCommentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $inputs =  $em->getRepository(Entrant::class)->findOneBy([
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

    public function initializeEntrantAction()
    {
        $em = $this->getDoctrine()->getManager();
        $inputs =  $em->getRepository(Entrant::class)->findAll();
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
        return $this->redirectToRoute('list_entrant');
    }

    public function autoCompleteNif2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $entrants = $em->getRepository(Entrant::class)->getByNif($nif);
            $output = [];
            foreach ($entrants as $entrant) {
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSocial();
                $temp_array['useIt'] = $entrant->getNif() . ' - ' . $entrant->getRaisonSocial() . '';
                // $temp_array['useIt'] = $createdAt . ' - ' . $entrant->getNif() . ' - ' . $entrant->getRaisonSocial() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }
    
    public function autoCompleteRsoc2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $entrants = $em->getRepository(Entrant::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($entrants as $entrant) {
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSocial();
                $temp_array['useIt'] = $entrant->getNif() . ' - ' . $entrant->getRaisonSocial() . '';
                // $temp_array['useIt'] = $createdAt . ' - ' . $entrant->getNif() . ' - ' . $entrant->getRaisonSocial() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }

    public function listDispatchAction(Request $request)
    {
        $_SESSION['page'] = $request->query->get('page');
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);
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
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        $entrantCheck = $em->createQueryBuilder();
        $entrantCheck->select('count(entrant.numeroCourrier)');
        $entrantCheck->from(Entrant::class, 'entrant');
        $entrantCount = $entrantCheck->getQuery()->getSingleScalarResult();

        for ($i = 0; $i < $entrantCount; $i++) {
            array_push($nomBynumCourier, " ");
            array_push($attributionList, " ");
        }
        if ($entrantCount > 0) {
            $entrantLast = $em->createQueryBuilder()
                ->select('MAX(le.numeroCourrier)')
                ->from(Entrant::class, 'le')
                ->where('le.yearCourr = 2022')
                ->getQuery()
                ->getSingleScalarResult();
                if (!$entrantLast)
                {
                    $entrantLast = 0;
                }
            $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                ->where('nc.numero > :lastNumero')
                ->setParameter('lastNumero', $entrantLast)
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
                    $newEntrant = new Entrant;
                    $newEntrant->setRaisonSocial($docCourrier->rs);
                    $newEntrant->setNif($docCourrier->nif);
                    $newEntrant->setTitre($docCourrier->titre);
                    $newEntrant->setObjetCourrier($docCourrier->objet);
                    $newEntrant->setNumeroCourrier($docCourrier->getNumero());
                    $newEntrant->setAuteur($this->getUser());
                    $newEntrant->setUpdatedAt(new \DateTime());
                    $newEntrant->setCreatedAt($docCourrier->createdDate);
                    $newEntrant->setObjectId($docCourrier->getDocCourrierObjectNo());
                    $newEntrant->setStatus('Nouveau');
                    $newEntrant->setPriority('Normal');
                    $newEntrant->setObservationContent('Nouveau courrier à dispatcher . . .');
                    $newEntrant->setCourrierId($docCourrier->getDocNo());
                    $newEntrant->addService($user->getService());
                    $newEntrant->setYearCourr($docCourrier->getYearCourr());
                    $newEntrant->dispatch = 'Dispatch';
                    $newEntrant->setAttribution(null);
                    $newEntrant->setCommentaires($docCourrier->commentaires);
                    $em->persist($newEntrant);
                    $em->flush();
                    $entrant_id = $newEntrant->getId();
                    $serviceId = $user->getService()->getId();
                    $em->getRepository(Entrant::class)->removeServiceEntrant($serviceId, $entrant_id);
                }
            }
        }

        $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusnouveau')
        ->setParameter('statusnouveau', 'Nouveau')
        ->distinct('e.numero')
        ->addOrderBy('e.createdAt', 'DESC')
        ->addOrderBy('e.id', 'DESC');
        
        if ($date_du && $date_au) {
            $entrantQuery
                ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $entrantQuery
                ->andWhere('e.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $entrantQuery
                ->andWhere('e.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $entrantQuery
                ->andWhere('e.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }

        $entrantQuery->getQuery();
        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierEntrantPagination = $this->refreshEntrant($request);
        return $this->render('DBundle:Entrant:listDispatch.html.twig', array(
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
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);
        
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
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if ($isChefSAI || $isChefDeDirection || $isSuperAdmin || $isSystemUser) {
            $entrantCheck = $em->createQueryBuilder();
            $entrantCheck->select('count(e.numeroCourrier)');
            $entrantCheck->from(Entrant::class, 'e');
            $entrantCount = $entrantCheck->getQuery()->getSingleScalarResult();

            for ($i = 0; $i < $entrantCount; $i++) {
                array_push($nomBynumCourier, " ");
                array_push($attributionList, " ");
            }
            if ($entrantCount > 0) {
                $entrantLast = $em->createQueryBuilder()
                    ->select('MAX(e.numeroCourrier)')
                    ->from(Entrant::class, 'e')
                    ->where('e.yearCourr = :yearCourr')
                    ->setParameter('yearCourr', $yearCourr)
                    ->getQuery()
                    ->getSingleScalarResult();
                    if (!$entrantLast)
                    {
                        $entrantLast = 0;
                    }
                $newCourriers = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('nc')
                    ->where('nc.numero > :lastNumero')
                    ->setParameter('lastNumero', $entrantLast)
                    ->andWhere('nc.typeCourrier LIKE :typeCourrier')
                    ->setParameter('typeCourrier', "E")
                    ->andWhere('nc.yearCourr LIKE :yearCourr')
                    ->setParameter('yearCourr', $yearCourr)
                    ->orderBy('nc.numero', 'ASC')
                    ->distinct('nc.numero')
                    ->getQuery()
                    ->getResult();

                if ($newCourriers) {
                    foreach ($newCourriers as $key => $newCourrier) {
                        $docCourrier = $this->getCourrier($newCourrier->getDocNo());
                        $newEntrant = new Entrant;
                        $newEntrant->setRaisonSocial($docCourrier->rs);
                        $newEntrant->setNif($docCourrier->nif);
                        $newEntrant->setTitre($docCourrier->titre);
                        $newEntrant->setObjetCourrier($docCourrier->objet);
                        $newEntrant->setNumeroCourrier($docCourrier->getNumero());
                        $newEntrant->setAuteur($this->getUser());
                        $newEntrant->setUpdatedAt(new \DateTime());
                        $newEntrant->setCreatedAt($docCourrier->createdDate);
                        $newEntrant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newEntrant->setStatus('Nouveau');
                        $newEntrant->setPriority('Normal');
                        $newEntrant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newEntrant->setCourrierId($docCourrier->getDocNo());
                        $newEntrant->addService($user->getService());
                        $newEntrant->setYearCourr($docCourrier->getYearCourr());
                        $newEntrant->dispatch = 'Dispatch';
                        $newEntrant->setAttribution(null);
                        $newEntrant->setCommentaires($docCourrier->commentaires);
                        $em->persist($newEntrant);
                        $em->flush();
                        $entrant_id = $newEntrant->getId();
                        $serviceId = $user->getService()->getId();
                        $em->getRepository(Entrant::class)->removeServiceEntrant($serviceId, $entrant_id);
                    }

                    $this->setStatAction();

                    $courrierEntrantPagination = $this->refreshEntrant($request);
                    return $this->render('DBundle:Entrant:list.html.twig', array(
                        'courriers' => $courrierEntrantPagination,
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
                        'isSuperAdmin' => $isSuperAdmin,
                        'isAdmin' => $isAdmin,
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

                $courrierEntrantPagination = $this->refreshEntrant($request);
                return $this->render('DBundle:Entrant:list.html.twig', array(
                    'courriers' => $courrierEntrantPagination,
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
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
                        $newEntrant = new Entrant;
                        $newEntrant->setRaisonSocial($docCourrier->rs);
                        $newEntrant->setNif($docCourrier->nif);
                        $newEntrant->setAuteur($this->getUser());
                        $newEntrant->setUpdatedAt(new \DateTime());
                        $newEntrant->setCreatedAt($courrierInfos->createdDate);
                        $newEntrant->setObjectId($docCourrier->getDocCourrierObjectNo());
                        $newEntrant->setStatus('Nouveau');
                        $newEntrant->setPriority('Normal');
                        $newEntrant->setObservationContent('Nouveau courrier à dispatcher . . .');
                        $newEntrant->setCourrierId($docCourrier->getDocNo());
                        $newEntrant->addService($user->getService());
                        $newEntrant->setYearCourr($docCourrier->getYearCourr());
                        $newEntrant->setTitre($docCourrier->titre);
                        $newEntrant->setObjet($docCourrier->objet);
                        $newEntrant->setNumeroCourrier($docCourrier->getNumero());
                        $newEntrant->dispatch = 'Dispatch';
                        $newEntrant->setCommentaires($docCourrier->commentaires);
                        $em->persist($newEntrant);
                        $em->flush();
                    }

                    $courrierEntrantPagination = $this->refreshEntrant($request);
                    return $this->render('DBundle:Entrant:list.html.twig', array(
                        'courriers' => $courrierEntrantPagination,
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
                        'isSuperAdmin' => $isSuperAdmin,
                        'isAdmin' => $isAdmin,
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

                $courrierEntrantPagination = $this->refreshEntrant($request);
                return $this->render('DBundle:Entrant:list.html.twig', array(
                    'courriers' => $courrierEntrantPagination,
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

            $courrierEntrantPagination = $this->refreshEntrant($request);
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
                // $courrierEntrantPagination = $this->refreshEntrant($request);
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
                    'isSuperAdmin' => $isSuperAdmin,
                    'isAdmin' => $isAdmin,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs'),
                    'nomBynumCourier' => $nomBynumCourier,
                    'attributionList' => $attributionList,
                    'listAssigne' => false,
                    'imprimer' => 'listAllAction',
                    'categories' => $categories,
                ));
            }

            $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:list.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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
                'imprimer' => 'listAllAction',
                'categories' => $categories,
            ));
        }else
        {
            $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
            ->Where('e.status <> :statusNouveau')
            ->setParameter('statusNouveau', 'Nouveau')
            ->distinct('e.numero')
            ->addOrderBy('e.createdAt', 'DESC')
            ->addOrderBy('e.id', 'DESC');
            
            if ($date_du && $date_au) {
                $entrantQuery
                    ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au);
            }
            if ($gestionnaireId) {
                $entrantQuery
                    ->andWhere('e.gestionnaire  = :gestionnaire')
                    ->setParameter('gestionnaire', $gestionnaireId);
            }
            if ($nifFilter) {
                $entrantQuery
                    ->andWhere('e.nif LIKE :nif')
                    ->setParameter('nif', '%' . $nifFilter . '%');
            }
            if ($rsFilter) {
                $entrantQuery
                    ->andWhere('e.raisonSocial LIKE :rs')
                    ->setParameter('rs', '%' . $rsFilter . '%');
            }
            $entrantQuery->getQuery();
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQuery,
                $request->query->getInt('page', 1),
                20
            );
    
            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
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
                'isSuperAdmin' => $isSuperAdmin,
                'isAdmin' => $isAdmin,
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
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);
        
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
            ->from(EntrantObservation::class, 'le')
            ->Where('le.status = :val')
            ->setParameter('val', $cc)
            ->getQuery()
            ->getScalarResult();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $userServiceId = $user->getService()->getId();
        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $isInspecteur = ($user->getInspecteur() == 1 ) ? true : false;
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
 
        $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
        $entrantQueryc =[];
        $courrier = "";
        foreach( $entrantQuery as  $entrant){                
            $courrier = $entrant->getCourrier();
            array_push($entrantQueryc,$courrier);
        }
        $entrantQueryOk = $em->getRepository(Entrant::class)->findBy(
            ['courrierId'=> $entrantQueryc, 'status' => 'Transmis'],
            ['numeroCourrier' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $attributions = $em->getRepository(Attribution::class)->findAll();

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierEntrantPagination = $this->refreshEntrant($request);
        return $this->render('DBundle:Entrant:list.html.twig', array(
            'courriers' => $entrants,
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
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

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
        } else {
            $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
            $entrantQueryc =[];
            $courrier = "";
            foreach( $entrantQuery as  $entrant){                
                $courrier = $entrant->getCourrier();
                array_push($entrantQueryc,$courrier);
            }
            $entrantQueryOk = $em->getRepository(Entrant::class)->findBy(['courrierId'=> $entrantQueryc]);
        }
        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers entrants'));
        $pdf->SetSubject('Courriers entrants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers entrants';
        $html = $this->render('DBundle:Entrant:listPdf.html.twig', array(
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

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

        $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusnouveau')
        ->setParameter('statusnouveau', 'Nouveau')
        ->distinct('e.numero')
        ->orderBy('e.id', 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers entrants'));
        $pdf->SetSubject('Courriers entrants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers entrants';
        $html = $this->render('DBundle:Entrant:listDispatchPdf.html.twig', array(
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

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

        $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
        ->distinct('e.numero')
        ->orderBy('e.id', 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQuery,
            $request->query->getInt('page', 1),
            20
        );

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));            

        $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('IDS');
        $pdf->SetTitle(('Courriers entrants'));
        $pdf->SetSubject('Courriers entrants');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage();

        $filename = 'Courriers entrants';
        $html = $this->render('DBundle:Entrant:listAllPdf.html.twig', array(
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
            'isSystemUser' => $isSystemUser,
            'isInspecteur' => $isInspecteur,
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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

    public function entrantExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $now = new \DateTime();
        date_format($now, 'd-m-Y H:i:s');
        $createdAt = date_format($now, 'd-m-Y');
        // $filename = 'DGE - Liste des courriers entrants du ' . $createdAt . '.xlsx';
        $filename = 'DGE - Liste des courriers entrants.xlsx';
            
        $query = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
            
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Liste des courriers entrants")
            ->setSubject("Courriers entrants")
            ->setDescription("Ce fichier contient les courriers entrants")
            ->setKeywords("Entrant")
            ->setCategory("ids.xls");
        $count = 4;
        // $phpExcelObject->setActiveSheetIndex(0)
        //     ->setCellValue('A1', 'LISTE DES COURRIERS ENTRANTS ');

        $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A1', $filename);

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Numéro ')
            ->setCellValue('B3', 'NIF ')
            ->setCellValue('C3', 'Raison sociale ')
            ->setCellValue('D3', 'Objet ')
            ->setCellValue('E3', 'Commentaires ')
            ->setCellValue('F3', 'Reçu le ');
        $phpExcelObject->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(65);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(75);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(12);

        foreach ($query as $query) {

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $query->getNumeroCourrier())
                ->setCellValue('B' . $count, $query->getNif())
                ->setCellValue('C' . $count, $query->getRaisonSocial())
                ->setCellValue('D' . $count, $query->getObjet())
                ->setCellValue('E' . $count, $query->getCommentaires())
                ->setCellValue('F' . $count, $query->getCreatedAt()->format('d-m-Y'));
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
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

    public function addObsNonTraiteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $observation = new EntrantObservation;
        $lObservation = $request->request->get("obsNonTraite");
        $idcourrier = $request->request->get("idcourrier");
        
        $docCourrierEntrant = $em->getRepository(Entrant::class)->findOneBy(array('courrierId' => $idcourrier));
        $getUser = $em->getRepository(User::class)->find($this->getUser()->getId());
        $observation->setUser($getUser);
        $observation->setMessage("Le courrier n'a pas été traité. Lire l'observation");
        $observation->setCreatedAt(new \DateTime);
        $observation->setEntrantIdAuto($docCourrierEntrant->getId());
        $observation->setCourrier($idcourrier);
        $observation->setStatus('Non Traité');
        $observation->setService($this->getUser()->getService()->getNom());
        $observation->setObservations($lObservation);
        $docCourrierEntrant->setStatus('Non Traité');
        $em->persist($docCourrierEntrant);
        $em->persist($observation);
        $em->flush();
        return $this->redirectToRoute('list_entrant_assigne');
    }

    public function listNouveauAction(Request $request)
    {
        $_SESSION['page'] = $request->query->get('page');
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
        $newObs = $em->createQueryBuilder()
            ->select('(le.createdAt)')
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

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

        $entrantQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
        ->Where('e.status = :statusTransmis')
        ->setParameter('statusTransmis', 'Transmis')
        ->distinct('e.numero')
        ->addOrderBy('e.createdAt', 'DESC')
        ->addOrderBy('e.id', 'DESC');
        
        if ($date_du && $date_au) {
            $entrantQuery
                ->andWhere('e.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if ($gestionnaireId) {
            $entrantQuery
                ->andWhere('e.gestionnaire  = :gestionnaire')
                ->setParameter('gestionnaire', $gestionnaireId);
        }
        if ($nifFilter) {
            $entrantQuery
                ->andWhere('e.nif LIKE :nif')
                ->setParameter('nif', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $entrantQuery
                ->andWhere('e.raisonSocial LIKE :rs')
                ->setParameter('rs', '%' . $rsFilter . '%');
        }
        $entrantQuery->getQuery();
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
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);
        
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
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

        if ($isChefDeDirection || $isChefSAI || $isChefDeService || $isSuperAdmin || $isSystemUser) 
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

            $entrantQueryOk = $em->getRepository(Entrant::class)->findBy(
                ['courrierId'=> $entrantQueryc],
                ['numeroCourrier' => 'DESC']);

            // $entrantQueryOk = $em->getRepository(Entrant::class)->findBy(
            //     array('courrierId'=> $entrantQueryc, 'status'<> 'Nouveau' ),
            //     array('numeroCourrier' => 'DESC')
            // );
            
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQueryOk,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
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

    public function listAssigneAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $userId = $this->getUser()->getId();
        $userManager = $this->get('fos_user.user_manager');
        $userM = $userManager->findUserBy(['id' => $userId]);
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
            ->from(EntrantObservation::class, 'le')
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
 
        $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
        $entrantQueryc =[];
        $courrier = "";
        foreach( $entrantQuery as  $entrant){                
            $courrier = $entrant->getCourrier();
            array_push($entrantQueryc,$courrier);
        }
        $entrantQueryOk = $em->getRepository(Entrant::class)->findBy(
            ['courrierId'=> $entrantQueryc],
            ['numeroCourrier' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $entrants = $paginator->paginate(
            $entrantQueryOk,
            $request->query->getInt('page', 1),
            20
        );

        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $attributions = $em->getRepository(Attribution::class)->findAll();

        $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
        // $courrierEntrantPagination = $this->refreshEntrant($request);
        return $this->render('DBundle:Entrant:list.html.twig', array(
            'courriers' => $entrants,
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
            'isSuperAdmin' => $isSuperAdmin,
            'isAdmin' => $isAdmin,
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
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(CategorieCourierEntrant::class)->createQueryBuilder('c')->getQuery();
        
        $paginator  = $this->get('knp_paginator');
        $categorie = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Entrant:categorie\stat_cat.html.twig', array(
            'categories' => $categorie
        ));
    }

    public function setStatAction()
    {
        $em = $this->getDoctrine()->getManager();
        $allCat = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        for($i = 1;$i <= 12;$i++){
            foreach($allCat as $item){
                $comment = $item->getNom();
                $month = $i;
                $courrierEntrants = $em->getRepository(Entrant::class)->getByComment($comment,$month);
                $theCat = $em->getRepository(CategorieCourierEntrant::class)->findOneBy(['nom' => $comment]);
                switch ($i){
                    case 1:
                        $theCat->setNbcourrier01(count($courrierEntrants));
                        break;
                    case 2:
                        $theCat->setNbcourrier02(count($courrierEntrants));
                        break;
                    case 3:
                        $theCat->setNbcourrier03(count($courrierEntrants));
                        break;
                    case 4:
                        $theCat->setNbcourrier04(count($courrierEntrants));
                        break;
                    case 5:
                        $theCat->setNbcourrier05(count($courrierEntrants));
                        break;
                    case 6:
                        $theCat->setNbcourrier06(count($courrierEntrants));
                        break;
                    case 7:
                        $theCat->setNbcourrier07(count($courrierEntrants));
                        break;
                    case 8:
                        $theCat->setNbcourrier08(count($courrierEntrants));
                        break;
                    case 9:
                        $theCat->setNbcourrier09(count($courrierEntrants));
                        break;
                    case 10:
                        $theCat->setNbcourrier10(count($courrierEntrants));
                        break;
                    case 11:
                        $theCat->setNbcourrier11(count($courrierEntrants));
                        break;
                    case 12:
                        $theCat->setNbcourrier12(count($courrierEntrants));
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
        $isSuperAdmin = ($userM->hasRole('ROLE_SUPER_ADMIN')) ? true : false;
        $isAdmin = ($userM->hasRole('ROLE_ADMIN')) ? true : false;

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        $type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $categories = $em->getRepository(CategorieCourierEntrant::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $attribution = $request->query->get('attribution');
        $categorie = $request->query->get('categorie');

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $moisCourr = $defaultYear->format('m');

        if ($isChefSAI || $isChefDeDirection || $isSuperAdmin || $isAdmin || $isSystemUser ) 
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
            }elseif ($categorie) {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findByCategorie($thisService ,$categorie);
                //     array('courrierId'=> $entrantQueryc, 'commentaires'=> $categorie ),
                //     array('numeroCourrier' => 'DESC')
                // );
            }else {
                $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
                    array('courrierId'=> $entrantQueryc),
                    array('numeroCourrier' => 'DESC')
                );
            }

            // $entrantQueryOkey = $em->getRepository(Entrant::class)->findBy(
            //     array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis' ),
            //     array('numeroCourrier' => 'DESC')
            // );
        
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQueryOkey,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
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
            
            $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e')
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

            $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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

                $status = "Transmis";
                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierEntrantPagination = $this->refreshEntrant($request);
                return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
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

            $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
                'courriers' => $courrierEntrantPagination,
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
            $documentsQuery = $em->getRepository(Entrant::class)->createQueryBuilder('e');
            $documentsQuery
                ->andWhere('e.service = :service')
                ->setParameter('service', $user->getService())
                ->distinct('e.numero');
            $entrantQuery = $em->getRepository(Service::class)
                ->find($user->getService()->getId())
                ->getEntrant();

            if ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) 
            {
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
                        array('courrierId'=> $entrantQueryc, 'status'=> 'Transmis'),
                        array('numeroCourrier' => 'DESC')
                    );
                }

                $paginator  = $this->get('knp_paginator');
                $entrants = $paginator->paginate(
                    $entrantQueryOkey,
                    $request->query->getInt('page', 1),
                    20
                );

                $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
                // $courrierEntrantPagination = $this->refreshEntrant($request);
                return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
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

            $entrantQuery = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
            $paginator  = $this->get('knp_paginator');
            $entrants = $paginator->paginate(
                $entrantQuery,
                $request->query->getInt('page', 1),
                20
            );

            $gestionnaire = $em->getRepository(User::class)->findOneBy(array('id' => $gestionnaireId));
            // $courrierEntrantPagination = $this->refreshEntrant($request);
            return $this->render('DBundle:Entrant:statParPeriode.html.twig', array(
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
