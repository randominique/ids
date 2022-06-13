<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PAIEMENT
 *
 * @ORM\Table(name="PAIEMENT")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\PAIEMENTRepository")
 */
class PAIEMENT
{
    
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     */
    private $taxPayerNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NIF", type="string", length=255)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE_CONTRIBUABLE", type="string", length=255, nullable=true)
     */
    private $typeContribuable;

    /**
     * @var string
     *
     * @ORM\Column(name="RAISON_SOCIALE", type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @var string
     *
     * @ORM\Column(name="ADRESSE", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="SECTEUR", type="string", length=255, nullable=true)
     */
    private $secteur;

    /**
     * @var string
     *
     * @ORM\Column(name="REGIME", type="string", length=255, nullable=true)
     */
    private $regime;

    /**
     * @var string
     *
     * @ORM\Column(name="FORME", type="string", length=255, nullable=true)
     */
    private $forme;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_PAIEMENT", type="oracledate", nullable=true)
     */
    private $dateDePaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="MODE_PAIEMENT", type="string", length=255, nullable=true)
     */
    private $modePaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="BANQUE", type="string", length=255, nullable=true)
     */
    private $banque;

    /**
     * @var string
     *
     * @ORM\Column(name="RECEPISSE", type="string", length=255, nullable=true)
     */
    private $recepisse;

    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var int
     *
     * @ORM\Column(name="MOIS", type="integer", nullable=true)
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PERIOD_NO", type="integer", nullable=true)
     */
    private $taxPeriodeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TYPE_NO", type="integer")
     */
    private $taxTypeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="IMPOT_TAXE", type="string", length=255, nullable=true)
     */
    private $impotTaxe;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_BASIS_NO", type="integer")
     */
    private $taxBasisNo;

    /**
     * @var string
     *
     * @ORM\Column(name="PCOP", type="string", length=255, nullable=true)
     */
    private $pcop;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TRANS_TYPE_NO", type="integer")
     */
    private $taxTransTypeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TRANS_TYPE_DESC_F", type="string", length=255, nullable=true)
     */
    private $taxTransTypeDescF;

    /**
     * @var string
     *
     * @ORM\Column(name="MONTANT", type="string", length=255, nullable=true)
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="ACCOUNT_NO", type="integer", nullable=true)
     */
    private $accountNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="string", length=255, nullable=true)
     */
    private $taxCentreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="PV", type="string", length=255, nullable=true)
     */
    private $pv;

    /**
     * @var string
     *
     * @ORM\Column(name="TREASURY_ACCT_NO", type="integer", length=8, nullable=true)
     */
    private $treasuryAcctNo;

    /**
     * @var string
     *
     * @ORM\Column(name="SUB_TRANS_COMMENT", type="string", length=80, nullable=true)
     */
    private $subTransComment;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TRANS_COMMENT", type="string", length=80, nullable=true)
     */
    private $taxTransComment;

    /**
     * @var string
     *
     * @ORM\Column(name="DOC_NO", type="integer", length=10, nullable=true)
     */
    private $docNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TPAYER_NO", type="integer", length=8, nullable=true)
     */
    private $tpayerNo;

    
     /**
     * Set taxPayerNo
     *
     * @param integer $taxPayerNo
     *
     * @return PAIEMENT
     */
    public function setTaxPayerNo($taxPayerNo)
    {
        $this->taxPayerNo = $taxPayerNo;

        return $this;
    }

    /**
     * Get taxPayerNo
     *
     * @return int
     */
    public function getTaxPayerNo()
    {
        return $this->taxPayerNo;
    }


    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return PAIEMENT
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    public function setContribuable(){
        return $this->contribuable;
    }

    /**
     * Set typeContribuable
     *
     * @param string $typeContribuable
     *
     * @return PAIEMENT
     */
    public function setTypeContribuable($typeContribuable)
    {
        $this->typeContribuable = $typeContribuable;

        return $this;
    }

    /**
     * Get typeContribuable
     *
     * @return string
     */
    public function getTypeContribuable()
    {
        return $this->typeContribuable;
    }

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return PAIEMENT
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return PAIEMENT
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set secteur
     *
     * @param string $secteur
     *
     * @return PAIEMENT
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set regime
     *
     * @param string $regime
     *
     * @return PAIEMENT
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;

        return $this;
    }

    /**
     * Get regime
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * Set forme
     *
     * @param string $forme
     *
     * @return PAIEMENT
     */
    public function setForme($forme)
    {
        $this->forme = $forme;

        return $this;
    }

    /**
     * Get forme
     *
     * @return string
     */
    public function getForme()
    {
        return $this->forme;
    }

    /**
     * Set dateDePaiement
     *
     * @param oracledate $dateDePaiement
     *
     * @return PAIEMENT
     */
    public function setDateDePaiement($dateDePaiement)
    {
        $this->dateDePaiement = $dateDePaiement;

        return $this;
    }

    /**
     * Get dateDePaiement
     *
     * @return oracledate
     */
    public function getDateDePaiement()
    {
        return $this->dateDePaiement;
    }

    /**
     * Set modePaiement
     *
     * @param string $modePaiement
     *
     * @return PAIEMENT
     */
    public function setModePaiement($modePaiement)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return string
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return PAIEMENT
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set recepisse
     *
     * @param string $recepisse
     *
     * @return PAIEMENT
     */
    public function setRecepisse($recepisse)
    {
        $this->recepisse = $recepisse;

        return $this;
    }

    /**
     * Get recepisse
     *
     * @return string
     */
    public function getRecepisse()
    {
        return $this->recepisse;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return PAIEMENT
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     *
     * @return PAIEMENT
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return integer
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set taxPeriodeNo
     *
     * @param integer $taxPeriodeNo
     *
     * @return PAIEMENT
     */
    public function setTaxPeriodeNo($taxPeriodeNo)
    {
        $this->taxPeriodeNo = $taxPeriodeNo;

        return $this;
    }

    /**
     * Get taxPeriodeNo
     *
     * @return integer
     */
    public function getTaxPeriodeNo()
    {
        return $this->taxPeriodeNo;
    }

    /**
     * Set taxTypeNo
     *
     * @param integer $taxTypeNo
     *
     * @return PAIEMENT
     */
    public function setTaxTypeNo($taxTypeNo)
    {
        $this->taxTypeNo = $taxTypeNo;

        return $this;
    }

    /**
     * Get taxTypeNo
     *
     * @return integer
     */
    public function getTaxTypeNo()
    {
        return $this->taxTypeNo;
    }

    /**
     * Set impotTaxe
     *
     * @param string $impotTaxe
     *
     * @return PAIEMENT
     */
    public function setImpotTaxe($impotTaxe)
    {
        $this->impotTaxe = $impotTaxe;

        return $this;
    }

    /**
     * Get impotTaxe
     *
     * @return string
     */
    public function getImpotTaxe()
    {
        return $this->impotTaxe;
    }

    /**
     * Set taxBasisNo
     *
     * @param integer $taxBasisNo
     *
     * @return PAIEMENT
     */
    public function settaxBasisNo($taxBasisNo)
    {
        $this->taxBasisNo = $taxBasisNo;

        return $this;
    }

    /**
     * Get taxBasisNo
     *
     * @return integer
     */
    public function gettaxBasisNo()
    {
        return $this->taxBasisNo;
    }

    /**
     * Set pcop
     *
     * @param string $pcop
     *
     * @return PAIEMENT
     */
    public function setPcop($pcop)
    {
        $this->pcop = $pcop;

        return $this;
    }

    /**
     * Get pcop
     *
     * @return string
     */
    public function getPcop()
    {
        return $this->pcop;
    }

    /**
     * Set taxTransTypeNo
     *
     * @param integer $taxTransTypeNo
     *
     * @return PAIEMENT
     */
    public function setTaxTransTypeNo($taxTransTypeNo)
    {
        $this->taxTransTypeNo = $taxTransTypeNo;

        return $this;
    }

    /**
     * Get taxTransTypeNo
     *
     * @return integer
     */
    public function getTaxTransTypeNo()
    {
        return $this->taxTransTypeNo;
    }

    /**
     * Set taxTransTypeDescF
     *
     * @param string $taxTransTypeDescF
     *
     * @return PAIEMENT
     */
    public function setTaxTransTypeDescF($taxTransTypeDescF)
    {
        $this->taxTransTypeDescF = $taxTransTypeDescF;

        return $this;
    }

    /**
     * Get taxTransTypeDescF
     *
     * @return string
     */
    public function getTaxTransTypeDescF()
    {
        return $this->taxTransTypeDescF;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return PAIEMENT
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set accountNo
     *
     * @param integer $accountNo
     *
     * @return PAIEMENT
     */
    public function setAccountNo($accountNo)
    {
        $this->accountNo = $accountNo;

        return $this;
    }

    /**
     * Get accountNo
     *
     * @return integer
     */
    public function getAccountNo()
    {
        return $this->accountNo;
    }

    /**
     * Set taxCentreNo
     *
     * @param string $taxCentreNo
     *
     * @return PAIEMENT
     */
    public function setTaxCentreNo($taxCentreNo)
    {
        $this->taxCentreNo = $taxCentreNo;

        return $this;
    }

    /**
     * Get taxCentreNo
     *
     * @return string
     */
    public function getTaxCentreNo()
    {
        return $this->taxCentreNo;
    }
}
