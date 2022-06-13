<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assessement
 * 
 * @ORM\Table(name="ASSESSMENT")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\AssessmentRepository")
 */
class Assessment
{
    /**
     * @var int
     * 
     * @ORM\Column(name="ASSESS_NO", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $assessNo;

    /**
     * @var int
     * @ORM\Column(name="ASSESS_TYPE_NO", type="integer", nullable=true)
     */
    public $assessTypeNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="TAX_PAYER_NO", type="integer", nullable=true)
     */
    public $taxPayerNo;

     /**
      * @var int
      * 
      * @ORM\Column(name="TAX_TYPE_NO", type="integer", nullable=true)
      */
     public $taxTypeNo;
    
    /**
     * @var int
     * 
     * @ORM\Column(name="TAX_PERIOD_NO", type="integer", nullable=true)
     */
    public $taxPeriodNo;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="ENTRY_DATE", type="datetime")
     */
    public $entryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="ENTRY_USER", type="string", length=255)
     */
    public $entryUser;

    /**
     * @var int
     * 
     * @ORM\Column(name="ASSESS_NEW_ASSESS_NO", type="integer", nullable=true)
     */
    public $assessNewAssessNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="BATCH_NO", type="integer", nullable=true)
     */
    public $batchNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="COLLECTION_CASE_NO", type="integer", nullable=true)
     */
    public $collectinoCaseNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="ESTAB_NO", type="integer", nullable=true)
     */
    public $estabNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="A_TAX_TO_PAY", type="integer", nullable=true)
     */
    public $aTaxToPay;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="RECEPTION_DATE", type="datetime")
     */
    public $receptionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="PREPARED_BY", type="string", length=255)
     */
    public $preparedBy;

    /**
     * @var int
     * 
     * @ORM\Column(name="TAX_TO_PAY", type="integer", nullable=true)
     */
    public $taxToPay;

    /**
     * @var int
     * 
     * @ORM\Column(name="TAX_PAID", type="integer", nullable=true)
     */
    public $taxPaid;

    /**
     * @var int
     * 
     * @ORM\Column(name="PEN_TO_PAY", type="integer", nullable=true)
     */
    public $penToPay;

    /**
     * @var int
     * 
     * @ORM\Column(name="PEN_PAID", type="integer", nullable=true)
     */
    public $penPaid;

    /**
     * @var int
     * 
     * @ORM\Column(name="INT_TO_PAY", type="integer", nullable=true)
     */
    public $intToPAy;

    /**
     * @var int
     * 
     * @ORM\Column(name="INT_PAID", type="integer", nullable=true)
     */
    public $intPaid;

    /**
     * @var int
     * 
     * @ORM\Column(name="BALANCE", type="integer", nullable=true)
     */
    public $balance;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="TP_START_DATE", type="datetime")
     */
    public $tpStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="TP_END_DATE", type="datetime")
     */
    public $tpEndDate;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="TP_DUE_DATE", type="datetime")
     */
    public $tpDueDate;
    
    /**
    * @var string
    *
    * @ORM\Column(name="ASSESS_COMMENT", type="string", length=255)
    */
   public $assessComment;

   /**
    * @var int
    * 
    * @ORM\Column(name="CALC_STATUS_NO", type="integer", nullable=true)
    */
   public $calcStatusNo;

   /**
    * @var int
    * 
    * @ORM\Column(name="LIC_BASE_NO", type="integer", nullable=true)
    */
   public $licBaseNo;

   /**
    * @var \DateTime
    * 
    * @ORM\Column(name="INT_DATE", type="datetime")
    */
   public $intDate;

   /**
    * @var \DateTime
    * 
    * @ORM\Column(name="PAY_PEN_DATE", type="datetime")
    */
   public $payPenDate;

   /**
    * @var \DateTime
    * 
    * @ORM\Column(name="FILE_PEN_DATE", type="datetime")
    */
   public $filePenDate;

   /**
    * @var int
    * 
    * @ORM\Column(name="FORM_NO", type="integer", nullable=true)
    */
   public $formNo;

   /**
    * @var int
    * 
    * @ORM\Column(name="VERSION_NO", type="integer", nullable=true)
    */
   public $versionNo;

   /**
    * @var int
    * 
    * @ORM\Column(name="LAST_TAX_BALANCE", type="integer", nullable=true)
    */
   public $lastTaxBalance;

   /**
    * @var int
    * 
    * @ORM\Column(name="LAST_PEN_BALANCE", type="integer", nullable=true)
    */
   public $lastPenBalance;

   /**
    * @var int
    * 
    * @ORM\Column(name="LAST_INT_BALANCE", type="integer", nullable=true)
    */
   public $lastIntBalance;

   /**
    * @var int
    * 
    * @ORM\Column(name="PLAST_TAX_BALANCE", type="integer", nullable=true)
    */
   public $plastTaxBalance;

   /**  
    * @var int
    * 
    * @ORM\Column(name="PLAST_PEN_BALANCE", type="integer", nullable=true)
    */
   public $plastPenBalance;

   /**
    * @var int
    * 
    * @ORM\Column(name="PLAST_INT_BALANCE", type="integer", nullable=true)
    */
   public $plastIntBalance;

   /**
    * @var int
    * 
    * @ORM\Column(name="TAX_CENTRE_NO", type="integer", nullable=true)
    */
   public $taxCentreNo;

   /**
    * @var int
    * 
    * @ORM\Column(name="LICENSE_NO", type="integer", nullable=true)
    */
   public $licenseNo;

   /**
    * @var int
    * 
    * @ORM\Column(name="TAX_ACCOUNT_NO", type="integer", nullable=true)
    */
   public $taxAccountNo;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="TP_PAYMENT_DATE", type="datetime")
     */
    public $tpPaymentDate;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="INT_DATE_TEMP", type="datetime")
     */
    public $intDateTemp;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="LAST_PEN_INT_CALC_DATE", type="datetime")
     */
    public $lastPenIntCalcDate;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="LAST_CALC_DATE", type="datetime")
     */
    public $lastCalcDate;

    /**
     * @var int
     * 
     * @ORM\Column(name="TRIGGERING_ASSESS_NO", type="integer", nullable=true)
     */
    public $triggeringAssessNo;

    /**
     * @var int
     * _
     * @ORM\Column(name="REASSESS_RQST_EMPLEE_NO", type="integer", nullable=true)
     */
    public $reassessRqstEmpleeNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="REASSESS_REASON_NO", type="integer", nullable=true)
     */
    public $reassessReasonNo;

    
    /**
     * @var int
     *
     * @ORM\Column(name="RANGE_START_TAX_PERIOD_NO", type="integer", nullable=true)
     */
    public $rangeStartTaxPeriodNo;

    /**
     * @var string
     *
     * @ORM\Column(name="REASSESS_AUTH_FL", type="string", length=255)
     */
    public $reassessAuthFl;

    /**
     * @var int
     *
     * @ORM\Column(name="RECORD_APPROVAL_NO", type="integer", nullable=true)
     */
    public $recordApprovalNo;

    /**
     * @var int
     *
     * @ORM\Column(name="SUPPORT_DOC_NO", type="integer", nullable=true)
     */
    public $supportDocNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_EXEMPTED_AMT", type="integer", nullable=true)
     */
    public $taxExemptedAmt;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="TO_RECEIVED_DATE", type="datetime")
     */
    public $toReceivedDate;
    


    /**
     * Get assessNo
     *
     * @return integer
     */
    public function getAssessNo()
    {
        return $this->assessNo;
    }

    /**
     * Set assessTypeNo
     *
     * @param integer $assessTypeNo
     *
     * @return Assessment
     */
    public function setAssessTypeNo($assessTypeNo)
    {
        $this->assessTypeNo = $assessTypeNo;

        return $this;
    }

    /**
     * Get assessTypeNo
     *
     * @return integer
     */
    public function getAssessTypeNo()
    {
        return $this->assessTypeNo;
    }

    /**
     * Set taxPayerNo
     *
     * @param integer $taxPayerNo
     *
     * @return Assessment
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
     * Set taxTypeNo
     *
     * @param integer $taxTypeNo
     *
     * @return Assessment
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
     * Set taxPeriodNo
     *
     * @param integer $taxPeriodNo
     *
     * @return Assessment
     */
    public function setTaxPeriodNo($taxPeriodNo)
    {
        $this->taxPeriodNo = $taxPeriodNo;

        return $this;
    }

    /**
     * Get taxPeriodNo
     *
     * @return integer
     */
    public function getTaxPeriodNo()
    {
        return $this->taxPeriodNo;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return Assessment
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set entryUser
     *
     * @param string $entryUser
     *
     * @return Assessment
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
     * Set assessNewAssessNo
     *
     * @param integer $assessNewAssessNo
     *
     * @return Assessment
     */
    public function setAssessNewAssessNo($assessNewAssessNo)
    {
        $this->assessNewAssessNo = $assessNewAssessNo;

        return $this;
    }

    /**
     * Get assessNewAssessNo
     *
     * @return integer
     */
    public function getAssessNewAssessNo()
    {
        return $this->assessNewAssessNo;
    }

    /**
     * Set batchNo
     *
     * @param integer $batchNo
     *
     * @return Assessment
     */
    public function setBatchNo($batchNo)
    {
        $this->batchNo = $batchNo;

        return $this;
    }

    /**
     * Get batchNo
     *
     * @return integer
     */
    public function getBatchNo()
    {
        return $this->batchNo;
    }

    /**
     * Set collectinoCaseNo
     *
     * @param integer $collectinoCaseNo
     *
     * @return Assessment
     */
    public function setCollectinoCaseNo($collectinoCaseNo)
    {
        $this->collectinoCaseNo = $collectinoCaseNo;

        return $this;
    }

    /**
     * Get collectinoCaseNo
     *
     * @return integer
     */
    public function getCollectinoCaseNo()
    {
        return $this->collectinoCaseNo;
    }

    /**
     * Set estabNo
     *
     * @param integer $estabNo
     *
     * @return Assessment
     */
    public function setEstabNo($estabNo)
    {
        $this->estabNo = $estabNo;

        return $this;
    }

    /**
     * Get estabNo
     *
     * @return integer
     */
    public function getEstabNo()
    {
        return $this->estabNo;
    }

    /**
     * Set aTaxToPay
     *
     * @param integer $aTaxToPay
     *
     * @return Assessment
     */
    public function setATaxToPay($aTaxToPay)
    {
        $this->aTaxToPay = $aTaxToPay;

        return $this;
    }

    /**
     * Get aTaxToPay
     *
     * @return integer
     */
    public function getATaxToPay()
    {
        return $this->aTaxToPay;
    }

    /**
     * Set receptionDate
     *
     * @param \DateTime $receptionDate
     *
     * @return Assessment
     */
    public function setReceptionDate($receptionDate)
    {
        $this->receptionDate = $receptionDate;

        return $this;
    }

    /**
     * Get receptionDate
     *
     * @return \DateTime
     */
    public function getReceptionDate()
    {
        return $this->receptionDate;
    }

    /**
     * Set preparedBy
     *
     * @param string $preparedBy
     *
     * @return Assessment
     */
    public function setPreparedBy($preparedBy)
    {
        $this->preparedBy = $preparedBy;

        return $this;
    }

    /**
     * Get preparedBy
     *
     * @return string
     */
    public function getPreparedBy()
    {
        return $this->preparedBy;
    }

    /**
     * Set taxToPay
     *
     * @param integer $taxToPay
     *
     * @return Assessment
     */
    public function setTaxToPay($taxToPay)
    {
        $this->taxToPay = $taxToPay;

        return $this;
    }

    /**
     * Get taxToPay
     *
     * @return integer
     */
    public function getTaxToPay()
    {
        return $this->taxToPay;
    }

    /**
     * Set taxPaid
     *
     * @param integer $taxPaid
     *
     * @return Assessment
     */
    public function setTaxPaid($taxPaid)
    {
        $this->taxPaid = $taxPaid;

        return $this;
    }

    /**
     * Get taxPaid
     *
     * @return integer
     */
    public function getTaxPaid()
    {
        return $this->taxPaid;
    }

    /**
     * Set penToPay
     *
     * @param integer $penToPay
     *
     * @return Assessment
     */
    public function setPenToPay($penToPay)
    {
        $this->penToPay = $penToPay;

        return $this;
    }

    /**
     * Get penToPay
     *
     * @return integer
     */
    public function getPenToPay()
    {
        return $this->penToPay;
    }

    /**
     * Set penPaid
     *
     * @param integer $penPaid
     *
     * @return Assessment
     */
    public function setPenPaid($penPaid)
    {
        $this->penPaid = $penPaid;

        return $this;
    }

    /**
     * Get penPaid
     *
     * @return integer
     */
    public function getPenPaid()
    {
        return $this->penPaid;
    }

    /**
     * Set intToPAy
     *
     * @param integer $intToPAy
     *
     * @return Assessment
     */
    public function setIntToPAy($intToPAy)
    {
        $this->intToPAy = $intToPAy;

        return $this;
    }

    /**
     * Get intToPAy
     *
     * @return integer
     */
    public function getIntToPAy()
    {
        return $this->intToPAy;
    }

    /**
     * Set intPaid
     *
     * @param integer $intPaid
     *
     * @return Assessment
     */
    public function setIntPaid($intPaid)
    {
        $this->intPaid = $intPaid;

        return $this;
    }

    /**
     * Get intPaid
     *
     * @return integer
     */
    public function getIntPaid()
    {
        return $this->intPaid;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     *
     * @return Assessment
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set tpStartDate
     *
     * @param \DateTime $tpStartDate
     *
     * @return Assessment
     */
    public function setTpStartDate($tpStartDate)
    {
        $this->tpStartDate = $tpStartDate;

        return $this;
    }

    /**
     * Get tpStartDate
     *
     * @return \DateTime
     */
    public function getTpStartDate()
    {
        return $this->tpStartDate;
    }

    /**
     * Set tpEndDate
     *
     * @param \DateTime $tpEndDate
     *
     * @return Assessment
     */
    public function setTpEndDate($tpEndDate)
    {
        $this->tpEndDate = $tpEndDate;

        return $this;
    }

    /**
     * Get tpEndDate
     *
     * @return \DateTime
     */
    public function getTpEndDate()
    {
        return $this->tpEndDate;
    }

    /**
     * Set tpDueDate
     *
     * @param \DateTime $tpDueDate
     *
     * @return Assessment
     */
    public function setTpDueDate($tpDueDate)
    {
        $this->tpDueDate = $tpDueDate;

        return $this;
    }

    /**
     * Get tpDueDate
     *
     * @return \DateTime
     */
    public function getTpDueDate()
    {
        return $this->tpDueDate;
    }

    /**
     * Set assessComment
     *
     * @param string $assessComment
     *
     * @return Assessment
     */
    public function setAssessComment($assessComment)
    {
        $this->assessComment = $assessComment;

        return $this;
    }

    /**
     * Get assessComment
     *
     * @return string
     */
    public function getAssessComment()
    {
        return $this->assessComment;
    }

    /**
     * Set calcStatusNo
     *
     * @param integer $calcStatusNo
     *
     * @return Assessment
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
     * Set licBaseNo
     *
     * @param integer $licBaseNo
     *
     * @return Assessment
     */
    public function setLicBaseNo($licBaseNo)
    {
        $this->licBaseNo = $licBaseNo;

        return $this;
    }

    /**
     * Get licBaseNo
     *
     * @return integer
     */
    public function getLicBaseNo()
    {
        return $this->licBaseNo;
    }

    /**
     * Set intDate
     *
     * @param \DateTime $intDate
     *
     * @return Assessment
     */
    public function setIntDate($intDate)
    {
        $this->intDate = $intDate;

        return $this;
    }

    /**
     * Get intDate
     *
     * @return \DateTime
     */
    public function getIntDate()
    {
        return $this->intDate;
    }

    /**
     * Set payPenDate
     *
     * @param \DateTime $payPenDate
     *
     * @return Assessment
     */
    public function setPayPenDate($payPenDate)
    {
        $this->payPenDate = $payPenDate;

        return $this;
    }

    /**
     * Get payPenDate
     *
     * @return \DateTime
     */
    public function getPayPenDate()
    {
        return $this->payPenDate;
    }

    /**
     * Set filePenDate
     *
     * @param \DateTime $filePenDate
     *
     * @return Assessment
     */
    public function setFilePenDate($filePenDate)
    {
        $this->filePenDate = $filePenDate;

        return $this;
    }

    /**
     * Get filePenDate
     *
     * @return \DateTime
     */
    public function getFilePenDate()
    {
        return $this->filePenDate;
    }

    /**
     * Set formNo
     *
     * @param integer $formNo
     *
     * @return Assessment
     */
    public function setFormNo($formNo)
    {
        $this->formNo = $formNo;

        return $this;
    }

    /**
     * Get formNo
     *
     * @return integer
     */
    public function getFormNo()
    {
        return $this->formNo;
    }

    /**
     * Set versionNo
     *
     * @param integer $versionNo
     *
     * @return Assessment
     */
    public function setVersionNo($versionNo)
    {
        $this->versionNo = $versionNo;

        return $this;
    }

    /**
     * Get versionNo
     *
     * @return integer
     */
    public function getVersionNo()
    {
        return $this->versionNo;
    }

    /**
     * Set lastTaxBalance
     *
     * @param integer $lastTaxBalance
     *
     * @return Assessment
     */
    public function setLastTaxBalance($lastTaxBalance)
    {
        $this->lastTaxBalance = $lastTaxBalance;

        return $this;
    }

    /**
     * Get lastTaxBalance
     *
     * @return integer
     */
    public function getLastTaxBalance()
    {
        return $this->lastTaxBalance;
    }

    /**
     * Set lastPenBalance
     *
     * @param integer $lastPenBalance
     *
     * @return Assessment
     */
    public function setLastPenBalance($lastPenBalance)
    {
        $this->lastPenBalance = $lastPenBalance;

        return $this;
    }

    /**
     * Get lastPenBalance
     *
     * @return integer
     */
    public function getLastPenBalance()
    {
        return $this->lastPenBalance;
    }

    /**
     * Set lastIntBalance
     *
     * @param integer $lastIntBalance
     *
     * @return Assessment
     */
    public function setLastIntBalance($lastIntBalance)
    {
        $this->lastIntBalance = $lastIntBalance;

        return $this;
    }

    /**
     * Get lastIntBalance
     *
     * @return integer
     */
    public function getLastIntBalance()
    {
        return $this->lastIntBalance;
    }

    /**
     * Set plastTaxBalance
     *
     * @param integer $plastTaxBalance
     *
     * @return Assessment
     */
    public function setPlastTaxBalance($plastTaxBalance)
    {
        $this->plastTaxBalance = $plastTaxBalance;

        return $this;
    }

    /**
     * Get plastTaxBalance
     *
     * @return integer
     */
    public function getPlastTaxBalance()
    {
        return $this->plastTaxBalance;
    }

    /**
     * Set plastPenBalance
     *
     * @param integer $plastPenBalance
     *
     * @return Assessment
     */
    public function setPlastPenBalance($plastPenBalance)
    {
        $this->plastPenBalance = $plastPenBalance;

        return $this;
    }

    /**
     * Get plastPenBalance
     *
     * @return integer
     */
    public function getPlastPenBalance()
    {
        return $this->plastPenBalance;
    }

    /**
     * Set plastIntBalance
     *
     * @param integer $plastIntBalance
     *
     * @return Assessment
     */
    public function setPlastIntBalance($plastIntBalance)
    {
        $this->plastIntBalance = $plastIntBalance;

        return $this;
    }

    /**
     * Get plastIntBalance
     *
     * @return integer
     */
    public function getPlastIntBalance()
    {
        return $this->plastIntBalance;
    }

    /**
     * Set taxCentreNo
     *
     * @param integer $taxCentreNo
     *
     * @return Assessment
     */
    public function setTaxCentreNo($taxCentreNo)
    {
        $this->taxCentreNo = $taxCentreNo;

        return $this;
    }

    /**
     * Get taxCentreNo
     *
     * @return integer
     */
    public function getTaxCentreNo()
    {
        return $this->taxCentreNo;
    }

    /**
     * Set licenseNo
     *
     * @param integer $licenseNo
     *
     * @return Assessment
     */
    public function setLicenseNo($licenseNo)
    {
        $this->licenseNo = $licenseNo;

        return $this;
    }

    /**
     * Get licenseNo
     *
     * @return integer
     */
    public function getLicenseNo()
    {
        return $this->licenseNo;
    }

    /**
     * Set taxAccountNo
     *
     * @param integer $taxAccountNo
     *
     * @return Assessment
     */
    public function setTaxAccountNo($taxAccountNo)
    {
        $this->taxAccountNo = $taxAccountNo;

        return $this;
    }

    /**
     * Get taxAccountNo
     *
     * @return integer
     */
    public function getTaxAccountNo()
    {
        return $this->taxAccountNo;
    }

    /**
     * Set tpPaymentDate
     *
     * @param \DateTime $tpPaymentDate
     *
     * @return Assessment
     */
    public function setTpPaymentDate($tpPaymentDate)
    {
        $this->tpPaymentDate = $tpPaymentDate;

        return $this;
    }

    /**
     * Get tpPaymentDate
     *
     * @return \DateTime
     */
    public function getTpPaymentDate()
    {
        return $this->tpPaymentDate;
    }

    /**
     * Set intDateTemp
     *
     * @param \DateTime $intDateTemp
     *
     * @return Assessment
     */
    public function setIntDateTemp($intDateTemp)
    {
        $this->intDateTemp = $intDateTemp;

        return $this;
    }

    /**
     * Get intDateTemp
     *
     * @return \DateTime
     */
    public function getIntDateTemp()
    {
        return $this->intDateTemp;
    }

    /**
     * Set lastPenIntCalcDate
     *
     * @param \DateTime $lastPenIntCalcDate
     *
     * @return Assessment
     */
    public function setLastPenIntCalcDate($lastPenIntCalcDate)
    {
        $this->lastPenIntCalcDate = $lastPenIntCalcDate;

        return $this;
    }

    /**
     * Get lastPenIntCalcDate
     *
     * @return \DateTime
     */
    public function getLastPenIntCalcDate()
    {
        return $this->lastPenIntCalcDate;
    }

    /**
     * Set lastCalcDate
     *
     * @param \DateTime $lastCalcDate
     *
     * @return Assessment
     */
    public function setLastCalcDate($lastCalcDate)
    {
        $this->lastCalcDate = $lastCalcDate;

        return $this;
    }

    /**
     * Get lastCalcDate
     *
     * @return \DateTime
     */
    public function getLastCalcDate()
    {
        return $this->lastCalcDate;
    }

    /**
     * Set triggeringAssessNo
     *
     * @param integer $triggeringAssessNo
     *
     * @return Assessment
     */
    public function setTriggeringAssessNo($triggeringAssessNo)
    {
        $this->triggeringAssessNo = $triggeringAssessNo;

        return $this;
    }

    /**
     * Get triggeringAssessNo
     *
     * @return integer
     */
    public function getTriggeringAssessNo()
    {
        return $this->triggeringAssessNo;
    }

    /**
     * Set reassessRqstEmpleeNo
     *
     * @param integer $reassessRqstEmpleeNo
     *
     * @return Assessment
     */
    public function setReassessRqstEmpleeNo($reassessRqstEmpleeNo)
    {
        $this->reassessRqstEmpleeNo = $reassessRqstEmpleeNo;

        return $this;
    }

    /**
     * Get reassessRqstEmpleeNo
     *
     * @return integer
     */
    public function getReassessRqstEmpleeNo()
    {
        return $this->reassessRqstEmpleeNo;
    }

    /**
     * Set reassessReasonNo
     *
     * @param integer $reassessReasonNo
     *
     * @return Assessment
     */
    public function setReassessReasonNo($reassessReasonNo)
    {
        $this->reassessReasonNo = $reassessReasonNo;

        return $this;
    }

    /**
     * Get reassessReasonNo
     *
     * @return integer
     */
    public function getReassessReasonNo()
    {
        return $this->reassessReasonNo;
    }

    /**
     * Set rangeStartTaxPeriodNo
     *
     * @param integer $rangeStartTaxPeriodNo
     *
     * @return Assessment
     */
    public function setRangeStartTaxPeriodNo($rangeStartTaxPeriodNo)
    {
        $this->rangeStartTaxPeriodNo = $rangeStartTaxPeriodNo;

        return $this;
    }

    /**
     * Get rangeStartTaxPeriodNo
     *
     * @return integer
     */
    public function getRangeStartTaxPeriodNo()
    {
        return $this->rangeStartTaxPeriodNo;
    }

    /**
     * Set reassessAuthFl
     *
     * @param string $reassessAuthFl
     *
     * @return Assessment
     */
    public function setReassessAuthFl($reassessAuthFl)
    {
        $this->reassessAuthFl = $reassessAuthFl;

        return $this;
    }

    /**
     * Get reassessAuthFl
     *
     * @return string
     */
    public function getReassessAuthFl()
    {
        return $this->reassessAuthFl;
    }

    /**
     * Set recordApprovalNo
     *
     * @param integer $recordApprovalNo
     *
     * @return Assessment
     */
    public function setRecordApprovalNo($recordApprovalNo)
    {
        $this->recordApprovalNo = $recordApprovalNo;

        return $this;
    }

    /**
     * Get recordApprovalNo
     *
     * @return integer
     */
    public function getRecordApprovalNo()
    {
        return $this->recordApprovalNo;
    }

    /**
     * Set supportDocNo
     *
     * @param integer $supportDocNo
     *
     * @return Assessment
     */
    public function setSupportDocNo($supportDocNo)
    {
        $this->supportDocNo = $supportDocNo;

        return $this;
    }

    /**
     * Get supportDocNo
     *
     * @return integer
     */
    public function getSupportDocNo()
    {
        return $this->supportDocNo;
    }

    /**
     * Set taxExemptedAmt
     *
     * @param integer $taxExemptedAmt
     *
     * @return Assessment
     */
    public function setTaxExemptedAmt($taxExemptedAmt)
    {
        $this->taxExemptedAmt = $taxExemptedAmt;

        return $this;
    }

    /**
     * Get taxExemptedAmt
     *
     * @return integer
     */
    public function getTaxExemptedAmt()
    {
        return $this->taxExemptedAmt;
    }

    /**
     * Set toReceivedDate
     *
     * @param \DateTime $toReceivedDate
     *
     * @return Assessment
     */
    public function setToReceivedDate($toReceivedDate)
    {
        $this->toReceivedDate = $toReceivedDate;

        return $this;
    }

    /**
     * Get toReceivedDate
     *
     * @return \DateTime
     */
    public function getToReceivedDate()
    {
        return $this->toReceivedDate;
    }
}
