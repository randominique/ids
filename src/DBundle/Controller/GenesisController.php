<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;

use DBundle\Entity\Entrant_LH;

use DBundle\Entity\Entrant;
use DBundle\Entity\Sortant;
use DBundle\Entity\User;
use DBundle\Entity\Assujettissement;
use DBundle\Form\AssujettissementType;
use DBundle\Entity\CourierEntrant;
use DBundle\Entity\CourierSortant;
use DBundle\Entity\RelanceSetting;
use DBundle\Entity\Communication;
use DBundle\Entity\Taxpayerids;
use DBundle\Entity\EnterpriseLH;
use DBundle\Entity\Mventreprise;
use DBundle\Entity\PaiementLH;
use DBundle\Entity\Contribuables;

use NIFBundle\Entity\Clients as NIFOnlineClients;

use SIGTASBundle\Entity\Clients as SigtasClients;
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

class GenesisController extends Controller
{
    private $db_nif = 'nifonline';
    private $db_sigtas = 'sigtas';

    /**
     * @Route("/taxpayerGen", name="d_taxpayerGen")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $qb = $sigtas_em->createQueryBuilder();
        $qb->select('count(account.nif)');
        $qb->from(SigtasClients::class, 'account');
        $qb->where('account.inactifDate IS NULL');

        $count = $qb->getQuery()->getSingleScalarResult();

        $users = $sigtas_em->getRepository(Taxpayer::class)->findBy(array(
            'inactifDate' => null
        ));

        if ($users) {
            foreach ($users as $key => $user) {
                $taxpayerids = new Taxpayerids;
                $taxpayerids->setTAXPAYERNO($user->getTaxPayerNo());
                $taxpayerids->setTPTYPENO($user->getTpTypeNo());
                $taxpayerids->setCOUNTRYNO($user->getCountryNo());
                $taxpayerids->setNSFCHEQUE($user->getNsfcheque());
                $taxpayerids->setCITYNO($user->getCityNo());
                $taxpayerids->setMAILINGADDRESS($user->getMailingaddress());
                $taxpayerids->setRESIDENT($user->getResident());
                $taxpayerids->setREPTAXPAYERNO($user->getReptaxpayerNo());
                $taxpayerids->setFISCYRSTART($user->getFiscyrstart());
                $taxpayerids->setFISCYREND($user->getFiscyrend());
                $taxpayerids->setBRANCHNO($user->getBranchno());
                $taxpayerids->setREPTYPENO($user->getReptypeno());
                $taxpayerids->setPOSTCODENO($user->getPostcodeno());
                $taxpayerids->setREPREASONNO($user->getRepreasonno());
                $taxpayerids->setBANKACCTNO($user->getBankacctno());
                $taxpayerids->setREPTAXRNAME($user->getReptaxrname());
                $taxpayerids->setENTERDATE($user->getEnterdate());
                $taxpayerids->setENTERUSER($user->getEnteruser());
                $taxpayerids->setFISCALNO($user->getNif());
                $taxpayerids->setSTREETNO($user->getStreetno());
                $taxpayerids->setDOORNO($user->getDoorno());
                $taxpayerids->setLOCALITYNO($user->getLocalityno());
                $taxpayerids->setACCOUNTHOLDER($user->getAccountholder());
                $taxpayerids->setBANKNO($user->getBankno());
                $taxpayerids->setUPDATEDATE($user->getUpdatedate());
                $taxpayerids->setTAXCENTRENO($user->getTaxcentreno());
                $taxpayerids->setTEMPORARYTIN($user->getTemporarytin());
                $taxpayerids->setTPSTYPENO($user->getTpstypeno());
                $taxpayerids->setLANGNO($user->getLangno());
                $taxpayerids->setSENSITIVE($user->getSensitive());
                $taxpayerids->setTAXPYRCOMMENT($user->getTaxpyrcomment());
                $taxpayerids->setIFEMAILREMITTANCE($user->getIfemailremittance());
                $taxpayerids->setUPDATEUSER($user->getUpdateuser());
                $taxpayerids->setUSETINASVATID($user->getUsetinasvatid());
                $taxpayerids->setWEREDANO($user->getWeredano());
                $taxpayerids->setKEBELEDESC($user->getKebeledesc());
                $taxpayerids->setPOBOX($user->getPobox());
                $taxpayerids->setUSELOCALDATE($user->getUselocaldate());
                $taxpayerids->setTINFROMFLAG($user->getTinfromflag());
                $taxpayerids->setTINSIGTASCREATEDATE($user->getTinsigtascreatedate());
                $taxpayerids->setTINSIGTASLASTUPDATEDATE($user->getTinsigtaslastupdatedate());
                $taxpayerids->setTINUPDATEDVALUES($user->getTinupdatedvalues());
                $taxpayerids->setTINVATFLAGREMOVED($user->getTinvatflagremoved());
                $taxpayerids->setPREVIOUSTIN($user->getPrevioustin());
                $taxpayerids->setSENDCORRTOREPR($user->getSendcorrtorepr());
                $taxpayerids->setEXPORTER($user->getExporter());
                $taxpayerids->setFISCALREGIMENO($user->getRegimefiscal());
                $taxpayerids->setCATNO($user->getCatno());
                $taxpayerids->setTPSTATUSNO($user->getTpstatusno());
                $taxpayerids->setFISCALNOSIGTASOLD($user->getFiscalnosigtasold());
                $taxpayerids->setINACTIFDATE($user->getInactifDate());

                $em->persist($taxpayerids);
                $em->flush();
            }
        }
        return $this->forward('DBundle:Default:index');
    }

    /**
     * @Route("/entrantGen", name="app_entrantGen")
     */
    public function entrantGenAction()
    {
        $em = $this->getDoctrine()->getManager();
        $inputs = $em->getRepository('DBundle:Entrant')->findAll();
        // dump($inputs);
        // die();
        if ($inputs) {
            foreach ($inputs as $key => $input) {
                $entrant_lh = new Entrant_LH;
                // dump($input);
                // die();
                // if(!$this->Entrant_LH->contains($input->getRaisonSocial()))
                // {
                $entrant_lh->setRaisonSocial($input->getRaisonSocial());
                $entrant_lh->setNif($input->getNif());
                $entrant_lh->setPriority($input->getPriority());
                $entrant_lh->setStatus($input->getStatus());
                // $entrant_lh->setAuteur($input->getAuteur());
                // $entrant_lh->setObjectId($input->getObjectId());
                // $entrant_lh->setGestionnaireId($input->getGestionnaire());
                $entrant_lh->setUpdatedAt($input->getUpdatedAt());
                $entrant_lh->setCreatedAt($input->getCreatedAt());
                // $entrant_lh->setServiceId($input->getService());
                // $entrant_lh->setCourrierId($input->getCourrierId());
                // $entrant_lh->setYearCourr($input->getYearCourr());
                // $entrant_lh->setTitre($input->getTitre());
                // $entrant_lh->setObjet($input->getObjet());
                // $entrant_lh->setNUMEROCOURRIER($input->getNUMEROCOURRIER());
                // $entrant_lh->setDelegationDate($input->getDelegationDate());
                // $entrant_lh->setTraitementDate($input->getTraitementDate());
                // $entrant_lh->setAttribution($input->getAttribution());

                // $entrant_lh->setPourInfo(null);
                // $entrant_lh->setDispatchings(null);

                $em->persist($entrant_lh);
                $em->flush();
                // if($this->Entrant_LH.id == 4 )
                // {
                // dump($input);
                // die();
                // }
                // }
            }
        }
        return $this->forward('DBundle:EntrantLH:list');
    }

