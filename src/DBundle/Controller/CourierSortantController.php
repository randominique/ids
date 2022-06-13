<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use DBundle\Entity\CourierSortant;
use DBundle\Entity\SaiSetting;
use DBundle\Entity\Sortant;
use DBundle\Entity\User;
use DBundle\Entity\Dge;
use DBundle\Entity\CategorieCourierSortant;

use NIFBundle\Entity\Clients as NIFOnlineClients;

use SIGTASBundle\Entity\DocCourrier;
use SIGTASBundle\Entity\Document;
// use SIGTASBundle\Entity\Clients as TaxPayer;
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
use SIGTASBundle\Entity\SectorActivity;
use SIGTASBundle\Entity\SECTOR_ACTIVITY;

use DBundle\Form\CourierSortantType;
use DBundle\Form\CategorieCourierSortantType;

class CourierSortantController extends Controller
{

    public function refreshSortrant(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $status = $request->query->get('status');
        $priority = $request->query->get('priority');
        //$type = $request->query->get('type');
        $gestionnaireId = $request->query->get('gestionnaire');
        $serviceId = $request->query->get('service');
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $courrierSortants = $em->getRepository(Sortant::class)->createQueryBuilder('s')
        ->addOrderBy('s.createdAt','DESC')
        ->addOrderBy('s.numeroCourrier','DESC');
        if($date_du && $date_au)
        {
            $courrierSortants
            ->andWhere('s.createdAt BETWEEN :date_du AND :date_au')
            ->setParameter('date_du', $date_du)
            ->setParameter('date_au', $date_au);
        }

        if($nifFilter)
        {
            $courrierSortants
            ->andWhere('s.nif LIKE :nif')
            ->setParameter('nif', '%'.$nifFilter.'%');
        }
        if($rsFilter)
        {
            $courrierSortants
            ->andWhere('s.raisonSocial LIKE :rs')
            // ->andWhere('s.raisonSocial IS NOT NULL')
            ->setParameter('rs', '%'.$rsFilter.'%');
        }

        $courrierSortants->getQuery();

        $paginator  = $this->get('knp_paginator');
        $courrierSortantPagination = $paginator->paginate(
            $courrierSortants,
            $request->query->getInt('page', 1),
            30
        );

        return $courrierSortantPagination;
    }

