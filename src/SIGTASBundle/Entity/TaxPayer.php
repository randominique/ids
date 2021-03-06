<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxPayer
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="TAX_PAYER")
 */
class TaxPayer
{
    private function __construct() {}

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     */
    public $taxPayerNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_TYPE_NO", type="integer")
     */
    public $tpTypeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="COUNTRY_NO", type="integer")
     */
    public $countryNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NSF_CHEQUE", type="string", length=1)
     */
    public $nsfcheque;

    /**
     * @var int
     *
     * @ORM\Column(name="CITY_NO", type="integer")
     */
    public $cityNo;

    /**
     * @var string
     *
     * @ORM\Column(name="MAILING_ADDRESS",type="string", length=255)
     */
    public $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="RESIDENT", type="string", length=1)
     */
    public $resident;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_TAX_PAYER_NO", type="integer")
     */
    public $reptaxpayerNo;

    /**
     * @var int
     *
     * @ORM\Column(name="FISC_YR_START", type="integer")
     */
    public $fiscyrstart;

    /**
     * @var int
     *
     * @ORM\Column(name="FISC_YR_END", type="integer")
     */
    public $fiscyrend;

    /**
     * @var int
     *
     * @ORM\Column(name="BRANCH_NO", type="integer")
     */
    public $branchno;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_TYPE_NO", type="integer")
     */
    public $reptypeno;

    /**
     * @var int
     *
     * @ORM\Column(name="POST_CODE_NO", type="integer")
     */
    public $postcodeno;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_REASON_NO", type="integer")
     */
    public $repreasonno;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK_ACCT_NO", type="string", length=25)
     */
    public $bankacctno;

    /**
     * @var string
     *
     * @ORM\Column(name="REP_TAXR_NAME", type="string", length=25)
     */
    public $reptaxrname;

    /**
     * @var \datetime
     * 
     * @ORM\Column(name="ENTER_DATE", type="datetime")
     */
    public $enterdate;

    /**
     * @var int
     *
     * @ORM\Column(name="ENTER_USER", type="integer")
     */
    public $enteruser;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO",type="string", length=20)
     * @ORM\Id
     */
    public $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="STREET_NO", type="string", length=15)
     */
    public $streetno;

    /**
     * @var string
     *
     * @ORM\Column(name="DOOR_NO", type="string", length=5)
     */
    public $doorno;

    /**
     * @var int
     *
     * @ORM\Column(name="LOCALITY_NO", type="integer")
     */
    public $localityno;

    /**
     * @var string
     *
     * @ORM\Column(name="ACCOUNT_HOLDER", type="string", length=50)
     */
    public $accountholder;

    /**
     * @var int
     *
     * @ORM\Column(name="BANK_NO", type="integer")
     */
    public $bankno;

    /**
     * @var \datetime
     * 
     * @ORM\Column(name="UPDATE_DATE", type="datetime")
     */
    public $updatedate;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer")
     */
    public $taxcentreno;

    /**
     * @var string
     *
     * @ORM\Column(name="TEMPORARY_TIN", type="string", length=10)
     */
    public $temporarytin;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_STYPE_NO", type="integer")
     */
    public $tpstypeno;

    /**
     * @var int
     *
     * @ORM\Column(name="LANG_NO", type="integer")
     */
    public $langno;

    /**
     * @var string
     *
     * @ORM\Column(name="SENSITIVE", type="string", length=1)
     */
    public $sensitive;

    /**
     * @var string
     *
     * @ORM\Column(name="TAXPYR_COMMENT", type="string", length=200)
     */
    public $taxpyrcomment;

    /**
     * @var string
     *
     * @ORM\Column(name="IF_EMAIL_REMITTANCE", type="string", length=1)
     */
    public $ifemailremittance;

    /**
     * @var int
     *
     * @ORM\Column(name="UPDATE_USER", type="integer")
     */
    public $updateuser;

    /**
     * @var string
     *
     * @ORM\Column(name="USE_TIN_AS_VAT_ID", type="string", length=1)
     */
    public $usetinasvatid;

    /**
     * @var int
     *
     * @ORM\Column(name="WEREDA_NO", type="integer")
     */
    public $weredano;

    /**
     * @var string
     *
     * @ORM\Column(name="KEBELE_DESC", type="string", length=30)
     */
    public $kebeledesc;

    /**
     * @var string
     *
     * @ORM\Column(name="PO_BOX", type="string", length=50)
     */
    public $pobox;

    /**
     * @var string
     *
     * @ORM\Column(name="USE_LOCAL_DATE", type="string", length=1)
     */
    public $uselocaldate;

    /**
     * @var string
     *
     * @ORM\Column(name="TIN_FROM_FLAG", type="string", length=30)
     */
    public $tinfromflag;

    /**
     * @var \datetime
     * 
     * @ORM\Column(name="TIN_SIGTAS_CREATE_DATE", type="datetime")
     */
    public $tinsigtascreatedate;

    /**
     * @var \datetime
     * 
     * @ORM\Column(name="TIN_SIGTAS_LAST_UPDATE_DATE", type="datetime")
     */
    public $tinsigtaslastupdatedate;

    /**
     * @var int
     *
     * @ORM\Column(name="TIN_UPDATED_VALUES", type="integer")
     */
    public $tinupdatedvalues;

    /**
     * @var string
     *
     * @ORM\Column(name="TIN_VAT_FLAG_REMOVED", type="string", length=1)
     */
    public $tinvatflagremoved;

    /**
     * @var int
     *
     * @ORM\Column(name="PREVIOUS_TIN", type="string", length=20)
     */
    public $previoustin;

    /**
     * @var string
     *
     * @ORM\Column(name="SEND_CORR_TO_REPR", type="string", length=1)
     */
    public $sendcorrtorepr;

    /**
     * @var string
     *
     * @ORM\Column(name="EXPORTER", type="string", length=1)
     */
    public $exporter;

    /**
     * @var int
     *
     * @ORM\Column(name="FISCAL_REGIME_NO", type="integer")
     */
    public $regimeFiscal;

    /**
     * @var int
     *
     * @ORM\Column(name="CAT_NO", type="integer")
     */
    public $catno;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_STATUS_NO", type="integer")
     */
    public $tpstatusno;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO_SIGTAS_OLD", type="string", length=20)
     */
    public $fiscalnosigtasold;

    /**
     * @var \datetime
     * 
     * @ORM\Column(name="INACTIF_DATE", type="datetime")
     */
    public $inactifDate;

    private $sigtas; 

    private $sigtasRs;

    private $sigtasNc;

    private $sigtasMail;

    private $secteurActivite;

    private $sigtasPhone;

    private $rs;


    /**
     * Set taxPayerNo
     *
     * @param integer $taxPayerNo
     *
     * @return TaxPayer
     */
    public function setTaxPayerNo($taxPayerNo)
    {
        $this->taxPayerNo = $taxPayerNo;

        return $this;
    }

    /**
     * Get taxPayerNo
     *
     * @return integer
     */
    public function getTaxPayerNo()
    {
        return $this->taxPayerNo;
    }

    /**
     * Set tpTypeNo
     *
     * @param integer $tpTypeNo
     *
     * @return TaxPayer
     */
    public function setTpTypeNo($tpTypeNo)
    {
        $this->tpTypeNo = $tpTypeNo;

        return $this;
    }

    /**
     * Get tpTypeNo
     *
     * @return integer
     */
    public function getTpTypeNo()
    {
        return $this->tpTypeNo;
    }

    /**
     * Set countryNo
     *
     * @param integer $countryNo
     *
     * @return TaxPayer
     */
    public function setCountryNo($countryNo)
    {
        $this->countryNo = $countryNo;

        return $this;
    }

    /**
     * Get countryNo
     *
     * @return integer
     */
    public function getCountryNo()
    {
        return $this->countryNo;
    }

    /**
     * Set nsfcheque
     *
     * @param string $nsfcheque
     *
     * @return TaxPayer
     */
    public function setNsfcheque($nsfcheque)
    {
        $this->nsfcheque = $nsfcheque;

        return $this;
    }

    /**
     * Get nsfcheque
     *
     * @return string
     */
    public function getNsfcheque()
    {
        return $this->nsfcheque;
    }

    /**
     * Set cityNo
     *
     * @param integer $cityNo
     *
     * @return TaxPayer
     */
    public function setCityNo($cityNo)
    {
        $this->cityNo = $cityNo;

        return $this;
    }

    /**
     * Get cityNo
     *
     * @return integer
     */
    public function getCityNo()
    {
        return $this->cityNo;
    }

    /**
     * Set mailingaddress
     *
     * @param string $mailingaddress
     *
     * @return TaxPayer
     */
    public function setMailingaddress($mailingaddress)
    {
        $this->mailingaddress = $mailingaddress;

        return $this;
    }

    /**
     * Get mailingaddress
     *
     * @return string
     */
    public function getMailingaddress()
    {
        return $this->mailingaddress;
    }

    /**
     * Set resident
     *
     * @param string $resident
     *
     * @return TaxPayer
     */
    public function setResident($resident)
    {
        $this->resident = $resident;

        return $this;
    }

    /**
     * Get resident
     *
     * @return string
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Set reptaxpayerNo
     *
     * @param integer $reptaxpayerNo
     *
     * @return TaxPayer
     */
    public function setReptaxpayerNo($reptaxpayerNo)
    {
        $this->reptaxpayerNo = $reptaxpayerNo;

        return $this;
    }

    /**
     * Get reptaxpayerNo
     *
     * @return integer
     */
    public function getReptaxpayerNo()
    {
        return $this->reptaxpayerNo;
    }

    /**
     * Set fiscyrstart
     *
     * @param integer $fiscyrstart
     *
     * @return TaxPayer
     */
    public function setFiscyrstart($fiscyrstart)
    {
        $this->fiscyrstart = $fiscyrstart;

        return $this;
    }

    /**
     * Get fiscyrstart
     *
     * @return integer
     */
    public function getFiscyrstart()
    {
        return $this->fiscyrstart;
    }

    /**
     * Set fiscyrend
     *
     * @param integer $fiscyrend
     *
     * @return TaxPayer
     */
    public function setFiscyrend($fiscyrend)
    {
        $this->fiscyrend = $fiscyrend;

        return $this;
    }

    /**
     * Get fiscyrend
     *
     * @return integer
     */
    public function getFiscyrend()
    {
        return $this->fiscyrend;
    }

    /**
     * Set branchno
     *
     * @param integer $branchno
     *
     * @return TaxPayer
     */
    public function setBranchno($branchno)
    {
        $this->branchno = $branchno;

        return $this;
    }

    /**
     * Get branchno
     *
     * @return integer
     */
    public function getBranchno()
    {
        return $this->branchno;
    }

    /**
     * Set reptypeno
     *
     * @param integer $reptypeno
     *
     * @return TaxPayer
     */
    public function setReptypeno($reptypeno)
    {
        $this->reptypeno = $reptypeno;

        return $this;
    }

    /**
     * Get reptypeno
     *
     * @return integer
     */
    public function getReptypeno()
    {
        return $this->reptypeno;
    }

    /**
     * Set postcodeno
     *
     * @param integer $postcodeno
     *
     * @return TaxPayer
     */
    public function setPostcodeno($postcodeno)
    {
        $this->postcodeno = $postcodeno;

        return $this;
    }

    /**
     * Get postcodeno
     *
     * @return integer
     */
    public function getPostcodeno()
    {
        return $this->postcodeno;
    }

    /**
     * Set repreasonno
     *
     * @param integer $repreasonno
     *
     * @return TaxPayer
     */
    public function setRepreasonno($repreasonno)
    {
        $this->repreasonno = $repreasonno;

        return $this;
    }

    /**
     * Get repreasonno
     *
     * @return integer
     */
    public function getRepreasonno()
    {
        return $this->repreasonno;
    }

    /**
     * Set bankacctno
     *
     * @param string $bankacctno
     *
     * @return TaxPayer
     */
    public function setBankacctno($bankacctno)
    {
        $this->bankacctno = $bankacctno;

        return $this;
    }

    /**
     * Get bankacctno
     *
     * @return string
     */
    public function getBankacctno()
    {
        return $this->bankacctno;
    }

    /**
     * Set reptaxrname
     *
     * @param string $reptaxrname
     *
     * @return TaxPayer
     */
    public function setReptaxrname($reptaxrname)
    {
        $this->reptaxrname = $reptaxrname;

        return $this;
    }

    /**
     * Get reptaxrname
     *
     * @return string
     */
    public function getReptaxrname()
    {
        return $this->reptaxrname;
    }

    /**
     * Set enterdate
     *
     * @param \DateTime $enterdate
     *
     * @return TaxPayer
     */
    public function setEnterdate($enterdate)
    {
        $this->enterdate = $enterdate;

        return $this;
    }

    /**
     * Get enterdate
     *
     * @return \DateTime
     */
    public function getEnterdate()
    {
        return $this->enterdate;
    }

    /**
     * Set enteruser
     *
     * @param integer $enteruser
     *
     * @return TaxPayer
     */
    public function setEnteruser($enteruser)
    {
        $this->enteruser = $enteruser;

        return $this;
    }

    /**
     * Get enteruser
     *
     * @return integer
     */
    public function getEnteruser()
    {
        return $this->enteruser;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return TaxPayer
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

    /**
     * Set streetno
     *
     * @param string $streetno
     *
     * @return TaxPayer
     */
    public function setStreetno($streetno)
    {
        $this->streetno = $streetno;

        return $this;
    }

    /**
     * Get streetno
     *
     * @return string
     */
    public function getStreetno()
    {
        return $this->streetno;
    }

    /**
     * Set doorno
     *
     * @param string $doorno
     *
     * @return TaxPayer
     */
    public function setDoorno($doorno)
    {
        $this->doorno = $doorno;

        return $this;
    }

    /**
     * Get doorno
     *
     * @return string
     */
    public function getDoorno()
    {
        return $this->doorno;
    }

    /**
     * Set localityno
     *
     * @param integer $localityno
     *
     * @return TaxPayer
     */
    public function setLocalityno($localityno)
    {
        $this->localityno = $localityno;

        return $this;
    }

    /**
     * Get localityno
     *
     * @return integer
     */
    public function getLocalityno()
    {
        return $this->localityno;
    }

    /**
     * Set accountholder
     *
     * @param string $accountholder
     *
     * @return TaxPayer
     */
    public function setAccountholder($accountholder)
    {
        $this->accountholder = $accountholder;

        return $this;
    }

    /**
     * Get accountholder
     *
     * @return string
     */
    public function getAccountholder()
    {
        return $this->accountholder;
    }

    /**
     * Set bankno
     *
     * @param integer $bankno
     *
     * @return TaxPayer
     */
    public function setBankno($bankno)
    {
        $this->bankno = $bankno;

        return $this;
    }

    /**
     * Get bankno
     *
     * @return integer
     */
    public function getBankno()
    {
        return $this->bankno;
    }

    /**
     * Set updatedate
     *
     * @param \DateTime $updatedate
     *
     * @return TaxPayer
     */
    public function setUpdatedate($updatedate)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate
     *
     * @return \DateTime
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set taxcentreno
     *
     * @param integer $taxcentreno
     *
     * @return TaxPayer
     */
    public function setTaxcentreno($taxcentreno)
    {
        $this->taxcentreno = $taxcentreno;

        return $this;
    }

    /**
     * Get taxcentreno
     *
     * @return integer
     */
    public function getTaxcentreno()
    {
        return $this->taxcentreno;
    }

    /**
     * Set temporarytin
     *
     * @param string $temporarytin
     *
     * @return TaxPayer
     */
    public function setTemporarytin($temporarytin)
    {
        $this->temporarytin = $temporarytin;

        return $this;
    }

    /**
     * Get temporarytin
     *
     * @return string
     */
    public function getTemporarytin()
    {
        return $this->temporarytin;
    }

    /**
     * Set tpstypeno
     *
     * @param integer $tpstypeno
     *
     * @return TaxPayer
     */
    public function setTpstypeno($tpstypeno)
    {
        $this->tpstypeno = $tpstypeno;

        return $this;
    }

    /**
     * Get tpstypeno
     *
     * @return integer
     */
    public function getTpstypeno()
    {
        return $this->tpstypeno;
    }

    /**
     * Set langno
     *
     * @param integer $langno
     *
     * @return TaxPayer
     */
    public function setLangno($langno)
    {
        $this->langno = $langno;

        return $this;
    }

    /**
     * Get langno
     *
     * @return integer
     */
    public function getLangno()
    {
        return $this->langno;
    }

    /**
     * Set sensitive
     *
     * @param string $sensitive
     *
     * @return TaxPayer
     */
    public function setSensitive($sensitive)
    {
        $this->sensitive = $sensitive;

        return $this;
    }

    /**
     * Get sensitive
     *
     * @return string
     */
    public function getSensitive()
    {
        return $this->sensitive;
    }

    /**
     * Set taxpyrcomment
     *
     * @param string $taxpyrcomment
     *
     * @return TaxPayer
     */
    public function setTaxpyrcomment($taxpyrcomment)
    {
        $this->taxpyrcomment = $taxpyrcomment;

        return $this;
    }

    /**
     * Get taxpyrcomment
     *
     * @return string
     */
    public function getTaxpyrcomment()
    {
        return $this->taxpyrcomment;
    }

    /**
     * Set ifemailremittance
     *
     * @param string $ifemailremittance
     *
     * @return TaxPayer
     */
    public function setIfemailremittance($ifemailremittance)
    {
        $this->ifemailremittance = $ifemailremittance;

        return $this;
    }

    /**
     * Get ifemailremittance
     *
     * @return string
     */
    public function getIfemailremittance()
    {
        return $this->ifemailremittance;
    }

    /**
     * Set updateuser
     *
     * @param integer $updateuser
     *
     * @return TaxPayer
     */
    public function setUpdateuser($updateuser)
    {
        $this->updateuser = $updateuser;

        return $this;
    }

    /**
     * Get updateuser
     *
     * @return integer
     */
    public function getUpdateuser()
    {
        return $this->updateuser;
    }

    /**
     * Set usetinasvatid
     *
     * @param string $usetinasvatid
     *
     * @return TaxPayer
     */
    public function setUsetinasvatid($usetinasvatid)
    {
        $this->usetinasvatid = $usetinasvatid;

        return $this;
    }

    /**
     * Get usetinasvatid
     *
     * @return string
     */
    public function getUsetinasvatid()
    {
        return $this->usetinasvatid;
    }

    /**
     * Set weredano
     *
     * @param integer $weredano
     *
     * @return TaxPayer
     */
    public function setWeredano($weredano)
    {
        $this->weredano = $weredano;

        return $this;
    }

    /**
     * Get weredano
     *
     * @return integer
     */
    public function getWeredano()
    {
        return $this->weredano;
    }

    /**
     * Set kebeledesc
     *
     * @param string $kebeledesc
     *
     * @return TaxPayer
     */
    public function setKebeledesc($kebeledesc)
    {
        $this->kebeledesc = $kebeledesc;

        return $this;
    }

    /**
     * Get kebeledesc
     *
     * @return string
     */
    public function getKebeledesc()
    {
        return $this->kebeledesc;
    }

    /**
     * Set pobox
     *
     * @param string $pobox
     *
     * @return TaxPayer
     */
    public function setPobox($pobox)
    {
        $this->pobox = $pobox;

        return $this;
    }

    /**
     * Get pobox
     *
     * @return string
     */
    public function getPobox()
    {
        return $this->pobox;
    }

    /**
     * Set uselocaldate
     *
     * @param string $uselocaldate
     *
     * @return TaxPayer
     */
    public function setUselocaldate($uselocaldate)
    {
        $this->uselocaldate = $uselocaldate;

        return $this;
    }

    /**
     * Get uselocaldate
     *
     * @return string
     */
    public function getUselocaldate()
    {
        return $this->uselocaldate;
    }

    /**
     * Set tinfromflag
     *
     * @param string $tinfromflag
     *
     * @return TaxPayer
     */
    public function setTinfromflag($tinfromflag)
    {
        $this->tinfromflag = $tinfromflag;

        return $this;
    }

    /**
     * Get tinfromflag
     *
     * @return string
     */
    public function getTinfromflag()
    {
        return $this->tinfromflag;
    }

    /**
     * Set tinsigtascreatedate
     *
     * @param \DateTime $tinsigtascreatedate
     *
     * @return TaxPayer
     */
    public function setTinsigtascreatedate($tinsigtascreatedate)
    {
        $this->tinsigtascreatedate = $tinsigtascreatedate;

        return $this;
    }

    /**
     * Get tinsigtascreatedate
     *
     * @return \DateTime
     */
    public function getTinsigtascreatedate()
    {
        return $this->tinsigtascreatedate;
    }

    /**
     * Set tinsigtaslastupdatedate
     *
     * @param \DateTime $tinsigtaslastupdatedate
     *
     * @return TaxPayer
     */
    public function setTinsigtaslastupdatedate($tinsigtaslastupdatedate)
    {
        $this->tinsigtaslastupdatedate = $tinsigtaslastupdatedate;

        return $this;
    }

    /**
     * Get tinsigtaslastupdatedate
     *
     * @return \DateTime
     */
    public function getTinsigtaslastupdatedate()
    {
        return $this->tinsigtaslastupdatedate;
    }

    /**
     * Set tinupdatedvalues
     *
     * @param integer $tinupdatedvalues
     *
     * @return TaxPayer
     */
    public function setTinupdatedvalues($tinupdatedvalues)
    {
        $this->tinupdatedvalues = $tinupdatedvalues;

        return $this;
    }

    /**
     * Get tinupdatedvalues
     *
     * @return integer
     */
    public function getTinupdatedvalues()
    {
        return $this->tinupdatedvalues;
    }

    /**
     * Set tinvatflagremoved
     *
     * @param string $tinvatflagremoved
     *
     * @return TaxPayer
     */
    public function setTinvatflagremoved($tinvatflagremoved)
    {
        $this->tinvatflagremoved = $tinvatflagremoved;

        return $this;
    }

    /**
     * Get tinvatflagremoved
     *
     * @return string
     */
    public function getTinvatflagremoved()
    {
        return $this->tinvatflagremoved;
    }

    /**
     * Set previoustin
     *
     * @param string $previoustin
     *
     * @return TaxPayer
     */
    public function setPrevioustin($previoustin)
    {
        $this->previoustin = $previoustin;

        return $this;
    }

    /**
     * Get previoustin
     *
     * @return string
     */
    public function getPrevioustin()
    {
        return $this->previoustin;
    }

    /**
     * Set sendcorrtorepr
     *
     * @param string $sendcorrtorepr
     *
     * @return TaxPayer
     */
    public function setSendcorrtorepr($sendcorrtorepr)
    {
        $this->sendcorrtorepr = $sendcorrtorepr;

        return $this;
    }

    /**
     * Get sendcorrtorepr
     *
     * @return string
     */
    public function getSendcorrtorepr()
    {
        return $this->sendcorrtorepr;
    }

    /**
     * Set exporter
     *
     * @param string $exporter
     *
     * @return TaxPayer
     */
    public function setExporter($exporter)
    {
        $this->exporter = $exporter;

        return $this;
    }

    /**
     * Get exporter
     *
     * @return string
     */
    public function getExporter()
    {
        return $this->exporter;
    }

    /**
     * Set regimeFiscal
     *
     * @param integer $regimeFiscal
     *
     * @return TaxPayer
     */
    public function setRegimeFiscal($regimeFiscal)
    {
        $this->regimeFiscal = $regimeFiscal;

        return $this;
    }

    /**
     * Get regimeFiscal
     *
     * @return integer
     */
    public function getRegimeFiscal()
    {
        return $this->regimeFiscal;
    }

    /**
     * Set catno
     *
     * @param integer $catno
     *
     * @return TaxPayer
     */
    public function setCatno($catno)
    {
        $this->catno = $catno;

        return $this;
    }

    /**
     * Get catno
     *
     * @return integer
     */
    public function getCatno()
    {
        return $this->catno;
    }

    /**
     * Set tpstatusno
     *
     * @param integer $tpstatusno
     *
     * @return TaxPayer
     */
    public function setTpstatusno($tpstatusno)
    {
        $this->tpstatusno = $tpstatusno;

        return $this;
    }

    /**
     * Get tpstatusno
     *
     * @return integer
     */
    public function getTpstatusno()
    {
        return $this->tpstatusno;
    }

    /**
     * Set fiscalnosigtasold
     *
     * @param string $fiscalnosigtasold
     *
     * @return TaxPayer
     */
    public function setFiscalnosigtasold($fiscalnosigtasold)
    {
        $this->fiscalnosigtasold = $fiscalnosigtasold;

        return $this;
    }

    /**
     * Get fiscalnosigtasold
     *
     * @return string
     */
    public function getFiscalnosigtasold()
    {
        return $this->fiscalnosigtasold;
    }

    /**
     * Set inactifDate
     *
     * @param \DateTime $inactifDate
     *
     * @return TaxPayer
     */
    public function setInactifDate($inactifDate)
    {
        $this->inactifDate = $inactifDate;

        return $this;
    }

    /**
     * Get inactifDate
     *
     * @return \DateTime
     */
    public function getInactifDate()
    {
        return $this->inactifDate;
    }

    /**
     * Set sigtas
     */
    public function setSigtas($sigtas)
    {
        $this->sigtas = $sigtas;

        return $this;
    }
    /**
     * Get sigtas
     */
    public function getSigtas()
    {
        return $this->sigtas;
    }

    /**
     * Set rs
     */
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }
    /**
     * Get rs
     */
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set SecteurActivite
     */
    public function setSecteurActivite($secteurActivite)
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }
    /**
     * Get SecteurActivite
     */
    public function getSecteurActivite()
    {
        return $this->secteurActivite;
    }

    /**
     * Set sigtasRs
     */
    public function setSigtasRs($sigtasRs)
    {
        $this->sigtasRs = $sigtasRs;

        return $this;
    }
    /**
     * Get sigtasRs
     */
    public function getSigtasRs()
    {
        return $this->sigtasRs;
    }
    /**
     * Set sigtasNc
     */
    public function setSigtasNc($sigtasNc)
    {
        $this->sigtasNc = $sigtasNc;

        return $this;
    }
    /**
     * Get sigtasNc
     */
    public function getSigtasNc()
    {
        return $this->sigtasNc;
    }
     /**
     * Set sigtasMail
     */
    public function setSigtasMail($sigtasMail)
    {
        $this->sigtasMail = $sigtasMail;

        return $this;
    }
    /**
     * Get sigtasMail
     */
    public function getSigtasMail()
    {
        return $this->sigtasMail;
    }

    /**
     * Set sigtasPhone
     */
    public function setSigtasPhone($sigtasPhone)
    {
        $this->sigtasPhone = $sigtasPhone;

        return $this;
    }

    /**
     * Get sigtasPhone
     */
    public function getSigtasPhone()
    {
        return $this->sigtasPhone;
    }

}