    /**
     * @Route("/enterpriseGen", name="d_enterpriseGen")
     */
    public function enterpriseGenAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $inputs = $sigtas_em->getRepository('SIGTASBundle:Enterprise')->findAll();
        if ($inputs) {
            foreach ($inputs as $key => $input) {
                $enterprise = new EnterpriseLH;
                $enterprise->setENTERPRISENO($input->getEnterpriseNo());
                $enterprise->setTAXPAYERNO($input->getTaxPayerNo());
                // $enterprise->setENTTYPENO($input->getEntTypeNo());
                $enterprise->setSECTORACTNO($input->getSectorActNo());
                $enterprise->setSTARTDATE($input->getStartDate());
                $enterprise->setENTRYDATE($input->getEntryDate());
                $enterprise->setFISCYRSTART($input->getFiscYrStart());
                $enterprise->setFISCYREND($input->getFiscYrEnd());
                // $enterprise->setTAXCONTACTNAME($input->getTaxcontactname());

                $em->persist($enterprise);
                $em->flush();
            }
        }
        return $this->forward('DBundle:Default:index');
    }

    /**
     * @Route("/paiementGen", name="d_paiementGen")
     */
    public function paiementGenAction()
    {

        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $inputs = $sigtas_em->getRepository(PAIEMENT::class)->findAll();
        // dump($inputs);
        // die();
        if ($inputs) {
            foreach ($inputs as $key => $input) {
                $payment = new PaiementLH;
                $payment->setTaxPayerNo($input->getTaxPayerNo());
                $payment->setNif($input->getNif());
                $payment->setTypeContribuable($input->getTypeContribuable());
                $payment->setRaisonSociale($input->getRaisonSociale());
                $payment->setAdresse($input->getAdresse());
                $payment->setSecteur($input->getSecteur());
                $payment->setRegime($input->getRegime());
                $payment->setForme($input->getForme());
                $payment->setDatePaiement($input->getDatePaiement());
                $payment->setModePaiement($input->getModePaiement());
                $payment->setBanque($input->getBanque());
                $payment->setRecepisse($input->getRecepisse());
                $payment->setAnnee($input->getAnnee());
                $payment->setMois($input->getMois());
                $payment->setTaxPeriodeNo($input->getTaxPeriodeNo());
                $payment->setTaxTypeNo($input->getTaxTypeNo());
                $payment->setImpotTaxe($input->getImpotTaxe());
                $payment->setTaxBasisNo($input->getTaxBasisNo());
                $payment->setPcop($input->getPcop());
                $payment->setTaxTransTypeNo($input->getTaxTransTypeNo());
                $payment->setTaxTransTypeDescF($input->getTaxTransTypeDescF());
                $payment->setMontant($input->getMontant());
                $payment->setAccountNo($input->getAccountNo());
                $payment->setTaxCentreNo($input->getTaxCentreNo());

                $em->persist($payment);
                $em->flush();
            }
        }
        return $this->forward('DBundle:Entrant:list');
    }

    /**
     * @Route("/entrepriseGen", name="d_entrepriseGen")
     */
    public function entrepriseGenAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');

        // $inputs = $nif_em->getRepository(NIFOnlineClients::class)->findAll();
        $inputs = $nif_em->getRepository(NIFOnlineClients::class)->findBy(array(
            'cgdesignation' => 'PERSONNE MORALE'
        ));

        // dump($inputs);
        // die();

        if ($inputs) {
            foreach ($inputs as $key => $input) {
                $mventreprise = new Mventreprise;
                $mventreprise->setNif($input->getNif());
                $mventreprise->setRegistname($input->getRegistname());
                $mventreprise->setCgdesignation($input->getCgdesignation());
                $mventreprise->setRs($input->getRs());
                $mventreprise->setNomcommercial($input->getNomcommercial());
                $mventreprise->setImpots($input->getImpots());
                $mventreprise->setNomDirigeant($input->getNomDirigeant());
                $mventreprise->setPhone($input->getPhone());
                $mventreprise->setEmail($input->getEmail());
                $mventreprise->setNomQualiteContact($input->getNomQualiteContact());
                $mventreprise->setContactPhone($input->getContactPhone());
                $mventreprise->setAdresse($input->getAdresse());

                $em->persist($mventreprise);
                $em->flush();
            }
        }
        return $this->forward('DBundle:Entrant:list');
    }

    /**
     * @Route("/assessmentG", name="d_assessmenGen")
     */
    public function assessmentGenAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $inputs = $sigtas_em->getRepository(TaxPayer::class)->findBy([
            'inactifDate' => null
        ]);

        if ($inputs) {
            foreach ($inputs as $key => $input) {
                $Contribuable = new Contribuables;
                $Contribuable->setNif($input->getNif());
                $Contribuable->setTaxpayerNo($input->getTaxpayerNo());

                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy([
                    'nif' => $input->getNif()
                ]);
                if ($nifInfos) {
                    $Contribuable->setRaisonSociale($nifInfos->getRs());
                    $Contribuable->setNomCommercial($nifInfos->getNomcommercial());
                    $Contribuable->setAdresse($nifInfos->getAdresse());
                    $Contribuable->setEmail($nifInfos->getEmail());
                    $Contribuable->setTelephone($nifInfos->getContactPhone());

                    $regimeFiscal = $sigtas_em->getRepository(FiscalRegime::class)->findOneBy([
                        'fiscalRegimeNo' => $input->getRegimeFiscal()
                    ]);
                    if ($regimeFiscal) {
                        // $FiscalRegimeDesc = $regimeFiscal->getFiscalRegimeDesc();
                        $Contribuable->setRegimeFiscal($regimeFiscal->getFiscalRegimeDesc());
                    }

                    $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                        'taxPayerNo' => $input->getTaxpayerNo()
                    ]);
                    if ($enterprise) {
                        $secteurActivite = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                            'id' => $enterprise->getSectorActNo()
                        ]);
                        $Contribuable->setSecteurActivite($secteurActivite->getSectorActDesc());
                    }

                    $Contribuable->setNomDirigeant($nifInfos->getNomQualiteContact());  // Nom # Qualité
                    // $Contribuable->setDateCreation($nifInfos->getTinsigtascreatedate());
                } else {
                    $Contribuable->setRaisonSociale = null;
                    $Contribuable->setNomCommercial = null;
                }
                $em->persist($Contribuable);
                $em->flush();
            }
        }
        return $this->forward('DBundle:Entrant:list');
    }

    public function contribuablesGenAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $inputs = $sigtas_em->getRepository(TaxPayer::class)->findBy([
            'inactifDate' => null
        ]);
        // ->createQueryBuilder('n')
        // ->where('n.inactifDate IS null')
        // ->getQuery()
        // ;
        // $inputs = $sigtas_em->createQueryBuilder();
        // $inputs->select('count(account.nif)');
        // $inputs->from(SigtasClients::class,'account');
        // $inputs->where('account.inactifDate IS NULL');

        // dump($inputs);die(); 
        if ($inputs) {
            // $contribuableDejaPresent = $em->getRespository(Contribuables::class)->findAll();
            // if($contribuableDejaPresent)
            // {
            //     $lastContribuables = $em->getRespository(Contribuables::class)
            //     ->select('MAX(lc.entryDate)')
            //     ->from(Contribuables::class,'lc')
            //     ->getQuery()

            //     ->getSingleScalarResult();

            //     $newContribuables = $em->getRespository(Enterprise::class)
            //     ->createQueryBuilder("nc")
            //     ->where('nc.entryDate > :lastContribuableEntryDate')
            //     ->setParameter('lastContribuableEntryDate', $lastContribuables)
            //     >getQuery();

            //     if($newContribuables)
            //     {
            //         foreach ($newContribuables as $key => $newContribuable) 
            //         {
            //             /// creation des nouveaux contribuables poour l'entité contribuables
            //         }
            //     }
            // }
            foreach ($inputs as $key => $input) {
                // $dateinactif = $input->getInactifDate();
                // dump($dateinactif);die(); 
                // if(IsNull($dateinactif))
                // {
                $Contribuable = new Contribuables;
                $Contribuable->setNif($input->getNif());
                $Contribuable->setTaxpayerNo($input->getTaxpayerNo());

                $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy([
                    'nif' => $input->getNif()
                ]);
                if ($nifInfos) {
                    $Contribuable->setRaisonSociale($nifInfos->getRs());
                    $Contribuable->setNomCommercial($nifInfos->getNomcommercial());
                    $Contribuable->setAdresse($nifInfos->getAdresse());
                    $Contribuable->setEmail($nifInfos->getEmail());
                    $Contribuable->setTelephone($nifInfos->getContactPhone());

                    $regimeFiscal = $sigtas_em->getRepository(FiscalRegime::class)->findOneBy([
                        'fiscalRegimeNo' => $input->getRegimeFiscal()
                    ]);
                    if ($regimeFiscal) {
                        $Contribuable->setRegimeFiscal($regimeFiscal->getFiscalRegimeDesc());
                    }

                    $enterprise = $sigtas_em->getRepository(Enterprise::class)->findOneBy([
                        'taxPayerNo' => $input->getTaxpayerNo()
                    ]);
                    if ($enterprise) {
                        $Contribuable->setNomDirigeant($enterprise->getTaxContactName());
                        $Contribuable->setDateCreation($enterprise->getStartDate());
                        $Contribuable->setDateArriveeDGE($enterprise->getEntryDate());
                        $Contribuable->setExerciceFiscalDebut($enterprise->getFiscYrStart());
                        $Contribuable->setExerciceFiscalFin($enterprise->getFiscYrEnd());
                        $secteurActivite = $sigtas_em->getRepository(SECTOR_ACTIVITY::class)->findOneBy([
                            'id' => $enterprise->getSectorActNo()
                        ]);
                        if ($secteurActivite) {
                            $Contribuable->setSecteurActivite($secteurActivite->getSectorActDesc());
                        }
                    }

                    // $Contribuable->setNomDirigeant($nifInfos->getNomQualiteContact());  // Nom # Qualité 
                    // prendre Enterprise.TAX_CONTACT_NAME ou Enterprise.MANAGER_FIRST_NAME et Enterprise.MANAGER_LAST_NAME 
                    // $Contribuable->setDateCreation($nifInfos->getTinsigtascreatedate()); // Enterprise.START_DATE
                } else {
                    $Contribuable->setRaisonSociale = null;
                    $Contribuable->setNomCommercial = null;
                }

                $Contribuable->setInactifDate($input->getInactifDate());
                // dump($Contribuable);die(); 

                $em->persist($Contribuable);
                $em->flush();
            }
            // }
        }
        return $this->forward('DBundle:Default:contribuables');
    }

    public function taxOfficesGen()
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $nif_em = $this->getDoctrine()->getManager('nifonline');

        $tOffices = $sigtas_em->getRepository(TaxationOffice::class)
            ->createQueryBuilder('a')
            ->orderBy('a.nif', 'ASC');
        $query->getQuery();

        foreach ($tOffices->getItems() as $key => $tOffice) {
            $taxOffice = new TaxOffices;
            $taxOffice->setNif($tOffice->getNif());
            $nifInfos = $nif_em->getRepository(NIFOnlineClients::class)->findOneBy([
                'nif' => $taxOffice->getNif()
            ]);
            if ($nifInfos) {
                $taxOffice->setRs($nifInfos->getRs());
            }
            $taxOffice->setTaxTypeDescF($tOffice->getTaxTypeDescF());
            $taxOffice->setTperYear($tOffice->getTperYear());
            $taxOffice->setTperMonth($tOffice->getTperMonth());
            $taxOffice->setTaxe($tOffice->getTaxe());
            $em->persist($taxOffice);
            $em->flush();
        }
        return $this->forward('DBundle:Default:contribuables');
    }

    public function TruncatetableAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userSystem = 'SuperAdmin';
        $user = $this->getUser()->getUsername();
        if($user == $userSystem)
        {
            $services = $em->getRepository(Tache::class)->toTruncate();
        }
        return $this->redirectToRoute('list_entrant');
    }

}
