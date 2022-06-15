<?php

namespace DBundle\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\PropertyAccess\PropertyAccess;

use DBundle\Entity\Communication;
use DBundle\Entity\Contribuables;
use DBundle\Form\CommunicationType;
use DBundle\Entity\User;
use DBundle\Entity\CourierEntrant;
use DBundle\Entity\Entrant;
use DBundle\Entity\EntrantObjet;
use DBundle\Entity\RARSANSPERIODE;
use NIFBundle\Entity\Clients as NIFOnlineClients;

// use SIGTASBundle\Entity\Clients as TaxPayer;
use SIGTASBundle\Entity\TaxationOffice;
use SIGTASBundle\Entity\Assessment;
use SIGTASBundle\Entity\DocAmount;
use SIGTASBundle\Entity\TAX_ACCOUNT;
use SIGTASBundle\Entity\TaxPayer;
use SIGTASBundle\Entity\TaxType;
use SIGTASBundle\Entity\Enterprise;
use SIGTASBundle\Entity\Paiment;
use SIGTASBundle\Entity\CarteStat;
use SIGTASBundle\Entity\Document;
use SIGTASBundle\Entity\SECTOR_ACTIVITY;
use SIGTASBundle\Entity\FiscalRegime;
use SIGTASBundle\Entity\PAIEMENT;
use SIGTASBundle\Entity\RAR_PERIODE;
use SIGTASBundle\Entity\RAR_SANS_PERIODE;
use SIGTASBundle\Entity\Titre_perception;

use Doctrine\DBAL\Event\Listeners\OracleSessionInit;

class GestionDesActivitesController extends Controller
{

    // TO: TAXATION D'OFFICE

    // public function TOSurDeclarationPeriodqueAction(Request $request)
    // {
    //     return $this->render('DBundle:GestionDesActivites:to_sur_declaration_periodque.html.twig', array(
    //         // ...
    //     ));
    // }

    public function TOSurDeclarationPeriodiqueAEtablirAction(Request $request)
    {
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) {
            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
            
        }
        else{
            $date_du = new \DateTime('now - 3 year');
            $date_au = new \DateTime();
        }

        $d = $date_du->format('d');
        $m = $date_du->format('M');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $m_au = $date_au->format('M');
        $y_au = $date_au->format('y');
        $d_au = $date_au->format('d');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au;
          
        $qr = $sigtas_em->getRepository(TaxationOffice::class)
        ->createQueryBuilder('t')
        ->orderBy('t.tperYear' , 'DESC')
        ->andWhere('t.entryDate BETWEEN :date_du AND :date_au')
        ->setParameter('date_du', $dmY)
        ->setParameter('date_au', $dmYau)
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        );  

        foreach ($pagination->getItems() as $key => $to) 
        {
            $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                array('nif' => $to->getNif())
            );
            if($nifInfos)
            {
                ////// pas la peine il y a un type d'impôt dans la table Taxation d'office
                $regimeFiscalExist = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                    'nif' => $to->getNif()
                ]);
                if($regimeFiscalExist)
                {
                    $regimeFiscalNo = $regimeFiscalExist->getRegimeFiscal();
                    $regimeFiscalDesc = $sigtas_em->getRepository(FiscalRegime::class)->findOneBy([
                        'fiscalRegimeNo' => $regimeFiscalNo
                    ]);
                    $documentInfosFiscalRegime = $regimeFiscalDesc->getFiscalRegimeDesc();
                }else{
                    $documentInfosFiscalRegime = null;
                }
                ///////le dessus éventuellement à effacer 

                $documentInfosRs = $nifInfos->getRs();
                $documentInfosTypeEse = $nifInfos->getCgdesignation();
            // }else{
            //     $documentInfosRs = null;
            }
            $to->regimeFiscalClair = $documentInfosFiscalRegime;
            $to->rs = $documentInfosRs;  
            $to->typeEse = $documentInfosTypeEse;  
        }

        return $this->render('DBundle:GestionDesActivites:to_sur_declaration_periodique_a_etablir.html.twig', array(
            'pagination' => $pagination
        ));
    }
    public function TOSurDeclarationPeriodiqueAEtablirPdfAction(Request $request)
    {
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) {
            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
            
        }
        else{
            $date_du = new \DateTime('now - 3 year');
            $date_au = new \DateTime();
        }

        $d = $date_du->format('d');
        $m = $date_du->format('M');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $m_au = $date_au->format('M');
        $y_au = $date_au->format('y');
        $d_au = $date_au->format('d');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au;

        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
          
        $qr = $sigtas_em->getRepository(TaxationOffice::class)
        ->createQueryBuilder('t')
        ->orderBy('t.tperYear' , 'DESC')
        ->andWhere('t.entryDate BETWEEN :date_du AND :date_au')
        ->setParameter('date_du', $dmY)
        ->setParameter('date_au', $dmYau)
        ->getQuery()
        // ->getResult()
        ;