    public function ListAction(Request $request)
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
        $dir = $em->getRepository(dge::class)->findOneBy(
            [],
            [
                'id' => 'desc'
            ]
        );
        $user = $this->getUser();
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId()))?true:false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId())?true:false;
        $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $isMembreSAI = (( $user->getService()->getId() == $sai->getService()->getId()))?true:false;
        // $isMembreDirection = (($dir->getUser()->getId() == $user->getId()))?true:false;  // il suffisait de copier-coller la ligne suivante dans SortantController
        $isMembreDirection = ($user->getService()->getId() == 4)?true:false;
        $userServiceId = (( $user->getService()->getId() == $sai->getService()->getId()))?true:false;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findBy(array(),array('sectorActDesc'=>'ASC'));

        $sortantCheck = $em->createQueryBuilder();
        $sortantCheck->select('count(sortant.numeroCourrier)');
        $sortantCheck->from(Sortant::class,'sortant');
        $sortantCount = $sortantCheck->getQuery()->getSingleScalarResult();
        
        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if($sortantCount > 0)
        {
            $sortantLast = $em->createQueryBuilder()
            ->select('MAX(le.numeroCourrier)')
            ->from(Sortant::class,'le')
            ->where('le.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->getQuery()
            ->getSingleScalarResult();
            
            if (!$sortantLast)
            {
                $sortantLast = 0;
            }

            $newCourrierSortants = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('n')
                ->where('n.numero > :lastNumero')
                ->setParameter('lastNumero', $sortantLast)
                ->andWhere('n.typeCourrier = :sortant')
                ->setParameter('sortant' , 'S')
                ->andWhere('n.yearCourr = :yearCourr')
                ->setParameter('yearCourr', $yearCourr)
                ->orderBy('n.numero', 'ASC')
                ->getQuery()
                ->getResult();


            if($newCourrierSortants)
            {
                foreach($newCourrierSortants as $key => $newSortant)
                {
                    
                    $docCourrier = $this->getSortant($newSortant->getDocNo());
                    
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
                    $newSortant->setCourrierId($docCourrier->getDocNo());
                    $newSortant->setService($user->getService());
                    $newSortant->setYearCourr($docCourrier->getYearCourr());
                    // pour debug
                    
                    $em->persist($newSortant);
                    $em->flush();

                }
                $courrierSortantPagination = $this->refreshSortrant($request);
              
                return $this->render('DBundle:CourierSortant:list.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'userServiceId' => $userServiceId,
                    'sectorActs' => $sectorActs,
                    'usersService' => $responsableQuery,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs')
                ));
            }

            $courrierSortantPagination = $this->refreshSortrant($request);

            return $this->render('DBundle:CourierSortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs')
            ));
        }else
        {
            $docCourriersQuery = $sigtas_em->getRepository(DocCourrier::class)
                ->createQueryBuilder('s')
                ->where('s.typeCourrier LIKE :typeCourrier')
                ->setParameter('typeCourrier', "S")
                ->andWhere('s.yearCourr = :yearCourr')
                ->setParameter('yearCourr', $yearCourr)
                ->getQuery();

            foreach ($docCourriersQuery->getResult() as $key => $docCourrier) 
            {
                $sortantInfos = $this->getSortant($docCourrier->getDocNo());
                
                $newSortant = new Sortant;
                $newSortant->setRaisonSocial($sortantInfos->rs);
                $newSortant->setNif($sortantInfos->nif);
                $newSortant->setTitre($sortantInfos->titre);
                $newSortant->setObjetCourrier($docCourrier->objet);
                $newSortant->setNumeroCourrier($docCourrier->getNumero());
                $newSortant->setAuteur($this->getUser());
                //$newSortant->setUpdatedAt(new \DateTime());
                $newSortant->setCreatedAt($sortantInfos->createdDate);
                $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());           
                $newSortant->setCourrierId($docCourrier->getDocNo());
                $newSortant->setService($user->getService());
                $newSortant->setYearCourr($docCourrier->getYearCourr());

                $em->persist($newSortant);
                $em->flush();
            }
            
            $courrierSortantPagination = $this->refreshSortrant($request);
           
            return $this->render('DBundle:CourierSortant:list.html.twig', array(
                'courriers' => $courrierSortantPagination,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,                
                'usersService' => $responsableQuery,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs')
            ));
        }
    }

    public function ListPdfAction(Request $request)
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
        $user = $this->getUser();
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId()))?true:false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId())?true:false;
        $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $isMembreSAI= (( $user->getService()->getId() == $sai->getService()->getId()))?true:false;

        
        $sortantCheck = $em->createQueryBuilder();
        $sortantCheck->select('count(sortant.numeroCourrier)');
        $sortantCheck->from(Sortant::class,'sortant');
        $sortantCount = $sortantCheck->getQuery()->getSingleScalarResult();
    
        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        if($sortantCount > 0)
        {
            $sortantLast = $em->createQueryBuilder()
            ->select('MAX(le.numeroCourrier)')
            ->from(Sortant::class,'le')
            ->where('le.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->getQuery()
            ->getSingleScalarResult();

            $newCourrierSortants = $sigtas_em->getRepository(DocCourrier::class)->createQueryBuilder('n')
            ->where('n.numero > :lastNumero')
            ->setParameter('lastNumero', $sortantLast)
            ->andWhere('n.typeCourrier = :sortant')
            ->setParameter('sortant' , 'S')
            ->andWhere('n.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->orderBy('n.numero', 'ASC')
            ->getQuery()
            ->getResult();


            if($newCourrierSortants)
            {
                foreach($newCourrierSortants as $key => $newSortant)
                {
                    $docCourrier = $this->getSortant($newSortant->getDocNo());
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
                    $newSortant->setCourrierId($docCourrier->getDocNo());
                    $newSortant->setService($user->getService());
                    $newSortant->setYearCourr($docCourrier->getYearCourr());
                    // pour debug
                    
                    $em->persist($newSortant);
                    $em->flush();

                    $courrierSortantPagination = $this->refreshSortrant($request);

                }
                $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('IDS');
                $pdf->SetTitle(('courriers sortants'));
                $pdf->SetSubject('courriers sortants');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('helvetica', '', 10, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();
                
                $filename = 'CourrierSortant';
                $html= $this->render('DBundle:CourierSortant:listpdf.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'isMembreSAI' => $isMembreSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'userServiceId' => $userServiceId,
                    'sectorActs' => $sectorActs,
                    'usersService' => $responsableQuery,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs')
                    
                ));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->Output($filename.".pdf",'I');
                
            }

            $courrierSortantPagination = $this->refreshSortrant($request);


            $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetAuthor('Our Code World');
                $pdf->SetTitle(('Our Code World Title'));
                $pdf->SetSubject('Our Code World Subject');
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('helvetica', '', 10, '', true);
                //$pdf->SetMargins(20,20,40, true);
                $pdf->AddPage();
                
                $filename = 'courrierSortant';
                $html= $this->render('DBundle:CourierSortant:listpdf.html.twig', array(
                    'courriers' => $courrierSortantPagination,
                    'date_du'   => $request->query->get('date_du'),
                    'date_au'   => $request->query->get('date_au'),
                    'isChefSAI' => $isChefSAI,
                    'isChefDeService' => $isChefDeService,
                    'nifFilter' => $request->query->get('nif'),
                    'rsFilter' => $request->query->get('rs')
                ));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->Output($filename.".pdf",'I');
            
        }else
        {
            $docCourriersQuery = $sigtas_em->getRepository(DocCourrier::class)
            ->createQueryBuilder('s')
            ->where('s.typeCourrier LIKE :typeCourrier')
            ->setParameter('typeCourrier', "S")
            ->andWhere('s.yearCourr = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->getQuery();

            foreach ($docCourriersQuery->getResult() as $key => $docCourrier) 
            {
                $sortantInfos = $this->getSortant($docCourrier->getDocNo());
                
                $newSortant = new Sortant;
                $newSortant->setRaisonSocial($sortantInfos->rs);
                $newSortant->setNif($sortantInfos->nif);
                $newSortant->setTitre($sortantInfos->titre);
                $newSortant->setObjetCourrier($docCourrier->objet);
                $newSortant->setNumeroCourrier($docCourrier->getNumero());
                $newSortant->setAuteur($this->getUser());
                //$newSortant->setUpdatedAt(new \DateTime());
                $newSortant->setCreatedAt($sortantInfos->createdDate);
                $newSortant->setObjectId($docCourrier->getDocCourrierObjectNo());           
                $newSortant->setCourrierId($docCourrier->getDocNo());
                $newSortant->setService($user->getService());
                $newSortant->setYearCourr($docCourrier->getYearCourr());

                $em->persist($newSortant);
                $em->flush();
            }
            
            
            $courrierSortantPagination = $this->refreshSortrant($request);


            $pdf = $this->get("white_october.tcpdf")->create($orientation = 'L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetAuthor('Our Code World');
            $pdf->SetTitle(('Our Code World Title'));
            $pdf->SetSubject('Our Code World Subject');
            $pdf->setFontSubsetting(true);
            $pdf->SetFont('helvetica', '', 10, '', true);
            //$pdf->SetMargins(20,20,40, true);
            $pdf->AddPage();
            
            $filename = 'courrierSortant';
            $html= $this->render('DBundle:CourierSortant:listpdf.html.twig', array(
                'courriers' => $courrierSortantPagination,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,                
                'usersService' => $responsableQuery,
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs')
            ));
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $pdf->Output($filename.".pdf",'I'); 

           
        }
    }

        ////// test /////
    public function getSortant($sortantDocNo)
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
        $user = $this->getUser();
        // $entrantDuService= $em->getRepository(Sortant::class)->findOneBy(['service' => $user->getService()->getId()]);
        $isMembreSAI= (( $user->getService()->getId() == $sai->getService()->getId()))?true:false;
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId()))?true:false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId())?true:false;

        $query = $sigtas_em->getRepository(DocCourrier::class)
        ->createQueryBuilder('d')
        ->where('d.docNo = :docNo')
        ->setParameter('docNo', $sortantDocNo)
        ->getQuery()
        ;

        $docCourrier = $query->getOneOrNullResult();

        if($docCourrier)
        {
            $courrierTitre = $sigtas_em->getRepository(DocCourrierTitre::class)->findOneBy(
                array('id' => $docCourrier->getDocCourrierTitreNo()));
            if($courrierTitre)
            {
                $docCourrierTitre = $courrierTitre->getDocCourrierTitre();
            }else{
                $docCourrierTitre = "-";
            }

            $courrierObjet = $sigtas_em->getRepository(DocCourrierObjet::class)->findOneBy(
                array('id' => $docCourrier->getDocCourrierObjectNo()));
            if($courrierObjet)
            {
                $docCourrierObjet = $courrierObjet->getDocCourrierObjet();
            }else{
                $docCourrierObjet = "-";
            }

            $document = $sigtas_em->getRepository(Document::class)->findOneBy(
                array('docNo' => $docCourrier->getDocNo()));
            if($document)
            {

                $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                    array('taxPayerNo' => $document->getTaxPayerNo()));
                $documentCreatedDate = $document->getCreatedDate();
                if($sigtasInfos)
                {
                    $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                        array('nif' => $sigtasInfos->getNif())
                    );
                        
                    if($nifInfos){
                        $documentNif = $nifInfos->getNif();
                        $documentRs = $nifInfos->getRs();
                        $taxpayer = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                            //'nif' => $nifInfos->getNif()
                            'nif' => $documentNif
                        ]);
                        if($taxpayer!=null){
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
                        }else{
                            $documentSensitive = '-'; 
                            $documentSecteurActivite = '-'; 
                            $documentRegimeFiscal = '-'; 
                        }
                    }else{
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
            }else{
                $documentNif =  '-'; 
                $documentRs = '-'; 
                $documentSensitive = '-'; 
                $documentSecteurActivite = '-'; 
                $documentRegimeFiscal = '-';
                $documentCreatedDate ='-';
            }
            $docCourrier->createdDate = $documentCreatedDate;
            $docCourrier->nif = $documentNif;
            $docCourrier->rs = $documentRs;
            $docCourrier->titre = $docCourrierTitre;
            $docCourrier->objet = $docCourrierObjet;
            $docCourrier->sensitive = $documentSensitive;
        }
        return $docCourrier;
    }

    public function ListOldAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) {
            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            $query = $em->getRepository(CourierSortant::class)
            ->createQueryBuilder('c')
            ->where('c.date BETWEEN :date_du AND :date_au')
            ->setParameter('date_du', $date_du)
            ->setParameter('date_au', $date_au)
            ->orderBy('c.date', 'asc')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }
        else
        {
            $query = $em->getRepository(CourierSortant::class)
            ->createQueryBuilder('c')
            ->orderBy('c.date', 'desc')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }

        return $this->render('DBundle:CourierSortant:list.html.twig', [
            'couriers' => $couriers,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ]);
    }

    public function NewCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $CourierSortant = new CategorieCourierSortant();

        $form = $this->createForm(CategorieCourierSortantType::class, $CourierSortant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($CourierSortant);
            $em->flush();

            return $this->redirectToRoute('_list_categorie_sortant');
        }

        return $this->render('DBundle:CourierSortant:new-categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ListCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) {
            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            $query = $em->getRepository(CategorieCourierSortant::class)
            ->createQueryBuilder('n')
            ->innerJoin('n.couriers', 'c')
            ->where('c.date BETWEEN :date_du AND :date_au')
            ->setParameter('date_du', $date_du)
            ->setParameter('date_au', $date_au)
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $categories = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );

            foreach ($categories as $key => $categorie) {
                $req = $em->getRepository(CourierSortant::class)
                ->createQueryBuilder('c')
                ->select('COUNT(c.id)')
                ->where('c.date BETWEEN :date_du AND :date_au')
                ->andWhere('c.categorie = :cateogorie')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au)
                ->setParameter('cateogorie', $categorie->getId())
                ->getQuery()->getSingleScalarResult();
                $categorie->setNbr($req);   
            }
        }
        else
        {
            $query = $em->getRepository(CategorieCourierSortant::class)
            ->createQueryBuilder('c')
            ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $categories = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }

        return $this->render('DBundle:CourierSortant:list-categorie.html.twig', [
            'categories' => $categories,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ]);
    }

    public function DetailOneAction($id_cr_sort){
        $em = $this->getDoctrine()->getManager();
        $courrier = $em->getRepository(Sortant::class)->find($id_cr_sort);
        return $this->render('DBundle:CourierSortant:detail-one.html.twig', [
            'courrier' => $courrier
        ]);
    }

    public function autoCompleNifAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if(isset($_GET["term"])){
            $nif = $_GET["term"];
            $entrants = $em->getRepository(Sortant::class)->getByNif($nif);
            $output = [];
            foreach($entrants as $entrant){
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');
                $nifFormated = number_format($entrant->getNif(),0 , '.', ' '); 
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSocial();
                $temp_array['useIt'] = $createdAt.' - '.$nifFormated.' - '.$entrant->getRaisonSocial(). '';
                
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
        if(isset($_GET["term"])){
            $rsoc = $_GET["term"];
            $entrants = $em->getRepository(Sortant::class)->getByRsoc($rsoc);
            $output = [];
            foreach($entrants as $entrant){
                $createdAt = date_format($entrant->getCreatedAt(), 'd-m-Y');                
                $nifFormated = number_format($entrant->getNif(),0 , '.', ' ');        
                $temp_array = array();
                $temp_array['thisNif'] = $entrant->getNif();
                $temp_array['raisonSoncial'] = $entrant->getRaisonSocial();
                $temp_array['useIt'] = $nifFormated.' - '.$entrant->getRaisonSocial(). ' - '.$createdAt;
                
                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('list_entrant');
    }

    public function statCatAction(Request $request)
    {
        $this->setStatAction();
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
    
}
