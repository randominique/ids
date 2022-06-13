<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxPayerids
 *
 * @ORM\Table(name="Taxpayerids")
 * @ORM\Entity(repositoryClass="DBundle\Repository\TaxpayeridsRepository")
 */
class Taxpayerids
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     */
    private $tAXPAYERNO;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_TYPE_NO", type="integer")
     */
    private $tPTYPENO;

    /**
     * @var int
     *
     * @ORM\Column(name="COUNTRY_NO", type="integer")
     */
    private $cOUNTRYNO;

    /**
     * @var bool
     *
     * @ORM\Column(name="NSF_CHEQUE", type="boolean")
     */
    private $nSFCHEQUE;

    /**
     * @var int
     *
     * @ORM\Column(name="CITY_NO", type="integer")
     */
    private $cITYNO;

    /**
     * @var string
     *
     * @ORM\Column(name="MAILING_ADDRESS", type="string", length=60, nullable=true)
     */
    private $mAILINGADDRESS;

    /**
     * @var bool
     *
     * @ORM\Column(name="RESIDENT", type="boolean")
     */
    private $rESIDENT;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_TAX_PAYER_NO", type="integer", nullable=true)
     */
    private $rEPTAXPAYERNO;

    /**
     * @var int
     *
     * @ORM\Column(name="FISC_YR_START", type="integer")
     */
    private $fISCYRSTART;

    /**
     * @var int
     *
     * @ORM\Column(name="FISC_YR_END", type="integer")
     */
    private $fISCYREND;

    /**
     * @var int
     *
     * @ORM\Column(name="BRANCH_NO", type="integer", nullable=true)
     */
    private $bRANCHNO;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_TYPE_NO", type="integer", nullable=true)
     */
    private $rEPTYPENO;

    /**
     * @var int
     *
     * @ORM\Column(name="POST_CODE_NO", type="integer", nullable=true)
     */
    private $pOSTCODENO;

    /**
     * @var int
     *
     * @ORM\Column(name="REP_REASON_NO", type="integer", nullable=true)
     */
    private $rEPREASONNO;

    /**
     * @var string
     *
     * @ORM\Column(name="BANK_ACCT_NO", type="string", length=25, nullable=true)
     */
    private $bANKACCTNO;

    /**
     * @var string
     *
     * @ORM\Column(name="REP_TAXR_NAME", type="string", length=40, nullable=true)
     */
    private $rEPTAXRNAME;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ENTER_DATE", type="datetime")
     */
    private $eNTERDATE;

    /**
     * @var int
     *
     * @ORM\Column(name="ENTER_USER", type="integer")
     */
    private $eNTERUSER;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO", type="string", length=20)
     */
    private $fISCALNO;

    /**
     * @var string
     *
     * @ORM\Column(name="STREET_NO", type="string", length=15, nullable=true)
     */
    private $sTREETNO;

    /**
     * @var string
     *
     * @ORM\Column(name="DOOR_NO", type="string", length=5, nullable=true)
     */
    private $dOORNO;

    /**
     * @var int
     *
     * @ORM\Column(name="LOCALITY_NO", type="integer", nullable=true)
     */
    private $lOCALITYNO;

    /**
     * @var string
     *
     * @ORM\Column(name="ACCOUNT_HOLDER", type="string", length=50, nullable=true)
     */
    private $aCCOUNTHOLDER;

    /**
     * @var int
     *
     * @ORM\Column(name="BANK_NO", type="integer")
     */
    private $bANKNO;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="UPDATE_DATE", type="datetime", nullable=true)
     */
    private $uPDATEDATE;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer", nullable=true)
     */
    private $tAXCENTRENO;

    /**
     * @var string
     *
     * @ORM\Column(name="TEMPORARY_TIN", type="string", length=10, nullable=true)
     */
    private $tEMPORARYTIN;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_STYPE_NO", type="integer", nullable=true)
     */
    private $tPSTYPENO;

    /**
     * @var int
     *
     * @ORM\Column(name="LANG_NO", type="integer")
     */
    private $lANGNO;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="SENSITIVE", type="string", length=1)
    //  */
    // private $sENSITIVE = null;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="TAXPYR_COMMENT", type="string", length=200, nullable=true)
    //  */
    // private $tAXPYRCOMMENT;

    // /**
    //  * @var bool
    //  *
    //  * @ORM\Column(name="IF_EMAIL_REMITTANCE", type="boolean")
    //  */
    // private $iFEMAILREMITTANCE;

    // /**
    //  * @var int
    //  *
    //  * @ORM\Column(name="UPDATE_USER", type="integer", nullable=true)
    //  */
    // private $uPDATEUSER;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="USE_TIN_AS_VAT_ID", type="string", length=1, nullable=true)
    //  */
    // private $uSETINASVATID;

    /**
     * @var int
     *
     * @ORM\Column(name="WEREDA_NO", type="integer", nullable=true)
     */
    private $wEREDANO;

    /**
     * @var string
     *
     * @ORM\Column(name="KEBELE_DESC", type="string", length=30, nullable=true)
     */
    private $kEBELEDESC;

    /**
     * @var string
     *
     * @ORM\Column(name="PO_BOX", type="string", length=50, nullable=true)
     */
    private $pOBOX;

    /**
     * @var string
     *
     * @ORM\Column(name="USE_LOCAL_DATE", type="string", length=1, nullable=true)
     */
    private $uSELOCALDATE;

    /**
     * @var string
     *
     * @ORM\Column(name="TIN_FROM_FLAG", type="string", length=1, nullable=true)
     */
    private $tINFROMFLAG;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TIN_SIGTAS_CREATE_DATE", type="datetime", nullable=true)
     */
    private $tINSIGTASCREATEDATE;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TIN_SIGTAS_LAST_UPDATE_DATE", type="datetime", nullable=true)
     */
    private $tINSIGTASLASTUPDATEDATE;

    /**
     * @var int
     *
     * @ORM\Column(name="TIN_UPDATED_VALUES", type="integer", nullable=true)
     */
    private $tINUPDATEDVALUES;

    /**
     * @var string
     *
     * @ORM\Column(name="TIN_VAT_FLAG_REMOVED", type="string", length=1, nullable=true)
     */
    private $tINVATFLAGREMOVED;

    /**
     * @var string
     *
     * @ORM\Column(name="PREVIOUS_TIN", type="string", length=20, nullable=true)
     */
    private $pREVIOUSTIN;

    /**
     * @var string
     *
     * @ORM\Column(name="SEND_CORR_TO_REPR", type="string", length=1)
     */
    private $sENDCORRTOREPR;

    /**
     * @var string
     *
     * @ORM\Column(name="EXPORTER", type="string", length=1, nullable=true)
     */
    private $eXPORTER;

    /**
     * @var int
     *
     * @ORM\Column(name="FISCAL_REGIME_NO", type="integer", nullable=true)
     */
    private $fISCALREGIMENO;

    /**
     * @var int
     *
     * @ORM\Column(name="CAT_NO", type="integer", nullable=true)
     */
    private $cATNO;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_STATUS_NO", type="integer", nullable=true)
     */
    private $tPSTATUSNO;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO_SIGTAS_OLD", type="string", length=20, nullable=true)
     */
    private $fISCALNOSIGTASOLD;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="INACTIF_DATE", type="datetime", nullable=true)
     */
    private $iNACTIFDATE;


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
     * Set tAXPAYERNO
     *
     * @param integer $tAXPAYERNO
     *
     * @return TaxPayerIds
     */
    public function setTAXPAYERNO($tAXPAYERNO)
    {
        $this->tAXPAYERNO = $tAXPAYERNO;

        return $this;
    }

    /**
     * Get tAXPAYERNO
     *
     * @return int
     */
    public function getTAXPAYERNO()
    {
        return $this->tAXPAYERNO;
    }

    /**
     * Set tPTYPENO
     *
     * @param integer $tPTYPENO
     *
     * @return TaxPayerIds
     */
    public function setTPTYPENO($tPTYPENO)
    {
        $this->tPTYPENO = $tPTYPENO;

        return $this;
    }

    /**
     * Get tPTYPENO
     *
     * @return int
     */
    public function getTPTYPENO()
    {
        return $this->tPTYPENO;
    }

    /**
     * Set cOUNTRYNO
     *
     * @param integer $cOUNTRYNO
     *
     * @return TaxPayerIds
     */
    public function setCOUNTRYNO($cOUNTRYNO)
    {
        $this->cOUNTRYNO = $cOUNTRYNO;

        return $this;
    }

    /**
     * Get cOUNTRYNO
     *
     * @return int
     */
    public function getCOUNTRYNO()
    {
        return $this->cOUNTRYNO;
    }

    /**
     * Set nSFCHEQUE
     *
     * @param boolean $nSFCHEQUE
     *
     * @return TaxPayerIds
     */
    public function setNSFCHEQUE($nSFCHEQUE)
    {
        $this->nSFCHEQUE = $nSFCHEQUE;

        return $this;
    }

    /**
     * Get nSFCHEQUE
     *
     * @return bool
     */
    public function getNSFCHEQUE()
    {
        return $this->nSFCHEQUE;
    }

    /**
     * Set cITYNO
     *
     * @param integer $cITYNO
     *
     * @return TaxPayerIds
     */
    public function setCITYNO($cITYNO)
    {
        $this->cITYNO = $cITYNO;

        return $this;
    }

    /**
     * Get cITYNO
     *
     * @return int
     */
    public function getCITYNO()
    {
        return $this->cITYNO;
    }

    /**
     * Set mAILINGADDRESS
     *
     * @param string $mAILINGADDRESS
     *
     * @return TaxPayerIds
     */
    public function setMAILINGADDRESS($mAILINGADDRESS)
    {
        $this->mAILINGADDRESS = $mAILINGADDRESS;

        return $this;
    }

    /**
     * Get mAILINGADDRESS
     *
     * @return string
     */
    public function getMAILINGADDRESS()
    {
        return $this->mAILINGADDRESS;
    }

    /**
     * Set rESIDENT
     *
     * @param boolean $rESIDENT
     *
     * @return TaxPayerIds
     */
    public function setRESIDENT($rESIDENT)
    {
        $this->rESIDENT = $rESIDENT;

        return $this;
    }

    /**
     * Get rESIDENT
     *
     * @return bool
     */
    public function getRESIDENT()
    {
        return $this->rESIDENT;
    }

    /**
     * Set rEPTAXPAYERNO
     *
     * @param integer $rEPTAXPAYERNO
     *
     * @return TaxPayerIds
     */
    public function setREPTAXPAYERNO($rEPTAXPAYERNO)
    {
        $this->rEPTAXPAYERNO = $rEPTAXPAYERNO;

        return $this;
    }

    /**
     * Get rEPTAXPAYERNO
     *
     * @return int
     */
    public function getREPTAXPAYERNO()
    {
        return $this->rEPTAXPAYERNO;
    }

    /**
     * Set fISCYRSTART
     *
     * @param integer $fISCYRSTART
     *
     * @return TaxPayerIds
     */
    public function setFISCYRSTART($fISCYRSTART)
    {
        $this->fISCYRSTART = $fISCYRSTART;

        return $this;
    }

    /**
     * Get fISCYRSTART
     *
     * @return int
     */
    public function getFISCYRSTART()
    {
        return $this->fISCYRSTART;
    }

    /**
     * Set fISCYREND
     *
     * @param integer $fISCYREND
     *
     * @return TaxPayerIds
     */
    public function setFISCYREND($fISCYREND)
    {
        $this->fISCYREND = $fISCYREND;

        return $this;
    }

    /**
     * Get fISCYREND
     *
     * @return int
     */
    public function getFISCYREND()
    {
        return $this->fISCYREND;
    }

    /**
     * Set bRANCHNO
     *
     * @param integer $bRANCHNO
     *
     * @return TaxPayerIds
     */
    public function setBRANCHNO($bRANCHNO)
    {
        $this->bRANCHNO = $bRANCHNO;

        return $this;
    }

    /**
     * Get bRANCHNO
     *
     * @return int
     */
    public function getBRANCHNO()
    {
        return $this->bRANCHNO;
    }

    /**
     * Set rEPTYPENO
     *
     * @param integer $rEPTYPENO
     *
     * @return TaxPayerIds
     */
    public function setREPTYPENO($rEPTYPENO)
    {
        $this->rEPTYPENO = $rEPTYPENO;

        return $this;
    }

    /**
     * Get rEPTYPENO
     *
     * @return int
     */
    public function getREPTYPENO()
    {
        return $this->rEPTYPENO;
    }

    /**
     * Set pOSTCODENO
     *
     * @param integer $pOSTCODENO
     *
     * @return TaxPayerIds
     */
    public function setPOSTCODENO($pOSTCODENO)
    {
        $this->pOSTCODENO = $pOSTCODENO;

        return $this;
    }

    /**
     * Get pOSTCODENO
     *
     * @return int
     */
    public function getPOSTCODENO()
    {
        return $this->pOSTCODENO;
    }

    /**
     * Set rEPREASONNO
     *
     * @param integer $rEPREASONNO
     *
     * @return TaxPayerIds
     */
    public function setREPREASONNO($rEPREASONNO)
    {
        $this->rEPREASONNO = $rEPREASONNO;

        return $this;
    }

    /**
     * Get rEPREASONNO
     *
     * @return int
     */
    public function getREPREASONNO()
    {
        return $this->rEPREASONNO;
    }

    /**
     * Set bANKACCTNO
     *
     * @param string $bANKACCTNO
     *
     * @return TaxPayerIds
     */
    public function setBANKACCTNO($bANKACCTNO)
    {
        $this->bANKACCTNO = $bANKACCTNO;

        return $this;
    }

    /**
     * Get bANKACCTNO
     *
     * @return string
     */
    public function getBANKACCTNO()
    {
        return $this->bANKACCTNO;
    }

    /**
     * Set rEPTAXRNAME
     *
     * @param string $rEPTAXRNAME
     *
     * @return TaxPayerIds
     */
    public function setREPTAXRNAME($rEPTAXRNAME)
    {
        $this->rEPTAXRNAME = $rEPTAXRNAME;

        return $this;
    }

    /**
     * Get rEPTAXRNAME
     *
     * @return string
     */
    public function getREPTAXRNAME()
    {
        return $this->rEPTAXRNAME;
    }

    /**
     * Set eNTERDATE
     *
     * @param \DateTime $eNTERDATE
     *
     * @return TaxPayerIds
     */
    public function setENTERDATE($eNTERDATE)
    {
        $this->eNTERDATE = $eNTERDATE;

        return $this;
    }

    /**
     * Get eNTERDATE
     *
     * @return \DateTime
     */
    public function getENTERDATE()
    {
        return $this->eNTERDATE;
    }

    /**
     * Set eNTERUSER
     *
     * @param integer $eNTERUSER
     *
     * @return TaxPayerIds
     */
    public function setENTERUSER($eNTERUSER)
    {
        $this->eNTERUSER = $eNTERUSER;

        return $this;
    }

    /**
     * Get eNTERUSER
     *
     * @return int
     */
    public function getENTERUSER()
    {
        return $this->eNTERUSER;
    }

    /**
     * Set fISCALNO
     *
     * @param string $fISCALNO
     *
     * @return TaxPayerIds
     */
    public function setFISCALNO($fISCALNO)
    {
        $this->fISCALNO = $fISCALNO;

        return $this;
    }

    /**
     * Get fISCALNO
     *
     * @return string
     */
    public function getFISCALNO()
    {
        return $this->fISCALNO;
    }

    /**
     * Set sTREETNO
     *
     * @param string $sTREETNO
     *
     * @return TaxPayerIds
     */
    public function setSTREETNO($sTREETNO)
    {
        $this->sTREETNO = $sTREETNO;

        return $this;
    }

    /**
     * Get sTREETNO
     *
     * @return string
     */
    public function getSTREETNO()
    {
        return $this->sTREETNO;
    }

    /**
     * Set dOORNO
     *
     * @param string $dOORNO
     *
     * @return TaxPayerIds
     */
    public function setDOORNO($dOORNO)
    {
        $this->dOORNO = $dOORNO;

        return $this;
    }

    /**
     * Get dOORNO
     *
     * @return string
     */
    public function getDOORNO()
    {
        return $this->dOORNO;
    }

    /**
     * Set lOCALITYNO
     *
     * @param integer $lOCALITYNO
     *
     * @return TaxPayerIds
     */
    public function setLOCALITYNO($lOCALITYNO)
    {
        $this->lOCALITYNO = $lOCALITYNO;

        return $this;
    }

    /**
     * Get lOCALITYNO
     *
     * @return int
     */
    public function getLOCALITYNO()
    {
        return $this->lOCALITYNO;
    }

    /**
     * Set aCCOUNTHOLDER
     *
     * @param string $aCCOUNTHOLDER
     *
     * @return TaxPayerIds
     */
    public function setACCOUNTHOLDER($aCCOUNTHOLDER)
    {
        $this->aCCOUNTHOLDER = $aCCOUNTHOLDER;

        return $this;
    }

    /**
     * Get aCCOUNTHOLDER
     *
     * @return string
     */
    public function getACCOUNTHOLDER()
    {
        return $this->aCCOUNTHOLDER;
    }

    /**
     * Set bANKNO
     *
     * @param integer $bANKNO
     *
     * @return TaxPayerIds
     */
    public function setBANKNO($bANKNO)
    {
        $this->bANKNO = $bANKNO;

        return $this;
    }

    /**
     * Get bANKNO
     *
     * @return int
     */
    public function getBANKNO()
    {
        return $this->bANKNO;
    }

    /**
     * Set uPDATEDATE
     *
     * @param \DateTime $uPDATEDATE
     *
     * @return TaxPayerIds
     */
    public function setUPDATEDATE($uPDATEDATE)
    {
        $this->uPDATEDATE = $uPDATEDATE;

        return $this;
    }

    /**
     * Get uPDATEDATE
     *
     * @return \DateTime
     */
    public function getUPDATEDATE()
    {
        return $this->uPDATEDATE;
    }

    /**
     * Set tAXCENTRENO
     *
     * @param integer $tAXCENTRENO
     *
     * @return TaxPayerIds
     */
    public function setTAXCENTRENO($tAXCENTRENO)
    {
        $this->tAXCENTRENO = $tAXCENTRENO;

        return $this;
    }

    /**
     * Get tAXCENTRENO
     *
     * @return int
     */
    public function getTAXCENTRENO()
    {
        return $this->tAXCENTRENO;
    }

    /**
     * Set tEMPORARYTIN
     *
     * @param string $tEMPORARYTIN
     *
     * @return TaxPayerIds
     */
    public function setTEMPORARYTIN($tEMPORARYTIN)
    {
        $this->tEMPORARYTIN = $tEMPORARYTIN;

        return $this;
    }

    /**
     * Get tEMPORARYTIN
     *
     * @return string
     */
    public function getTEMPORARYTIN()
    {
        return $this->tEMPORARYTIN;
    }

    /**
     * Set tPSTYPENO
     *
     * @param integer $tPSTYPENO
     *
     * @return TaxPayerIds
     */
    public function setTPSTYPENO($tPSTYPENO)
    {
        $this->tPSTYPENO = $tPSTYPENO;

        return $this;
    }

    /**
     * Get tPSTYPENO
     *
     * @return int
     */
    public function getTPSTYPENO()
    {
        return $this->tPSTYPENO;
    }

    /**
     * Set lANGNO
     *
     * @param integer $lANGNO
     *
     * @return TaxPayerIds
     */
    public function setLANGNO($lANGNO)
    {
        $this->lANGNO = $lANGNO;

        return $this;
    }

    /**
     * Get lANGNO
     *
     * @return int
     */
    public function getLANGNO()
    {
        return $this->lANGNO;
    }

    /**
     * Set sENSITIVE
     *
     * @param string $sENSITIVE
     *
     * @return TaxPayerIds
     */
    public function setSENSITIVE($sENSITIVE)
    {
        $this->sENSITIVE = $sENSITIVE;

        return $this;
    }

    /**
     * Get sENSITIVE
     *
     * @return string
     */
    public function getSENSITIVE()
    {
        return $this->sENSITIVE;
    }

    /**
     * Set tAXPYRCOMMENT
     *
     * @param string $tAXPYRCOMMENT
     *
     * @return TaxPayerIds
     */
    public function setTAXPYRCOMMENT($tAXPYRCOMMENT)
    {
        $this->tAXPYRCOMMENT = $tAXPYRCOMMENT;

        return $this;
    }

    /**
     * Get tAXPYRCOMMENT
     *
     * @return string
     */
    public function getTAXPYRCOMMENT()
    {
        return $this->tAXPYRCOMMENT;
    }

    /**
     * Set iFEMAILREMITTANCE
     *
     * @param boolean $iFEMAILREMITTANCE
     *
     * @return TaxPayerIds
     */
    public function setIFEMAILREMITTANCE($iFEMAILREMITTANCE)
    {
        $this->iFEMAILREMITTANCE = $iFEMAILREMITTANCE;

        return $this;
    }

    /**
     * Get iFEMAILREMITTANCE
     *
     * @return bool
     */
    public function getIFEMAILREMITTANCE()
    {
        return $this->iFEMAILREMITTANCE;
    }

    /**
     * Set uPDATEUSER
     *
     * @param integer $uPDATEUSER
     *
     * @return TaxPayerIds
     */
    public function setUPDATEUSER($uPDATEUSER)
    {
        $this->uPDATEUSER = $uPDATEUSER;

        return $this;
    }

    /**
     * Get uPDATEUSER
     *
     * @return int
     */
    public function getUPDATEUSER()
    {
        return $this->uPDATEUSER;
    }

    /**
     * Set uSETINASVATID
     *
     * @param string $uSETINASVATID
     *
     * @return TaxPayerIds
     */
    public function setUSETINASVATID($uSETINASVATID)
    {
        $this->uSETINASVATID = $uSETINASVATID;

        return $this;
    }

    /**
     * Get uSETINASVATID
     *
     * @return string
     */
    public function getUSETINASVATID()
    {
        return $this->uSETINASVATID;
    }

    /**
     * Set wEREDANO
     *
     * @param integer $wEREDANO
     *
     * @return TaxPayerIds
     */
    public function setWEREDANO($wEREDANO)
    {
        $this->wEREDANO = $wEREDANO;

        return $this;
    }

    /**
     * Get wEREDANO
     *
     * @return int
     */
    public function getWEREDANO()
    {
        return $this->wEREDANO;
    }

    /**
     * Set kEBELEDESC
     *
     * @param string $kEBELEDESC
     *
     * @return TaxPayerIds
     */
    public function setKEBELEDESC($kEBELEDESC)
    {
        $this->kEBELEDESC = $kEBELEDESC;

        return $this;
    }

    /**
     * Get kEBELEDESC
     *
     * @return string
     */
    public function getKEBELEDESC()
    {
        return $this->kEBELEDESC;
    }

    /**
     * Set pOBOX
     *
     * @param string $pOBOX
     *
     * @return TaxPayerIds
     */
    public function setPOBOX($pOBOX)
    {
        $this->pOBOX = $pOBOX;

        return $this;
    }

    /**
     * Get pOBOX
     *
     * @return string
     */
    public function getPOBOX()
    {
        return $this->pOBOX;
    }

    /**
     * Set uSELOCALDATE
     *
     * @param string $uSELOCALDATE
     *
     * @return TaxPayerIds
     */
    public function setUSELOCALDATE($uSELOCALDATE)
    {
        $this->uSELOCALDATE = $uSELOCALDATE;

        return $this;
    }

    /**
     * Get uSELOCALDATE
     *
     * @return string
     */
    public function getUSELOCALDATE()
    {
        return $this->uSELOCALDATE;
    }

    /**
     * Set tINFROMFLAG
     *
     * @param string $tINFROMFLAG
     *
     * @return TaxPayerIds
     */
    public function setTINFROMFLAG($tINFROMFLAG)
    {
        $this->tINFROMFLAG = $tINFROMFLAG;

        return $this;
    }

    /**
     * Get tINFROMFLAG
     *
     * @return string
     */
    public function getTINFROMFLAG()
    {
        return $this->tINFROMFLAG;
    }

    /**
     * Set tINSIGTASCREATEDATE
     *
     * @param \DateTime $tINSIGTASCREATEDATE
     *
     * @return TaxPayerIds
     */
    public function setTINSIGTASCREATEDATE($tINSIGTASCREATEDATE)
    {
        $this->tINSIGTASCREATEDATE = $tINSIGTASCREATEDATE;

        return $this;
    }

    /**
     * Get tINSIGTASCREATEDATE
     *
     * @return \DateTime
     */
    public function getTINSIGTASCREATEDATE()
    {
        return $this->tINSIGTASCREATEDATE;
    }

    /**
     * Set tINSIGTASLASTUPDATEDATE
     *
     * @param \DateTime $tINSIGTASLASTUPDATEDATE
     *
     * @return TaxPayerIds
     */
    public function setTINSIGTASLASTUPDATEDATE($tINSIGTASLASTUPDATEDATE)
    {
        $this->tINSIGTASLASTUPDATEDATE = $tINSIGTASLASTUPDATEDATE;

        return $this;
    }

    /**
     * Get tINSIGTASLASTUPDATEDATE
     *
     * @return \DateTime
     */
    public function getTINSIGTASLASTUPDATEDATE()
    {
        return $this->tINSIGTASLASTUPDATEDATE;
    }

    /**
     * Set tINUPDATEDVALUES
     *
     * @param integer $tINUPDATEDVALUES
     *
     * @return TaxPayerIds
     */
    public function setTINUPDATEDVALUES($tINUPDATEDVALUES)
    {
        $this->tINUPDATEDVALUES = $tINUPDATEDVALUES;

        return $this;
    }

    /**
     * Get tINUPDATEDVALUES
     *
     * @return int
     */
    public function getTINUPDATEDVALUES()
    {
        return $this->tINUPDATEDVALUES;
    }

    /**
     * Set tINVATFLAGREMOVED
     *
     * @param string $tINVATFLAGREMOVED
     *
     * @return TaxPayerIds
     */
    public function setTINVATFLAGREMOVED($tINVATFLAGREMOVED)
    {
        $this->tINVATFLAGREMOVED = $tINVATFLAGREMOVED;

        return $this;
    }

    /**
     * Get tINVATFLAGREMOVED
     *
     * @return string
     */
    public function getTINVATFLAGREMOVED()
    {
        return $this->tINVATFLAGREMOVED;
    }

    /**
     * Set pREVIOUSTIN
     *
     * @param string $pREVIOUSTIN
     *
     * @return TaxPayerIds
     */
    public function setPREVIOUSTIN($pREVIOUSTIN)
    {
        $this->pREVIOUSTIN = $pREVIOUSTIN;

        return $this;
    }

    /**
     * Get pREVIOUSTIN
     *
     * @return string
     */
    public function getPREVIOUSTIN()
    {
        return $this->pREVIOUSTIN;
    }

    /**
     * Set sENDCORRTOREPR
     *
     * @param string $sENDCORRTOREPR
     *
     * @return TaxPayerIds
     */
    public function setSENDCORRTOREPR($sENDCORRTOREPR)
    {
        $this->sENDCORRTOREPR = $sENDCORRTOREPR;

        return $this;
    }

    /**
     * Get sENDCORRTOREPR
     *
     * @return string
     */
    public function getSENDCORRTOREPR()
    {
        return $this->sENDCORRTOREPR;
    }

    /**
     * Set eXPORTER
     *
     * @param string $eXPORTER
     *
     * @return TaxPayerIds
     */
    public function setEXPORTER($eXPORTER)
    {
        $this->eXPORTER = $eXPORTER;

        return $this;
    }

    /**
     * Get eXPORTER
     *
     * @return string
     */
    public function getEXPORTER()
    {
        return $this->eXPORTER;
    }

    /**
     * Set fISCALREGIMENO
     *
     * @param integer $fISCALREGIMENO
     *
     * @return TaxPayerIds
     */
    public function setFISCALREGIMENO($fISCALREGIMENO)
    {
        $this->fISCALREGIMENO = $fISCALREGIMENO;

        return $this;
    }

    /**
     * Get fISCALREGIMENO
     *
     * @return int
     */
    public function getFISCALREGIMENO()
    {
        return $this->fISCALREGIMENO;
    }

    /**
     * Set cATNO
     *
     * @param integer $cATNO
     *
     * @return TaxPayerIds
     */
    public function setCATNO($cATNO)
    {
        $this->cATNO = $cATNO;

        return $this;
    }

    /**
     * Get cATNO
     *
     * @return int
     */
    public function getCATNO()
    {
        return $this->cATNO;
    }

    /**
     * Set tPSTATUSNO
     *
     * @param integer $tPSTATUSNO
     *
     * @return TaxPayerIds
     */
    public function setTPSTATUSNO($tPSTATUSNO)
    {
        $this->tPSTATUSNO = $tPSTATUSNO;

        return $this;
    }

    /**
     * Get tPSTATUSNO
     *
     * @return int
     */
    public function getTPSTATUSNO()
    {
        return $this->tPSTATUSNO;
    }

    /**
     * Set fISCALNOSIGTASOLD
     *
     * @param string $fISCALNOSIGTASOLD
     *
     * @return TaxPayerIds
     */
    public function setFISCALNOSIGTASOLD($fISCALNOSIGTASOLD)
    {
        $this->fISCALNOSIGTASOLD = $fISCALNOSIGTASOLD;

        return $this;
    }

    /**
     * Get fISCALNOSIGTASOLD
     *
     * @return string
     */
    public function getFISCALNOSIGTASOLD()
    {
        return $this->fISCALNOSIGTASOLD;
    }

    /**
     * Set iNACTIFDATE
     *
     * @param \DateTime $iNACTIFDATE
     *
     * @return TaxPayerIds
     */
    public function setINACTIFDATE($iNACTIFDATE)
    {
        $this->iNACTIFDATE = $iNACTIFDATE;

        return $this;
    }

    /**
     * Get iNACTIFDATE
     *
     * @return \DateTime
     */
    public function getINACTIFDATE()
    {
        return $this->iNACTIFDATE;
    }
}