// dump($qr);die();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        );  

        foreach ($pagination->getItems() as $key => $to) 
        {
            $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                array('nif' => $to->getNif())
            );
            if($nifInfos)
            {
                ////// pas la peine il y a un type d'impôt dans la table Taxation d'office
                $regimeFiscalExist = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                    'nif' => $to->getNif()
                ]);
                if($regimeFiscalExist)
                {
                    $regimeFiscalNo = $regimeFiscalExist->getRegimeFiscal();
                    $regimeFiscalDesc = $sigtas_em->getRepository(FiscalRegime::class)->findOneBy([
                        'fiscalRegimeNo' => $regimeFiscalNo
                    ]);
                    $documentInfosFiscalRegime = $regimeFiscalDesc->getFiscalRegimeDesc();
                }else{
                    $documentInfosFiscalRegime = null;
                }
                ///////le dessus éventuellement à effacer 


                $documentInfosRs = $nifInfos->getRs();
            }else{
                $documentInfosRs = null;
            }
            $to->regimeFiscalClair = $documentInfosFiscalRegime;
            $to->rs = $documentInfosRs;  

        }
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage('L','A4');
        
        $filename = 'ourcodeworld_pdf_demo';
       
        $html= $this->render('DBundle:GestionDesActivites:to_sur_declaration_periodique_a_etablirpdf.html.twig', array(
            'pagination' => $pagination
        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I');
        
    }

    public function TOSurDeclarationRealiseesAction(Request $request)
    {
        // TaxationOffice new TaxationOffice au lieu de Assessment
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nif_em = $this->getDoctrine()->getManager('nifonline');

        $nif = $request->query->get('nif');
        $rs = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $query = $sigtas_em->getRepository(TaxationOffice::class)
        ->createQueryBuilder('a')
        ->orderBy('a.nif', 'ASC');

        if($nif)
        {
            // $query
            // ->andWhere('a.nif = :nif')
            // ->setParameter('nif', $nif );

            $qr = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nif
            ]);
            if($qr)
            {
                $query
                ->andWhere('a.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qr->getId());
            }

        }

        if($typeImpot)
        {
            $query
            ->andWhere('a.taxTypeDescF = :taxType')
            ->setParameter('taxType', $typeImpot );
        }
        
        $query->getQuery();

        $paginator  = $this->get('knp_paginator');
        $assessments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );    

        foreach ($assessments->getItems() as $key => $assessment)
        {
            $taxPayerInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('nif' => $assessment->getNif()));

            if($taxPayerInfos)
            {
                $assessmentNif = $taxPayerInfos->getNif();
                $assessment->Nif = $assessmentNif;

                $nifInfos = $sigtas_em->getRepository(Enterprise::class)->findOneBy(
                    array('taxPayerNo' => $taxPayerInfos->getTaxPayerNo())
                );
                if($nifInfos)
                {
                    // $documentInfosNif = $nifInfos->getNif();
                    $assessment->rs = $nifInfos->getRegistName();
                }

                // $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                //     'id' => $assessment->getTaxTypeNo()
                // ]);
                // if($taxTypeTrouver)
                // {
                //     $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
                // }else
                // {
                //     $assessmentTaxTypeDesc = null;
                // }
                $assessment->taxTypeDesc = $typeImpot;

                // $enterpriseInfos = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                //     'taxPayerNo' => $sigtasInfos->getId()
                // ]);

                // if($enterpriseInfos)
                // {
                //     $sectorActiviteDesc = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                //         'id' => $enterpriseInfos->getSectorActNo()
                //     ]);
                //     if($sectorActiviteDesc)
                //     {
                //         $sectorActiviteDescTemp = $sectorActiviteDesc->getSectorActDesc();
                //     }
                //     $Assessment->secteurActivite = $sectorActiviteDescTemp;
                // }
            }

            // $documentInfos->nif = $documentInfosNif;
            // $documentInfos->rs = $documentInfosRs;
            // $documentInfos->sensitive = $taxpayer->sensitive;
            // $documentInfos->regimeFiscal = $taxpayer->regimeFiscal;
            // $documentInfos->secteurActivite = $taxpayer->secteurActivite;        
            // $documentInfos = $sigtas_em->getRepository(Document::class)->findOneBy(
            //     array('taxPayerNo' => $Assessment->taxPayerNo));
            // if($documentInfos)
            // {            
            // }
            // $taxationDoffice->nif = $documentInfos->nif;
            // $taxationDoffice->rs = $documentInfos->rs;
            // $Assessment->secteurActivite = $sectorActiviteDescTemp;
            // $Assessment->sensitive = $documentInfos->sensitive;
            // $Assessment->regimeFiscal = $documentInfos->regimeFiscal;
            // $Assessment->secteurActivite = $documentInfos->secteurActivite;

        }   

        // $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        // $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        // $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findAll();   // avant (combo non triée)
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));
        // $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findBy(array(),array('sectorActDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:to_sur_declaration_realisees.html.twig', array(
            // 'pagination' => $pagination,
            // 'documentInfos' => $documentInfos,
            'assessments' => $assessments,
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'nif' => $nif,
            'rs' => $rs,
            // 'usersService' => $responsableQuery,
            // 'sectorActs' => $sectorActs
        ));

    }

    public function DefaillantsChroniqueAction(Request $request)
    {
    /*
    -	Les défaillants chroniques (3 déclarations manquantes ou plus), les récidivistes
    -	Evolution des dépôts : par NIF, par impôts, entre deux dates, en nombre, par montant
    -	Dépôts AVANT échéance, par NIF, par impôts, entre deux dates, en nombre, par montant
    -	Dépôts APRES échéance, par NIF, par impôts, entre deux dates, en nombre, par montant
    -	Récidivistes (non dépôt supérieur à 2 dans l’années par impôts
    -	Dialogue de gestion (discussion avec le représentant de la société) :
    -	Variation en mois de la déclaration (paramétrable : 5% ou 10%)
    -	Suite de l’action (paiement, ou réponse du contribuable)
         */
        
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $query = $sigtas_em->getRepository(Assessment::class)->createQueryBuilder('a');
        $query->where('a.tpPaymentDate >= a.tpDueDate');

        
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) 
        {
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
        }else
        {
            $date_du = new \DateTime('now - 1 month'); 
            $date_au = new \Datetime();
        }    
        
        $d = $date_du->format('d');
        $m = $date_du->format('M');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $m_au = $date_au->format('M');
        $y_au = $date_au->format('y');
        $d_au = $date_au->format('d');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au; 
        
        $query->andWhere('a.entryDate BETWEEN :date_du AND :date_au')
        ->setParameter('date_du', $dmY )
        ->setParameter('date_au', $dmYau);
        $query->getQuery(); 

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );          
        
        return $this->render('DBundle:GestionDesActivites:defaillants_chronique.html.twig', array(
            'contribuables' => $pagination,
            'date_du'   => $dmY,
            'date_au'   => $dmYau
        ));
    }


    public function contribuablesAvecAnomaliesTVAAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:contribuables_avec_anomalies_tva.html.twig', array(
            // ...
        ));
    }

    public function CoherenceNombreDeDossierAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $secteur = $request->query->get('secteur');
        $gestionnaire = $request->query->get('gestionnaire');
        // $gestionnaire_assign = $request->query->get('gestionnaire_assign');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findBy(array(),array('sectorActDesc'=>'ASC'));
        $secteurEnClair = Null;

        // $qr = $em->getRepository(Contribuables::class)
        //     ->createQueryBuilder('n')
        //     ->where('n.inactifDate IS null');

        $qr = $em->createQueryBuilder()
            ->select('n')    
            ->from(Contribuables::class, 'n')
            ->orderBy('n.id', 'DESC');

        if ($nifFilter) {
            $qr
                ->where('n.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }

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
                ->getQuery()
                ->getResult();

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
        return $this->render('DBundle:GestionDesActivites:coherence_nombre_de_dossier.html.twig', [
            'contribuables' => $pagination,
            // 'nifLink' => $nifLink,
            'secteur' => $secteurEnClair,
            // 'secteur' => $secteur,
            'nifFilter'   => $request->query->get('nif'),
            'rsFilter'   => $request->query->get('rs'),
            'sectorActs' => $sectorActs,
            'usersService' => $responsableQuery,
            'allUsers' => $allUsers,
            'gestionnaire' => $gestionnaire_nom,
        ]);
    }

    public function PdfcreteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $nif = $request->query->get('nif');
        $rs = $request->query->get('rs');
        $gestionnaire_filtre = $request->query->get('gestionnaire_filtre');
        $gestionnaire_assign = $request->query->get('gestionnaire_assign');


        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        
        $qr = $sigtas_em->getRepository(TaxPayer::class)
        ->createQueryBuilder('n')
        ->where('n.inactifDate IS null');

        if($nif)
        {
            $qr
            ->andWhere('n.nif LIKE :nifParam')
            ->setParameter('nifParam', '%'.$nif.'%');
        }
        if($rs)
        {
            $qr
            ->andWhere('n.rs LIKE :rsParam')
            ->setParameter('rsParam', '%'.$rs.'%');
        }

        $qr->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            50
        );       
        
        foreach ($pagination->getItems() as $key => $contribuable) {

            // Récupérations des informations RS et NomCOmmmercial Phone email dans la base NIF
            // Récupération des éléments par correspndances NIF
            
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

            //S'il y a correspondance assigner mettre les inforamtions dans des variables
            //à faire en array si maitriser
           if($nifInfos!=null)
           {
                $sigtasRs = $nifInfos->getRs();
                $sigtasNc = $nifInfos->getNomcommercial();
                $sigtasMail = $nifInfos->getEmail();
                $sigtasPhone = $nifInfos->getContactPhone();
                //$sigtasStartDate = $nifInfos->getStartDate();

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
                    'taxPayerNo' => $contribuable->getId()
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
                    $enterpriseSect2 = $enterpriseSect1;
                    $enterpriseStartDate = $enterprise->getStartDate();
                }else{
                    $enterpriseStartDate = null;
                }$enterpriseSect = $enterpriseSect2;

            }else{
                $enterpriseSect= null;
                $enterpriseStartDate = null;
            }
            $contribuable->sectorActivite = $enterpriseSect;
            $contribuable->startDate = $enterpriseStartDate;
            $contribuable->setSigtasRs($sigtasRs);
            $contribuable->setSigtasMail($sigtasMail);
            $contribuable->setSigtasNc($sigtasNc);
            $contribuable->setSigtasPhone($sigtasPhone);
            //assignation des valeurs de RS Mail NomCommercial Phone
        }
        $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $allUsers = $em->getRepository(User::class)->findAll();
        
        //pdf
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage('L','A4');
        $filename = 'ourcodeworld_pdf_demo';
        $html= $this->render('DBundle:GestionDesActivites:coherence_nombre_de_dossierpdf.html.twig', [
        'contribuables' => $pagination,
        'sectorActs' => $sectorActs,
        /*
        'date_du'   => $request->query->get('date_du'),
        'date_au'   => $request->query->get('date_au'),
        */
        'nif'   => $request->query->get('nif'),
        'rs'   => $request->query->get('rs'),
        // 'sectorActs' => $sectorActs,
        'usersService' => $responsableQuery,
        'allUsers' => $allUsers
        ]);

        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        // $content=$html->getContent();
        // $pdf -> writeHTML($content);
        $pdf->Output($filename.".pdf",'I');
        //pdf 
    }

    public function MouvementDossiersAuDFUAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:mouvement_dossiers_au_dfu.html.twig', array(
            // ...
        ));
    }

    public function SituationDossiersAuDFUAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:situation_dossiers_au_dfu.html.twig', array(
            // ...
        ));
    }

    public function AssujettissementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $query = $sigtas_em->getRepository(TAX_ACCOUNT::class)
            ->createQueryBuilder('a')
            ->Where('a.closeDate IS null');
        
        if($nifFilter)
        {
            $qr = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qr)
            {
                $query
                ->andWhere('a.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qr->getTaxPayerNo());
            }
        }

        if($rsFilter)
        {
            $qr = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qr)
            {
                $query
                ->andWhere('a.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qr->getTaxPayerNo());
            }
        }

        if($typeImpot)
        {
            $query
            ->andWhere('a.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot );
        }
        
        $query->getQuery();

        $dossierCheck = $sigtas_em->createQueryBuilder()
        ->select('count(r.taxPayerNo)')
        ->from(TAX_ACCOUNT::class, 'r')
        ->Where('r.closeDate IS null');
        $dossierCount = $dossierCheck->getQuery()->getSingleScalarResult();

        $paginator  = $this->get('knp_paginator');
        $assessments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        foreach ($assessments->getItems() as $key => $assessment) 
        {
            $taxPayerInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('taxPayerNo' => $assessment->getTaxPayerNo()));
            if($taxPayerInfos)
            {
                $assessmentNif = $taxPayerInfos->getNif();
                $assessment->nif = $assessmentNif;
            }
  
            $nifInfos = $sigtas_em->getRepository(Enterprise::class)->findOneBy(
                    array('taxPayerNo' => $assessment->getTaxPayerNo())
                );
            $assessment->rs = $nifInfos->getRegistName();

            $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                'id' => $assessment->getTaxTypeNo()
            ]);
            if($taxTypeTrouver)
            {
                $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
            }else
            {
                $assessmentTaxTypeDesc = null;
            }
            $assessment->taxTypeDesc = $assessmentTaxTypeDesc;
        }

        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:assujettissement.html.twig', array(
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'assessments' => $assessments,
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'dossierCount' => $dossierCount,
        ));
    }

    public function assujettissement_excelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'DGE - Liste des assujettissements au ' . $createdAt . '.xlsx';

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $dossiers = $sigtas_em->getRepository(TAX_ACCOUNT::class)
            ->createQueryBuilder('t')
            ->Where('t.closeDate IS null')
            ->getQuery()
            ->getResult();

        // if($nifFilter)
        // {
        //     $qr = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
        //         'nif' => $nifFilter
        //     ]);
        //     if($qr)
        //     {
        //         $dossiers
        //         ->andWhere('a.taxPayerNo = :taxPayerNo')
        //         ->setParameter('taxPayerNo', $qr->getTaxPayerNo());
        //     }
        // }

        // if($rsFilter)
        // {
        //     $qr = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
        //         'nif' => $nifFilter
        //     ]);
        //     if($qr)
        //     {
        //         $dossiers
        //         ->andWhere('a.taxPayerNo = :taxPayerNo')
        //         ->setParameter('taxPayerNo', $qr->getTaxPayerNo());
        //     }
        // }

        // if($typeImpot)
        // {
        //     $dossiers
        //     ->andWhere('a.taxTypeNo = :taxType')
        //     ->setParameter('taxType', $typeImpot );
        // }
    
        // dump($dossiers);  // ne donne rien
        // die();

        $paginator  = $this->get('knp_paginator');
        $dossierQuery = $paginator->paginate(
            $dossiers,
            $request->query->getInt('page', 1),
            10000
        );
    
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les assujettissements de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIF ')
            ->setCellValue('B1', 'Raison sociale ')
            ->setCellValue('C1', 'Type d\'impôt ')
            ->setCellValue('D1', 'Libellé Type d\'impôt ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        // dump($dossierQuery);
        // die();
        foreach ($dossierQuery as $dossier) {
            $taxPayerInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('taxPayerNo' => $dossier->getTaxPayerNo()));
            if($taxPayerInfos)
            {
                $assessmentNif = $taxPayerInfos->getNif();
                $dossier->nif = $assessmentNif;
            }
            $nifInfos = $sigtas_em->getRepository(Enterprise::class)->findOneBy(
                    array('taxPayerNo' => $dossier->getTaxPayerNo())
                );
            $dossier->rs = $nifInfos->getRegistName();

            $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                'id' => $dossier->getTaxPayerNo()
            ]);
            if($taxTypeTrouver)
            {
                $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
            }else
            {
                $assessmentTaxTypeDesc = null;
            }
            $dossier->taxTypeDesc = $assessmentTaxTypeDesc;

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier->nif)
                ->setCellValue('B' . $count, $dossier->rs)
                ->setCellValue('C' . $count, $dossier->getTaxTypeNo())
                ->setCellValue('D' . $count, $dossier->taxTypeDesc);
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Liste des RAR sans période');
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

    public function AssujettissementPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $typeImpot = $request->query->get('typeImpot');
        $query = $sigtas_em->getRepository(Assessment::class)
            ->createQueryBuilder('a');
            
        if($typeImpot)
        {
            $query
            ->andWhere('a.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot );
        }else{
            $query->andWhere('a.taxTypeNo = 23');
        }
        
            $query->getQuery();

        $paginator  = $this->get('knp_paginator');
        $assessments = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            100
        );
        foreach ($assessments->getItems() as $key => $Assessment) {
            $documentInfos = $sigtas_em->getRepository(Document::class)->findOneBy(
                array('taxPayerNo' => $Assessment->taxPayerNo));
            if($documentInfos)
            {            
                $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                        array('id' => $documentInfos->getTaxPayerNo()));
        
                if($sigtasInfos)
                {
                    $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                        array('nif' => $sigtasInfos->getNif())
                    );
                    if($nifInfos)
                    {
                        $documentInfosNif = $nifInfos->getNif();
                        $documentInfosRs = $nifInfos->getRs();
                    }
                    $taxpayer = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                        'nif' => $sigtasInfos->getNif()
                        ]); 
                        
                    if($taxpayer!=null){
                        $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                            'taxPayerNo' => $taxpayer->taxPayerNo
                            ]);
                            /*
                        if($enterprise){
                            $taxpayer->secteurActivite = $enterprise->secteurActivite->sectorActDesc;
                        }else{
                            $taxpayer->secteurActivite = 'NA';
                        }
                        */
                    }
                }
                $documentInfos->nif = $documentInfosNif;
                $documentInfos->rs = $documentInfosRs;
                $documentInfos->sensitive = $taxpayer->sensitive;
                $documentInfos->regimeFiscal = $taxpayer->regimeFiscal;
                //$documentInfos->secteurActivite = $taxpayer->secteurActivite;        
            }
            $Assessment->nif = $documentInfos->nif;
            $Assessment->rs = $documentInfos->rs;
            $Assessment->sensitive = $documentInfos->sensitive;
            $Assessment->regimeFiscal = $documentInfos->regimeFiscal;
            //$Assessment->secteurActivite = $documentInfos->secteurActivite;

        }

        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 9, '', true);
        $pdf->AddPage();
        
        $filename = 'ourcodeworld_pdf_demo';
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findAll();
        
        $html= $this->render('DBundle:GestionDesActivites:assujettissementpdf.html.twig', array(
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'assessments' => $assessments,
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'paginator' => $paginator,

        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I');
       
    }

    public function DeclarationsDepotAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');
        $gestionnaire_filtre = $request->query->get('gestionnaire_filtre');
        $gestionnaire_assign = $request->query->get('gestionnaire_assign');
        $doctypeno = 1;
        $docstateno = 2;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        if ($date_du && $date_au) 
        {            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
        }
        else
        {
            $date_du = new \DateTime('now - 1 month'); 
            $date_au = new \Datetime();
        }
        $d = $date_du->format('d');
        $m = $date_du->format('m');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $d_au = $date_au->format('d');
        $m_au = $date_au->format('m');
        $y_au = $date_au->format('y');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au;
        // dump($date_du, $d, $m, $y, $dmY, $date_au, $d_au, $m_au, $y_au, $dmYau);
        // die();
        // dump($date_du, $date_au);
        // die();
        // dump($date_du, $date_au);
        // die('else');

        $qr = $sigtas_em->getRepository(Document::class)
            ->createQueryBuilder('d')
            ->where('d.receivedDate IS NOT null')
            ->andWhere('d.docTypeNo = :doctypeno')
            ->setparameter('doctypeno', $doctypeno)
            ->andWhere('d.docStateNo = :docstateno')
            ->setparameter('docstateno', $docstateno)
            // ->andWhere('d.docTpDueDate BETWEEN :date_du AND :date_au')
            // ->setParameter('date_du', $dmY)
            // ->setParameter('date_au', $dmYau)
            // ->addOrderBy('d.taxPayerNo','ASC');
            ->orderBy('d.taxPayerNo' , 'ASC')
            ->addOrderBy('d.taxTypeNo' , 'ASC')
            ->addorderBy('d.docTpDueDate' , 'ASC');

        if($nifFilter|$rsFilter)
        {
            $qn = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qn)
            {
                $qr
                ->andWhere('d.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qn->getTaxPayerNo());
            }
        }
        // if ($date_du && $date_au) {
        //     $date_du = new \DateTime($request->query->get('date_du'));
        //     $date_au = new \DateTime($request->query->get('date_au'));
        //     $qr
        //         ->andWhere('d.receivedDate BETWEEN :date_du AND :date_au')
        //         ->setParameter('date_du', $date_du)
        //         ->setParameter('date_au', $date_au);
        // }
        if($typeImpot) {
            $qr
            ->andWhere('d.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot);
        }
            $qr
            ->getQuery();
        
        // $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        // $allUsers = $em->getRepository(User::class)->findAll();

        // return $this->render('DBundle:GestionDesActivites:coherence_nombre_de_dossier.html.twig', [
            // 'contribuables' => $pagination,
            // 'sectorActs' => $sectorActs,
            /*
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            */
            // 'nif'   => $request->query->get('nif'),
            // 'rs'   => $request->query->get('rs'),
            // 'sectorActs' => $sectorActs,
            // 'usersService' => $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
            // 'allUsers' => $allUsers
        // ]);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        ); 

        foreach ($pagination->getItems() as $key => $document) {
            $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('taxPayerNo' => $document->getTaxPayerNo())
            );
            if($sigtasInfos)
            {
                $document->nif = $sigtasInfos->getNif();
                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $sigtasInfos->getNif()) 
                );
                if($nifInfos)
                {
                    $documentRs = $nifInfos->getRs();
                }
                else
                {
                    $documentRs = null;
                }
                $document->rs = $documentRs;
                // $document->regimeFiscal = $sigtasInfos->getRegimeFiscal();

                $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                    'id' => $document->getTaxTypeNo()
                ]);
                if($taxTypeTrouver)
                {
                    $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
                }else
                {
                    $assessmentTaxTypeDesc = null;
                }
                $document->taxTypeDesc = $assessmentTaxTypeDesc;
    
            }
            else 
            {
                $document->nif = 'NA';
                $document->rs = 'NA';
            }
            $document->PaymentDate = $document->getDocTpPaymentDate();
            // dump($document->getDocTpPaymentDate());die();
        }
        $usersService = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:declarations_depot.html.twig', [
            'documents' => $pagination,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'sectorActs' => $sectorActs,
            'usersService' => $usersService,
            'taxTypes' => $taxTypeList,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            // 'usersService' => $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()))
        ]);

    }

    public function DeclarationsDepotPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        
        if ($date_du && $date_au) 
        {            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
            
        }else
        {
            $date_du = new \DateTime('now - 1 month'); 
            $date_au = new \Datetime();
        }
        $d = $date_du->format('d');
        $m = $date_du->format('M');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $m_au = $date_au->format('M');
        $y_au = $date_au->format('y');
        $d_au = $date_au->format('d');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au; 

        
        $qr = $sigtas_em->getRepository(Document::class)
        ->createQueryBuilder('d')
        ->where('d.receivedDate IS NOT null')
        ->andWhere('d.docTpDueDate BETWEEN :date_du AND :date_au')
        ->setParameter('date_du', $dmY)
        ->setParameter('date_au', $dmYau)
        ->orderBy('d.receivedDate' , 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            50
        ); 

        foreach ($pagination->getItems() as $key => $document) {
            $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('id' => $document->getTaxPayerNo())
            );
            if($sigtasInfos)
            {
                $document->nif = $sigtasInfos->getNif();
                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $sigtasInfos->getNif()) 
                );
                if($nifInfos)
                {
                    $documentRs = $nifInfos->getRs();
                }
                $document->rs = $documentRs;
                $document->regimeFiscal = $sigtasInfos->getRegimeFiscal();
            }
            else 
            {
                $document->nif = 'NA';
                $document->rs = 'NA';
            }
        }

        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage('L','A4');
        
        $filename = 'declarations_depot';
        $html=$this->render('DBundle:GestionDesActivites:declarations_depotpdf.html.twig', [
            'documents' => $pagination
        ]);
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I');

    }

    public function DeclarationsNonDepotAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');
        $doctypeno = 1;
        $docstateno = 2;
        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');
        $typeImpot = $request->query->get('typeImpot');
        $gestionnaire_filtre = $request->query->get('gestionnaire_filtre');
        $gestionnaire_assign = $request->query->get('gestionnaire_assign');
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        if ($date_du && $date_au) 
        {            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
        }else
        {
            $date_du = new \DateTime('now - 1 month'); 
            $date_au = new \Datetime();
        }
        $d = $date_du->format('d');
        $m = $date_du->format('M');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        $m_au = $date_au->format('M');
        $y_au = $date_au->format('y');
        $d_au = $date_au->format('d');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au; 
        $qr = $sigtas_em->getRepository(Document::class)
            ->createQueryBuilder('d')
            ->where('d.receivedDate IS NULL')
            ->andWhere('d.docTypeNo = :doctypeno')
            ->setparameter('doctypeno', $doctypeno)
            ->andWhere('d.docStateNo = :docstateno')
            ->setparameter('docstateno', $docstateno)
            ->andWhere('d.docTpYear = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->orderBy('d.taxPayerNo' , 'ASC')
            ->addOrderBy('d.taxTypeNo' , 'ASC');
        if($nifFilter|$rsFilter)
        {
            $qn = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qn)
            {
                $qr
                ->andWhere('d.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qn->getTaxPayerNo());
            }
        }
        // if ($date_du && $date_au) {
        //     $date_du = new \DateTime($request->query->get('date_du'));
        //     $date_au = new \DateTime($request->query->get('date_au'));
            // dump($date_du, $date_au);
            // die();
        //     $qr
        //         ->andWhere('d.receivedDate BETWEEN :date_du AND :date_au')
        //         ->setParameter('date_du', $date_du)
        //         ->setParameter('date_au', $date_au);
        // }
        if($typeImpot)
            $qr
            ->andWhere('d.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot)
            ->getQuery();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        ); 
        foreach ($pagination->getItems() as $key => $document) {
            $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('taxPayerNo' => $document->getTaxPayerNo())
            );
            if($sigtasInfos)
            {
                $document->nif = $sigtasInfos->getNif();
                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $sigtasInfos->getNif()) 
                );
                if($nifInfos)
                {
                    $documentRs = $nifInfos->getRs();
                }
                else
                {
                    $documentRs = null;
                }
                $document->rs = $documentRs;
                // $document->regimeFiscal = $sigtasInfos->getRegimeFiscal();
                $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                    'id' => $document->getTaxTypeNo()
                ]);
                if($taxTypeTrouver)
                {
                    $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
                }else
                {
                    $assessmentTaxTypeDesc = null;
                }
                $document->taxTypeDesc = $assessmentTaxTypeDesc;
            }
            else 
            {
                $document->nif = 'NA';
                $document->rs = 'NA';
            }
            $document->PaymentDate = $document->getDocTpPaymentDate();
            // dump($document->getDocTpPaymentDate());die();
        }
        $usersService = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:declarations_non_depot.html.twig', [
            'contribuables' => $qr,
            'documents' => $pagination,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'sectorActs' => $sectorActs,
            'usersService' => $usersService,
            'taxTypes' => $taxTypeList,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
    ]);
    }

    public function MouvementAction(Entrant $courrier, Request $request)
    {
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        // si le contribuable a fait un dépôt
        if($courrier->getRecievedDate() != null)
        {
            $mouvement = new Mouvement;
            $mouvement->setRecievedDate($courrier->getRecievedDate());
            $mouvement->setUpdatedDate(new \DateTime());

        }

    }

    public function AfficheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $enterprises = $sigtas_em->getRepository(Enterprise::Class)
        ->createQueryBuilder('e')
        ->orderBy('e.id', 'DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $enterprises,
            $request->query->getInt('page', 1),
            50
        ); 
        

        foreach ($pagination->getItems() as $key => $enterprise) 
        {
            $taxPayer = $sigtas_em->getRepository(TaxPayer::Class)->findOneBy(['taxPayerNo' => $enterprise]);
            if($taxPayer)
            {
                $enterprise->fiscalNo = $taxPayer->getFiscalNo();
                $enterprise->inactifDate = $taxPayer->getInactifDate();
            }else{
                $enterprise->fiscalNo = '-';
                $enterprise->inactifDate = '-';
            }
        }

        return $this->render('DBundle:GestionDesActivites:affiche.html.twig',array(
            'affiche'=> $pagination,
        ));
    }

    public function DeclarationsDepotParContribuablesAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:declarations_depot_par_contribuables.html.twig', array(
            // ...
        ));
    }

    public function DeclarationsDepotParGestionnaireAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:declarations_depot_par_gestionnaire.html.twig', array(
            // ...
        ));
    }

    public function DeclarationsNonDepotParEcheanceAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:declarations_non_depot_par_echeance.html.twig', array(
            // ...
        ));
    }

    public function DeclarationsNonDepotParContribuablesAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:declarations_non_depot_par_contribuables.html.twig', array(
            // ...
        ));
    }

    public function DeclarationsNonDepotParGestionnaireAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:declarations_non_depot_par_gestionnaire.html.twig', array(
            // ...
        ));
    }

    public function RectificationDeclarationAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:rectification_declaration.html.twig', array(
            // ...
        ));
    }

    public function DemandeDeRedessementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        
        $demandeRedressement = $em->getRepository(CourierEntrant::class)
        ->createQueryBuilder('c')
        ->where('c.categorie = 3')
        ->getQuery()
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $demandeRedressement,
            $request->query->getInt('page', 1),
            20
        );  

        foreach ($pagination->getItems() as $key => $contribuable) {
            $nif = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy([
                'nif' => $contribuable->getNif()
            ]);
            $contribuable->setContribuable($nif);
        }

        return $this->render('DBundle:GestionDesActivites:DemandeDeRedessement.html.twig', array(
            'demandeRedressement' => $pagination
        ));
    }

    public function AnnexeTVAAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:AnnexeTVA.html.twig', array(
            // ...
        ));
    }

    public function SuiviAcompteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $doctypeno = 7;
        $docstateno = 2;

        $acomptes = $sigtas_em->getRepository(DOCUMENT::class)
        ->createQueryBuilder('d')
        ->Where('d.docTypeNo = :doctypeno')
        ->setparameter('doctypeno', $doctypeno)
        ->andWhere('d.docStateNo = :docstateno')
        ->setparameter('docstateno', $docstateno)
        ->orderBy('d.docNo','DESC')
        ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate($acomptes,
            $request->query->getInt('page', 1),
            20
        ); 
            
        foreach ($pagination->getItems() as $key => $acompte)
        {
            $sigtasClientInfos = $sigtas_em->getRepository(Taxpayer::class)->findOneBy([
                'taxPayerNo' => $acompte->getTaxPayerNo()
            ]);
            if($sigtasClientInfos)
            {
                $acompte->nif = $sigtasClientInfos->getNif();
                $nifEnterpriseInfos = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                    'taxPayerNo' => $acompte->getTaxPayerNo()
                ]);
                if($nifEnterpriseInfos)
                {
                    $rs = $nifEnterpriseInfos->getRegistName(); 
                }
                $acompte->rs = $rs;
            }
            
            $acomptedu= $sigtas_em->getRepository(DocAmount::class)->findOneBy([
                'docNo' => $acompte->getDocNo()
            ]);
            if($acomptedu)
            {
                $acomptedus = $acomptedu->getTAXAMOUNT();
            }else
            {
                $acomptedus = null;
            }
            $acompte->acomptedu = $acomptedus*-1;
            
            $acomptedejapaye= $sigtas_em->getRepository(PAIEMENT::class)->findOneBy([
                'docNo' => $acompte->getDocNo()
            ]);
            // dump($acomptedejapaye);die();
            if($acomptedejapaye)
            {
                $annee = $acomptedejapaye->getAnnee();
                $acomptepaye = $acomptedejapaye->getMontant();
            }else
            {
                $annee = null;
                $acomptepaye = null;
            }
            $acompte->annee = $annee;
            $acompte->acomptePaye = $acomptepaye;
            $acompte->reliquat = $acompte->acomptedu - $acompte->acomptePaye;
        }

        return $this->render('DBundle:GestionDesActivites:suivi_acompte.html.twig', array(
            'acomptes' => $pagination
        ));
    }

    public function SuiviAcomptePdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $acomptes = $sigtas_em->getRepository(TAX_ACCOUNT::class)
        ->createQueryBuilder('t')
        //->where('c.categorie = 3')
        ->getQuery()
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $acomptes,
            $request->query->getInt('page', 1),
            20
        ); 

        foreach ($pagination->getItems() as $key => $account) {
            $sigtasClientInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'id' => $account->getTaxPayerNo()
            ]);
            if($sigtasClientInfos)
            {
                $account->nif = $sigtasClientInfos->getNif();

                $nifEnterpriseInfos = $nif_em->getRepository(NifOnlineClients::class)->findOneBy([
                    'nif' => $account->nif
                ]);
                if($nifEnterpriseInfos)
                {
                    $rs = $nifEnterpriseInfos->getRs(); 
                }
                $account->rs = $rs;
            }
        }

        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Our Code World');
        $pdf->SetTitle(('Our Code World Title'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(false);
        $pdf->SetFont('helvetica', '', 10, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage('L','A4');
        
        $filename = 'suiviCompte';
        $html= $this->render('DBundle:GestionDesActivites:suivi_acomptepdf.html.twig', array(
            'acomptes' => $pagination
        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); 
        
    }
    public function SuiviDossiersPhysiquesAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:suivi_dossiers_physiques.html.twig', array(
            // ...
        ));
    }

    public function GestionDesCommunicationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $Communication = $em->getRepository(Communication::class)
        ->createQueryBuilder('c')
        ->getQuery()
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $Communication,
            $request->query->getInt('page', 1),
            20
        );  

        return $this->render('DBundle:GestionDesActivites:GestionDesCommunications.html.twig', array(
            'communications' => $pagination
        ));
    }

    public function newGestionDesCommunicationsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $Communication = new Communication();

        $form = $this->createForm(CommunicationType::class, $Communication);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($Communication);
            $em->flush();

            return $this->redirectToRoute('_gestion_des_communications');
        }

        return $this->render('DBundle:GestionDesActivites:newGestionDesCommunications.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function ArchiveGestionDesCommunicationsAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:ArchiveGestionDesCommunications.html.twig', array(
            // ...
        ));
    }

    public function MesTachesAction(Request $request)
    {
        return $this->render('DBundle:GestionDesActivites:MesTaches.html.twig', array(
            // ...
        ));
    }

    public function DeclarationsDepotExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');
        $gestionnaire_filtre = $request->query->get('gestionnaire_filtre');
        $gestionnaire_assign = $request->query->get('gestionnaire_assign');
        $doctypeno = 1;
        $docstateno = 2;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        if ($date_du && $date_au) 
        {            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
        }
        // else
        // {
        //     $date_du = new \DateTime('now - 1 month'); 
        //     $date_au = new \Datetime();
        // }
        // $d = $date_du->format('d');
        // $m = $date_du->format('M');
        // $y = $date_du->format('y');
        // $dmY = $d.'-'.$m.'-'.$y;
        
        // $m_au = $date_au->format('M');
        // $y_au = $date_au->format('y');
        // $d_au = $date_au->format('d');
        // $dmYau = $d_au.'-'.$m_au.'-'.$y_au; 
        
        $query = $sigtas_em->getRepository(Document::class)
            ->createQueryBuilder('d')
            ->where('d.receivedDate IS NOT null')
            ->andWhere('d.docTypeNo = :doctypeno')
            ->setparameter('doctypeno', $doctypeno)
            ->andWhere('d.docStateNo = :docstateno')
            ->setparameter('docstateno', $docstateno)
            // ->andWhere('d.docTpDueDate BETWEEN :date_du AND :date_au')
            // ->setParameter('date_du', $dmY)
            // ->setParameter('date_au', $dmYau)
            ->orderBy('d.receivedDate' , 'DESC');

        if($nifFilter|$rsFilter)
        {
            die('nif');
            $qn = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qn)
            {
                $query
                ->andWhere('d.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qn->getTaxPayerNo());
            }
        }
        if ($date_du && $date_au) {
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
            // dump($date_du, $date_au);
            // die();
            $query
                ->andWhere('d.receivedDate BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if($typeImpot) {
            $query
            ->andWhere('d.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot);
        }
            $query
            ->getQuery()
            ->getResult();
        
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
            $phpExcelObject->getProperties()->setCreator("Dominique")
                ->setLastModifiedBy("Dominique")
                ->setTitle("Liste des déclarations-dépôt")
                ->setSubject("Déclarations-dépôt")
                ->setDescription("Ce fichier contient les déclarations-dépôt")
                ->setKeywords("declaration-depot")
                ->setCategory("ids.xls");
            $count = 6;
            // $phpExcelObject->setActiveSheetIndex(0)
            //     ->setCellValue('A1', 'Liste des déclarations-dépôt ');
    
            $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A3', 'Liste des déclarations-dépôt ');
    
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A5', 'NIF ')
                ->setCellValue('B5', 'Raison sociale ')
                ->setCellValue('C5', 'Impôts ')
                ->setCellValue('D5', 'Echéance ')
                ->setCellValue('E5', 'Date de dépôt ')
                ->setCellValue('F5', 'Date de paiement ');
            
            // dump($query);
            // die();

            foreach ($query as $query) {
    
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('A' . $count, $query->getNif())
                    ->setCellValue('B' . $count, $query->getRaisonSocial())
                    ->setCellValue('C' . $count, $query->getTaxTypeDesc())
                    ->setCellValue('D' . $count, $query->getDocTpDueDate())
                    ->setCellValue('E' . $count, $query->getPaymentDate())
                    ->setCellValue('F' . $count, $query->getReceivedDate());
                $count++;
            }
            $phpExcelObject->getActiveSheet()->setTitle('Simple');
            $phpExcelObject->setActiveSheetIndex(0);
            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);
            $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'Liste des declarations-depot.xlsx'
            );
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            $response->headers->set('Content-Disposition', $dispositionHeader);
    
            return $response;

    }

    public function documentListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');
        $gestionnaire_filtre = $request->query->get('gestionnaire_filtre');
        $gestionnaire_assign = $request->query->get('gestionnaire_assign');
        $doctypeno = 1;
        $docstateno = 2;
        $sectorActs = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findAll();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        if ($date_du && $date_au) 
        {            
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
        }
        else
        {
            $date_du = new \DateTime('now - 1 month'); 
            $date_au = new \Datetime();
        }
        $d = $date_du->format('d');
        $m = $date_du->format('m');
        $y = $date_du->format('y');
        $dmY = $d.'-'.$m.'-'.$y;
        
        $d_au = $date_au->format('d');
        $m_au = $date_au->format('m');
        $y_au = $date_au->format('y');
        $dmYau = $d_au.'-'.$m_au.'-'.$y_au; 
        // dump($date_du, $d, $m, $y, $dmY, $date_au, $d_au, $m_au, $y_au, $dmYau);
        // die();

        $defaultYear = new \Datetime('now');
        $yearCourr = $defaultYear->format('Y');

        // dump($defaultYear, $yearCourr);
        // die();

        $qr = $sigtas_em->getRepository(Document::class)
            ->createQueryBuilder('d')
            ->where('d.receivedDate IS NOT null')
            ->andWhere('d.docTypeNo = :doctypeno')
            ->setparameter('doctypeno', $doctypeno)
            ->andWhere('d.docStateNo = :docstateno')
            ->setparameter('docstateno', $docstateno)
            ->andWhere('d.docTpYear = :yearCourr')
            ->setParameter('yearCourr', $yearCourr)
            ->orderBy('d.docTpDueDate' , 'DESC');
            // ->addOrderBy('d.taxPayerNo','ASC');

        if($nifFilter|$rsFilter)
        {
            $qn = $sigtas_em->getRepository(TaxPayer::class)->findOneBy([
                'nif' => $nifFilter
            ]);
            if($qn)
            {
                $qr
                ->andWhere('d.taxPayerNo = :taxPayerNo')
                ->setParameter('taxPayerNo', $qn->getTaxPayerNo());
            }
        }
        if ($date_du && $date_au) {
            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));
            // dump($date_du, $date_au);
            // die();
            $qr
                ->andWhere('d.receivedDate BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au);
        }
        if($typeImpot) {
            $qr
            ->andWhere('d.taxTypeNo = :taxType')
            ->setParameter('taxType', $typeImpot);
        }
            $qr
            ->getQuery();
        
        // $responsableQuery =$em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        // $allUsers = $em->getRepository(User::class)->findAll();

        // return $this->render('DBundle:GestionDesActivites:coherence_nombre_de_dossier.html.twig', [
            // 'contribuables' => $pagination,
            // 'sectorActs' => $sectorActs,
            /*
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            */
            // 'nif'   => $request->query->get('nif'),
            // 'rs'   => $request->query->get('rs'),
            // 'sectorActs' => $sectorActs,
            // 'usersService' => $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
            // 'allUsers' => $allUsers
        // ]);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qr,
            $request->query->getInt('page', 1),
            20
        ); 

        foreach ($pagination->getItems() as $key => $document) {
            $sigtasInfos = $sigtas_em->getRepository(TaxPayer::class)->findOneBy(
                array('taxPayerNo' => $document->getTaxPayerNo())
            );
            if($sigtasInfos)
            {
                $document->nif = $sigtasInfos->getNif();
                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy(
                    array('nif' => $sigtasInfos->getNif()) 
                );
                if($nifInfos)
                {
                    $documentRs = $nifInfos->getRs();
                }
                else
                {
                    $documentRs = null;
                }
                $document->rs = $documentRs;
                // $document->regimeFiscal = $sigtasInfos->getRegimeFiscal();

                $taxTypeTrouver = $sigtas_em->getRepository(TaxType::class)->findOneBy([
                    'id' => $document->getTaxTypeNo()
                ]);
                if($taxTypeTrouver)
                {
                    $assessmentTaxTypeDesc = $taxTypeTrouver->getTaxTypeDesc();
                }else
                {
                    $assessmentTaxTypeDesc = null;
                }
                $document->taxTypeDesc = $assessmentTaxTypeDesc;
    
            }
            else 
            {
                $document->nif = 'NA';
                $document->rs = 'NA';
            }
            $document->PaymentDate = $document->getDocTpPaymentDate();
            // dump($document->getDocTpPaymentDate());die();
        }
        $usersService = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:documentList.html.twig', [
            'documents' => $pagination,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'sectorActs' => $sectorActs,
            'usersService' => $usersService,
            'taxTypes' => $taxTypeList,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            // 'usersService' => $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()))
        ]);

    }

    public function rar_periodeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $rarCheck = $sigtas_em->createQueryBuilder()
            ->select('count(r.nif)')
            ->from(RAR_PERIODE::class, 'r');
        $rarCount = $rarCheck->getQuery()->getSingleScalarResult();

        $RARs = $sigtas_em->getRepository(RAR_PERIODE::class)
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC');
            // ->getQuery()
            // ->getArrayResult();
 
        if ($nifFilter) {
            // $RARs = $sigtas_em->getRepository(RAR_PERIODE::class)
            //     ->createQueryBuilder('t')
            //     ->orderBy('t.nif', 'ASC')
            $RARs
                ->Where('t.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
                // ->andWhere('t.nom LIKE :rsParam')
                // ->setParameter('rsParam', '%' . $rsFilter . '%');
        }

        if($typeImpot)
        {
            $RARs
                ->andWhere('t.taxTypeNo = :taxType')
                ->setParameter('taxType', $typeImpot );
        }
            $RARs
                ->getQuery()
                ->getArrayResult();

        $rarList = $sigtas_em->createQueryBuilder()
            ->select('SUM(r.montant)')
            ->from(RAR_PERIODE::class, 'r');
            if ($nifFilter) {
                $rarList
                ->Where('r.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%')
                ->andWhere('r.nom LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
            }
            if($typeImpot)
            {
                $rarList
                ->andWhere('r.taxTypeNo = :taxType')
                ->setParameter('taxType', $typeImpot );
            }
        $rarSum = $rarList->getQuery()->getSingleScalarResult();
    
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $RARs,
            $request->query->getInt('page', 1),
            20
        ); 

        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:rar_periode.html.twig', array(
            'rars' => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'rarCount' => $rarCount,
            'rarSum' => $rarSum,
        ));
    }
    
    public function rar_periode_excelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'DGE - Liste des RAR avec periode au ' . $createdAt . '.xlsx';
        
        $dossierQuery = $sigtas_em->getRepository(RAR_PERIODE::class)
        // ->findBy(array(),array('nif' => 'ASC'));
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC')
            ->addOrderBy('t.nature' , 'ASC')
            ->addOrderBy('t.type' , 'ASC')
            ->getQuery()
            ->getArrayResult();
    
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les RAR avec période de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIF ')
            ->setCellValue('B1', 'Raison sociale ')
            ->setCellValue('C1', 'Impôts ')
            ->setCellValue('D1', 'Type ')
            ->setCellValue('E1', 'Montant ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        foreach ($dossierQuery as $dossier) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier["nif"])
                ->setCellValue('B' . $count, $dossier["nom"])
                ->setCellValue('C' . $count, $dossier["nature"])
                ->setCellValue('D' . $count, $dossier["type"])
                ->setCellValue('E' . $count, $dossier["montant"]);
                // ->setCellValue('A' . $count, $dossier->getNif())
                // ->setCellValue('B' . $count, $dossier->getNom())
                // ->setCellValue('C' . $count, $dossier->getNature())
                // ->setCellValue('D' . $count, $dossier->getType())
                // ->setCellValue('E' . $count, $dossier->getMontant());
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Liste des RAR avec période');
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

    public function rar_sans_periodeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $RARs = $sigtas_em->getRepository(RAR_SANS_PERIODE::class)
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC')
            ->getQuery()
            ->getArrayResult();
 
        $rarCheck = $sigtas_em->createQueryBuilder()
            ->select('count(r.nif)')
            ->from(RAR_SANS_PERIODE::class, 'r');
        $rarCount = $rarCheck->getQuery()->getSingleScalarResult();

        if ($nifFilter) {
            $RARs = $sigtas_em->getRepository(RAR_SANS_PERIODE::class)
                ->createQueryBuilder('t')
                ->orderBy('t.nif', 'ASC')
                ->where('t.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%')
                ->andWhere('t.nom LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%')
                ->getQuery()
                ->getArrayResult();
            }
        if($typeImpot)
        {
            $RARs = $sigtas_em->getRepository(RAR_SANS_PERIODE::class)
                ->createQueryBuilder('t')
                ->orderBy('t.nif', 'ASC')
                ->where('t.taxTypeNo = :taxType')
                ->setParameter('taxType', $typeImpot )
                ->getQuery()
                ->getArrayResult();
        }
            // $RARs
            //     ->getQuery()
            //     ->getArrayResult();

        $rarList = $sigtas_em->createQueryBuilder()
            ->select('SUM(r.montant)')
            ->from(RAR_SANS_PERIODE::class, 'r');
            if ($nifFilter) {
                $rarList
                ->Where('r.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%')
                ->andWhere('r.nom LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
            }
            if($typeImpot)
            {
                $rarList
                ->andWhere('r.taxTypeNo = :taxType')
                ->setParameter('taxType', $typeImpot );
            }
        $rarSum = $rarList->getQuery()->getSingleScalarResult();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $RARs,
            $request->query->getInt('page', 1),
            20
        ); 

        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:rar_sans_periode.html.twig', array(
            'rars' => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'rarCount' => $rarCount,
            'rarSum' => $rarSum,
        ));
    }
    
    public function rar_sans_periode_excelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'DGE - Liste des RAR sans periode au ' . $createdAt . '.xlsx';
        $dossierQuery = $sigtas_em->getRepository(RAR_SANS_PERIODE::class)
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC')
            ->addOrderBy('t.nature' , 'ASC')
            ->addOrderBy('t.type' , 'ASC')
            ->getQuery()
            ->getArrayResult();

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les RAR sans période de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIF ')
            ->setCellValue('B1', 'Raison sociale ')
            ->setCellValue('C1', 'Impôts ')
            ->setCellValue('D1', 'Type ')
            ->setCellValue('E1', 'Montant ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        foreach ($dossierQuery as $dossier) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier["nif"])
                ->setCellValue('B' . $count, $dossier["rs"])
                ->setCellValue('C' . $count, $dossier["nature"])
                ->setCellValue('D' . $count, $dossier["type"])
                ->setCellValue('E' . $count, $dossier["montant"]);
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Liste des RAR sans période');
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

    public function titre_perceptionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $typeImpot = $request->query->get('typeImpot');

        $rarCheck = $sigtas_em->createQueryBuilder()
            ->select('count(r.nif)')
            ->from(Titre_perception::class, 'r');
        $rarCount = $rarCheck->getQuery()->getSingleScalarResult();

        $RARs = $sigtas_em->getRepository(Titre_perception::class)
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC');
            // ->getQuery()
            // ->getArrayResult();
 
        if ($nifFilter) {
            // if($typeImpot){
            //     $RARs = $sigtas_em->getRepository(Titre_perception::class)
            //         ->createQueryBuilder('t')
            //         ->orderBy('t.nif', 'ASC')
            //         ->where('t.nif LIKE :nifParam')
            //         ->setParameter('nifParam', '%' . $nifFilter . '%')
            //         ->andWhere('t.nom LIKE :rsParam')
            //         ->setParameter('rsParam', '%' . $rsFilter . '%')
            //         ->andWhere('t.taxTypeDescF = :taxType')
            //         ->setParameter('taxType', $typeImpot );
            //         // ->getQuery()
            //         // ->getArrayResult();
            // }else
            // {
                // $RARs = $sigtas_em->getRepository(Titre_perception::class)
                    // ->createQueryBuilder('t')
                    // ->orderBy('t.nif', 'ASC')
                $RARs
                    ->where('t.nif LIKE :nifParam')
                    ->setParameter('nifParam', '%' . $nifFilter . '%')
                    ->andWhere('t.nom LIKE :rsParam')
                    ->setParameter('rsParam', '%' . $rsFilter . '%');
                    // ->getQuery()
                    // ->getArrayResult();
            // }
        }

        if($typeImpot)
        {
            // if($nifFilter){
            //     $RARs = $sigtas_em->getRepository(Titre_perception::class)
            //         ->createQueryBuilder('t')
            //         ->orderBy('t.nif', 'ASC')
            //         ->where('t.nif LIKE :nifParam')
            //         ->setParameter('nifParam', '%' . $nifFilter . '%')
            //         ->andWhere('t.nom LIKE :rsParam')
            //         ->setParameter('rsParam', '%' . $rsFilter . '%')
            //         ->andWhere('t.taxTypeDescF = :taxType')
            //         ->setParameter('taxType', $typeImpot );
            //         // ->getQuery()
            //         // ->getArrayResult();
            // }else
            // {
                // $RARs = $sigtas_em->getRepository(Titre_perception::class)
                $RARs
                    // ->createQueryBuilder('t')
                    // ->orderBy('t.nif', 'ASC')
                    ->where('t.taxTypeDescF = :taxType')
                    ->setParameter('taxType', $typeImpot );
                    // ->getQuery()
                    // ->getArrayResult();
            // }
        }
            $RARs
            ->getQuery()
            ->getArrayResult();

        $rarList = $sigtas_em->createQueryBuilder()
            ->select('SUM(r.rar)')
            ->from(Titre_perception::class, 'r');
            if ($nifFilter) {
                $rarList
                ->Where('r.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%')
                ->andWhere('r.nom LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
            }
            if($typeImpot)
            {
                $rarList
                ->andWhere('r.taxTypeDescF = :taxType')
                ->setParameter('taxType', $typeImpot );
            }
        $rarSum = $rarList->getQuery()->getSingleScalarResult();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $RARs,
            $request->query->getInt('page', 1),
            20
        ); 
    
        $taxTypeList = $sigtas_em->getRepository(TaxType::class)->findBy(array(),array('taxTypeDesc'=>'ASC'));

        return $this->render('DBundle:GestionDesActivites:titre_perception.html.twig', array(
            'rars' => $pagination,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'taxTypes' => $taxTypeList,
            'taxtype' => $typeImpot,
            'rarCount' => $rarCount,
            'rarSum' => $rarSum,
        ));
    }
    
    public function titre_perception_excelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');

        $now = new \DateTime();
        date_format($now, 'd-m-Y');
        $createdAt = date_format($now, 'd-m-Y');
        $filename = 'DGE - Liste des RAR Titre perception au ' . $createdAt . '.xlsx';

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        
        $dossiers = $sigtas_em->getRepository(Titre_perception::class)
            ->createQueryBuilder('t')
            ->orderBy('t.nif', 'ASC')
            ->getQuery()
            ->getArrayResult();
    
        $paginator  = $this->get('knp_paginator');
        $dossierQuery = $paginator->paginate(
            $dossiers,
            $request->query->getInt('page', 1),
            10000
        );

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Fonction PHP qui traite les données du site vers Excel")
            ->setSubject("Data PHP Excel")
            ->setDescription("Ce fichier contient les RAR Titre perception de la DGE")
            ->setKeywords("PHPExcel")
            ->setCategory("Fichier données du site vers Excel");
        $count = 2;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NIF ')
            ->setCellValue('B1', 'Raison sociale ')
            ->setCellValue('C1', 'Mois ')
            ->setCellValue('D1', 'Année ')
            ->setCellValue('E1', 'Impôts ')
            ->setCellValue('F1', 'Principal ')
            ->setCellValue('G1', 'Pénalité ')
            ->setCellValue('H1', 'Intérêt ')
            ->setCellValue('I1', 'Total ');
        $phpExcelObject->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setWidth(12);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setWidth(55);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setWidth(7);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setWidth(22);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        foreach ($dossierQuery as $dossier) {
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $dossier["nif"])
                ->setCellValue('B' . $count, $dossier["nom"])
                ->setCellValue('C' . $count, $dossier["mois"])
                ->setCellValue('D' . $count, $dossier["annee"])
                ->setCellValue('E' . $count, $dossier["taxTypeDescF"])
                ->setCellValue('F' . $count, $dossier["ppChrg"])
                ->setCellValue('G' . $count, $dossier["penChrg"])
                ->setCellValue('H' . $count, $dossier["intChrg"])
                ->setCellValue('I' . $count, $dossier["rar"]);
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Liste des RAR sans période');
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
