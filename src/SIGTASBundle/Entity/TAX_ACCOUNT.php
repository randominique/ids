<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TAX_ACCOUNT
 *
 * @ORM\Table(name="tax_account")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\TAX_ACCOUNTRepository")
 */
class TAX_ACCOUNT
{
    /**
     * @var int
     *
     * @ORM\Column(name="TAX_ACCOUNT_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="string", length=255)
     */
    private $taxPayerNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TYPE_NO", type="string", length=255)
     */
    private $taxTypeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="OPENING_TAX", type="integer")
     */
    private $openingTax;

    /**
     * @var int
     *
     * @ORM\Column(name="OPENING_PEN", type="integer")
     */
    private $openingPEN;

    /**
     * @var int
     *
     * @ORM\Column(name="OPENING_INT", type="integer")
     */
    private $openingInt;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_BALANCE", type="integer")
     */
    private $taxBalance;

    /**
     * @var int
     *
     * @ORM\Column(name="PEN_BALANCE", type="integer")
     */
    private $penBalance;

    /**
     * @var int
     *
     * @ORM\Column(name="INT_BALANCE", type="integer")
     */
    private $intBalance;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="REG_DATE", type="oracledate")
     */
    private $regDate;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="ENTRY_DATE", type="oracledate")
     */
    private $entryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ENTRY_USER", type="string")
     */
    private $entryUser;

    /**
     * @var int
     *
     * @ORM\Column(name="LOCALITY_NO", type="integer")
     */
    private $localityNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer")
     */
    private $taxeCentreNo;

    /**
     * @var int
     *
     * @ORM\Column(name="CITY_NO", type="integer")
     */
    private $cityNo;

    /**
     * @var int
     *
     * @ORM\Column(name="COUNTRY_NO", type="integer")
     */
    private $countryNo;

    /**
     * @var int
     *
     * @ORM\Column(name="POST_CODE_NO", type="integer")
     */
    private $postCodeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="REP_NAME", type="string")
     */
    private $repName;

    /**
     * @var string
     *
     * @ORM\Column(name="INST_FLAG", type="string")
     */
    private $instFlag;

    /**
     * @var string
     *
     * @ORM\Column(name="MAILING_ADDRESS", type="string")
     */
    private $mailingAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="INSTALL_TO_PAY", type="string")
     */
    private $installToPay;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_ACCT_NO", type="string")
     */
    private $taxeAcctNo;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="REG_RECEPT_DATE", type="oracledate")
     */
    private $regReceptDate;

    /**
     * @var int
     *
     * @ORM\Column(name="REF_AGAINST_INST", type="integer")
     */
    private $refAgainstDate;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="OPEN_PEN_DATE", type="oracledate")
     */
    private $openPenDate;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="OPEN_INT_DATE", type="oracledate")
     */
    private $openIntDate;

    /**
     * @var int
     *
     * @ORM\Column(name="CALC_STATUS_NO", type="integer")
     */
    private $calcStatusNo;

    /**
     * @var string
     *
     * @ORM\Column(name="ICODE", type="string")
     */
    private $iCode;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="VISIT_DATE", type="oracledate")
     */
    private $visiteDate;

    /**
     * @var string
     *
     * @ORM\Column(name="STREET_NO", type="string")
     */
    private $streetNo;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="CLOSE_DATE", type="oracledate")
     */
    private $closeDate;

    /**
     * @var int
     *
     * @ORM\Column(name="WEREDA_NO", type="integer")
     */
    private $weredaNo;

    /**
     * @var string
     *
     * @ORM\Column(name="KEBELE_DESC", type="string")
     */
    private $kebeleDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="PO_BOX", type="string")
     */
    private $poBox;

    /**
     * @var string
     *
     * @ORM\Column(name="SEND_CORR_TO_REPR", type="string")
     */
    private $sendCorrToRepr;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set taxPayerNo
     *
     * @param string $taxPayerNo
     *
     * @return TAX_ACCOUNT
     */
    public function setTaxPayerNo($taxPayerNo)
    {
        $this->taxPayerNo = $taxPayerNo;

        return $this;
    }

    /**
     * Get taxPayerNo
     *
     * @return string
     */
    public function getTaxPayerNo()
    {
        return $this->taxPayerNo;
    }

    /**
     * Set taxTypeNo
     *
     * @param string $taxTypeNo
     *
     * @return TAX_ACCOUNT
     */
    public function setTaxTypeNo($taxTypeNo)
    {
        $this->taxTypeNo = $taxTypeNo;

        return $this;
    }

    /**
     * Get taxTypeNo
     *
     * @return string
     */
    public function getTaxTypeNo()
    {
        return $this->taxTypeNo;
    }

    /**
     * Set entryDate
     * 
     * @return \Datetime
     */
    public function setEntryDate()
    {
        $this->entryDate = $entryDate;

        return $this;
    }

     /**
     * Get entryDate
     *
     * @return \Datetime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set openingTax
     *
     * @param integer $openingTax
     *
     * @return TAX_ACCOUNT
     */
    public function setOpeningTax($openingTax)
    {
        $this->openingTax = $openingTax;

        return $this;
    }

    /**
     * Get openingTax
     *
     * @return integer
     */
    public function getOpeningTax()
    {
        return $this->openingTax;
    }

    /**
     * Set openingPEN
     *
     * @param integer $openingPEN
     *
     * @return TAX_ACCOUNT
     */
    public function setOpeningPEN($openingPEN)
    {
        $this->openingPEN = $openingPEN;

        return $this;
    }

    /**
     * Get openingPEN
     *
     * @return integer
     */
    public function getOpeningPEN()
    {
        return $this->openingPEN;
    }

    /**
     * Set openingInt
     *
     * @param integer $openingInt
     *
     * @return TAX_ACCOUNT
     */
    public function setOpeningInt($openingInt)
    {
        $this->openingInt = $openingInt;

        return $this;
    }

    /**
     * Get openingInt
     *
     * @return integer
     */
    public function getOpeningInt()
    {
        return $this->openingInt;
    }

    /**
     * Set taxBalance
     *
     * @param integer $taxBalance
     *
     * @return TAX_ACCOUNT
     */
    public function settaxBalance($taxBalance)
    {
        $this->taxBalance = $taxBalance;

        return $this;
    }

    /**
     * Get taxBalance
     *
     * @return integer
     */
    public function getTaxBalance()
    {
        return $this->taxBalance;
    }

    /**
     * Set penBalance
     *
     * @param integer $penBalance
     *
     * @return TAX_ACCOUNT
     */
    public function setPenBalance($penBalance)
    {
        $this->penBalance = $penBalance;

        return $this;
    }

    /**
     * Get penBalance
     *
     * @return integer
     */
    public function getPenBalance()
    {
        return $this->penBalance;
    }

    /**
     * Set intBalance
     *
     * @param integer $intBalance
     *
     * @return TAX_ACCOUNT
     */
    public function setIntBalance($intBalance)
    {
        $this->intBalance = $intBalance;

        return $this;
    }

    /**
     * Get intBalance
     *
     * @return integer
     */
    public function getIntBalance()
    {
        return $this->intBalance;
    }

    /**
     * Set regDate
     *
     * @param oracledate $regDate
     *
     * @return TAX_ACCOUNT
     */
    public function setRegDate($regDate)
    {
        $this->regDate = $regDate;

        return $this;
    }

    /**
     * Get regDate
     *
     * @return oracledate
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /**
     * Set entryUser
     *
     * @param string $entryUser
     *
     * @return TAX_ACCOUNT
     */
    public function setEntryUser($entryUser)
    {
        $this->entryUser = $entryUser;

        return $this;
    }

    /**
     * Get entryUser
     *
     * @return string
     */
    public function getEntryUser()
    {
        return $this->entryUser;
    }

    /**
     * Set localityNo
     *
     * @param integer $localityNo
     *
     * @return TAX_ACCOUNT
     */
    public function setLocalityNo($localityNo)
    {
        $this->localityNo = $localityNo;

        return $this;
    }

    /**
     * Get localityNo
     *
     * @return integer
     */
    public function getLocalityNo()
    {
        return $this->localityNo;
    }

    /**
     * Set taxeCentreNo
     *
     * @param integer $taxeCentreNo
     *
     * @return TAX_ACCOUNT
     */
    public function setTaxeCentreNo($taxeCentreNo)
    {
        $this->taxeCentreNo = $taxeCentreNo;

        return $this;
    }

    /**
     * Get taxeCentreNo
     *
     * @return integer
     */
    public function getTaxeCentreNo()
    {
        return $this->taxeCentreNo;
    }

    /**
     * Set cityNo
     *
     * @param integer $cityNo
     *
     * @return TAX_ACCOUNT
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
     * Set countryNo
     *
     * @param integer $countryNo
     *
     * @return TAX_ACCOUNT
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
     * Set postCodeNo
     *
     * @param integer $postCodeNo
     *
     * @return TAX_ACCOUNT
     */
    public function setPostCodeNo($postCodeNo)
    {
        $this->postCodeNo = $postCodeNo;

        return $this;
    }

    /**
     * Get postCodeNo
     *
     * @return integer
     */
    public function getPostCodeNo()
    {
        return $this->postCodeNo;
    }

    /**
     * Set repName
     *
     * @param string $repName
     *
     * @return TAX_ACCOUNT
     */
    public function setRepName($repName)
    {
        $this->repName = $repName;

        return $this;
    }

    /**
     * Get repName
     *
     * @return string
     */
    public function getRepName()
    {
        return $this->repName;
    }

    /**
     * Set instFlag
     *
     * @param string $instFlag
     *
     * @return TAX_ACCOUNT
     */
    public function setInstFlag($instFlag)
    {
        $this->instFlag = $instFlag;

        return $this;
    }

    /**
     * Get instFlag
     *
     * @return string
     */
    public function getInstFlag()
    {
        return $this->instFlag;
    }

    /**
     * Set mailingAddress
     *
     * @param string $mailingAddress
     *
     * @return TAX_ACCOUNT
     */
    public function setMailingAddress($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;

        return $this;
    }

    /**
     * Get mailingAddress
     *
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * Set installToPay
     *
     * @param string $installToPay
     *
     * @return TAX_ACCOUNT
     */
    public function setInstallToPay($installToPay)
    {
        $this->installToPay = $installToPay;

        return $this;
    }

    /**
     * Get installToPay
     *
     * @return string
     */
    public function getInstallToPay()
    {
        return $this->installToPay;
    }

    /**
     * Set taxeAcctNo
     *
     * @param string $taxeAcctNo
     *
     * @return TAX_ACCOUNT
     */
    public function setTaxeAcctNo($taxeAcctNo)
    {
        $this->taxeAcctNo = $taxeAcctNo;

        return $this;
    }

    /**
     * Get taxeAcctNo
     *
     * @return string
     */
    public function getTaxeAcctNo()
    {
        return $this->taxeAcctNo;
    }

    /**
     * Set regReceptDate
     *
     * @param oracledate $regReceptDate
     *
     * @return TAX_ACCOUNT
     */
    public function setRegReceptDate($regReceptDate)
    {
        $this->regReceptDate = $regReceptDate;

        return $this;
    }

    /**
     * Get regReceptDate
     *
     * @return oracledate
     */
    public function getRegReceptDate()
    {
        return $this->regReceptDate;
    }

    /**
     * Set refAgainstDate
     *
     * @param integer $refAgainstDate
     *
     * @return TAX_ACCOUNT
     */
    public function setRefAgainstDate($refAgainstDate)
    {
        $this->refAgainstDate = $refAgainstDate;

        return $this;
    }

    /**
     * Get refAgainstDate
     *
     * @return integer
     */
    public function getRefAgainstDate()
    {
        return $this->refAgainstDate;
    }

    /**
     * Set openPenDate
     *
     * @param oracledate $openPenDate
     *
     * @return TAX_ACCOUNT
     */
    public function setOpenPenDate($openPenDate)
    {
        $this->openPenDate = $openPenDate;

        return $this;
    }

    /**
     * Get openPenDate
     *
     * @return oracledate
     */
    public function getOpenPenDate()
    {
        return $this->openPenDate;
    }

    /**
     * Set openIntDate
     *
     * @param oracledate $openIntDate
     *
     * @return TAX_ACCOUNT
     */
    public function setOpenIntDate($openIntDate)
    {
        $this->openIntDate = $openIntDate;

        return $this;
    }

    /**
     * Get openIntDate
     *
     * @return oracledate
     */
    public function getOpenIntDate()
    {
        return $this->openIntDate;
    }

    /**
     * Set calcStatusNo
     *
     * @param integer $calcStatusNo
     *
     * @return TAX_ACCOUNT
     */
    public function setCalcStatusNo($calcStatusNo)
    {
        $this->calcStatusNo = $calcStatusNo;

        return $this;
    }

    /**
     * Get calcStatusNo
     *
     * @return integer
     */
    public function getCalcStatusNo()
    {
        return $this->calcStatusNo;
    }

    /**
     * Set iCode
     *
     * @param string $iCode
     *
     * @return TAX_ACCOUNT
     */
    public function setICode($iCode)
    {
        $this->iCode = $iCode;

        return $this;
    }

    /**
     * Get iCode
     *
     * @return string
     */
    public function getICode()
    {
        return $this->iCode;
    }

    /**
     * Set visiteDate
     *
     * @param oracledate $visiteDate
     *
     * @return TAX_ACCOUNT
     */
    public function setVisiteDate($visiteDate)
    {
        $this->visiteDate = $visiteDate;

        return $this;
    }

    /**
     * Get visiteDate
     *
     * @return oracledate
     */
    public function getVisiteDate()
    {
        return $this->visiteDate;
    }

    /**
     * Set streetNo
     *
     * @param string $streetNo
     *
     * @return TAX_ACCOUNT
     */
    public function setStreetNo($streetNo)
    {
        $this->streetNo = $streetNo;

        return $this;
    }

    /**
     * Get streetNo
     *
     * @return string
     */
    public function getStreetNo()
    {
        return $this->streetNo;
    }

    /**
     * Set closeDate
     *
     * @param oracledate $closeDate
     *
     * @return TAX_ACCOUNT
     */
    public function setCloseDate($closeDate)
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    /**
     * Get closeDate
     *
     * @return oracledate
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Set weredaNo
     *
     * @param integer $weredaNo
     *
     * @return TAX_ACCOUNT
     */
    public function setWeredaNo($weredaNo)
    {
        $this->weredaNo = $weredaNo;

        return $this;
    }

    /**
     * Get weredaNo
     *
     * @return integer
     */
    public function getWeredaNo()
    {
        return $this->weredaNo;
    }

    /**
     * Set kebeleDesc
     *
     * @param string $kebeleDesc
     *
     * @return TAX_ACCOUNT
     */
    public function setKebeleDesc($kebeleDesc)
    {
        $this->kebeleDesc = $kebeleDesc;

        return $this;
    }

    /**
     * Get kebeleDesc
     *
     * @return string
     */
    public function getKebeleDesc()
    {
        return $this->kebeleDesc;
    }

    /**
     * Set poBox
     *
     * @param string $poBox
     *
     * @return TAX_ACCOUNT
     */
    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;

        return $this;
    }

    /**
     * Get poBox
     *
     * @return string
     */
    public function getPoBox()
    {
        return $this->poBox;
    }

    /**
     * Set sendCorrToRepr
     *
     * @param string $sendCorrToRepr
     *
     * @return TAX_ACCOUNT
     */
    public function setSendCorrToRepr($sendCorrToRepr)
    {
        $this->sendCorrToRepr = $sendCorrToRepr;

        return $this;
    }

    /**
     * Get sendCorrToRepr
     *
     * @return string
     */
    public function getSendCorrToRepr()
    {
        return $this->sendCorrToRepr;
    }
}
