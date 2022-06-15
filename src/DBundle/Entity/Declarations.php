<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Declarations
 *
 * @ORM\Table(name="Declarations")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DeclarationsRepository")
 */
class Declarations
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
     * @ORM\Column(name="DOC_NO", type="integer")
     */
    private $docNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TYPE_NO", type="integer")
     */
    private $taxTypeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TYPE", type="string")
     */
    private $taxType;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PERIOD_NO", type="integer")
     */
    private $taxPeriodNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     */
    private $taxPayerNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NIF", type="string")
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="RAISON_SOCIALE", type="string")
     */
    private $rs;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_TYPE_NO", type="integer")
     */
    private $docTypeNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CREATED_DATE", type="datetime")
     */
    private $createdDate;

    /**
     * @var int
     *
     * @ORM\Column(name="LETTER_NO", type="integer")
     */
    private $letterNo;

    /**
     * @var int
     *
     * @ORM\Column(name="ASSESS_NO", type="integer")
     */
    private $assessNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RECEIVED_DATE", type="date")
     */
    private $receivedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="PRINTED_DATE", type="datetime")
     */
    private $printedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DOC_TP_START_DATE", type="datetime")
     */
    private $docTpStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DOC_TP_END_DATE", type="datetime")
     */
    private $docTpEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DOC_TP_DUE_DATE", type="datetime")
     */
    private $docTpDueDate;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_TP_MONTH", type="integer")
     */
    private $docTpMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_TP_YEAR", type="integer")
     */
    private $docTpYear;

    /**
     * @var string
     *
     * @ORM\Column(name="PERSONAL_TEXT", type="string", length=2000, nullable=true)
     */
    private $personalText;

    /**
     * @var int
     *
     * @ORM\Column(name="IRD_FILE_NO", type="integer")
     */
    private $irdFileNo;

    /**
     * @var int
     *
     * @ORM\Column(name="INSTALL_RATE_NO", type="integer")
     */
    private $installRateNo;

    /**
     * @var int
     *
     * @ORM\Column(name="JOB_NO", type="integer")
     */
    private $jobNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="STATE_CHANGE_DATE", type="datetime")
     */
    private $stateChangeDate;

    /**
     * @var string
     *
     * @ORM\Column(name="STATE_CHANGE_USER", type="string")
     */
    private $stateChangeUser;

    /**
     * @var string
     *
     * @ORM\Column(name="RECEIPT", type="string", length=255)
     */
    private $receipt;

    /**
     * @var int
     *
     * @ORM\Column(name="ESTAB_NO", type="integer")
     */
    private $estabNo;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_STATE_NO", type="integer")
     */
    private $docStateNo;

    /**
     * @var int
     *
     * @ORM\Column(name="FORM_NO", type="integer")
     */
    private $formNo;

    /**
     * @var int
     *
     * @ORM\Column(name="VERSION_NO", type="integer", nullable=true)
     */
    private $versionNo;

    /**
     * @var int
     *
     * @ORM\Column(name="LIC_BASE_NO", type="integer", nullable=true)
     */
    private $licBaseNo;

    /**
     * @var int
     *
     * @ORM\Column(name="PAY_AGREE_NO", type="integer", nullable=true)
     */
    private $payAgreeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_INST_MONTH", type="integer", nullable=true)
     */
    private $docInstMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_INST_YEAR", type="integer", nullable=true)
     */
    private $docInstYear;

    /**
     * @var string
     *
     * @ORM\Column(name="ENTRY_USER", type="string", length=255)
     */
    private $entryUser;

    /**
     * @var int
     *
     * @ORM\Column(name="MV_REGIS_NO", type="integer", nullable=true)
     */
    private $mvRegisNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer")
     */
    private $taxCentreNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DOC_TP_PAYMENT_DATE", type="datetime", nullable=true)
     */
    private $docTpPaymentDate;

    /**
     * @var int
     *
     * @ORM\Column(name="LICENSE_NO", type="integer", nullable=true)
     */
    private $licenseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="EXT_DOC_NO", type="string", length=255)
     */
    private $extDocNo;

    /**
     * @var string
     *
     * @ORM\Column(name="REASON", type="string", length=255, nullable=true)
     */
    private $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="BATCH_NO", type="integer", nullable=true)
     */
    private $batchNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TP_INSTALL_RATE_NO", type="integer", nullable=true)
     */
    private $tpInstallRateNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DELIVERED_DATE", type="datetime", nullable=true)
     */
    private $deliveredDate;

    /**
     * @var int
     *
     * @ORM\Column(name="DISTRIBUTED_TO_IRD_EMP_NO", type="integer", nullable=true)
     */
    private $distributedToIrdEmpNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DISTRIBUTED_DATE", type="datetime", nullable=true)
     */
    private $distributedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMENTS", type="string", length=500, nullable=true)
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="RECEIPT_PRINTED", type="string", length=255, nullable=true)
     */
    private $receiptPrinted;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_TP_WEEK", type="integer", nullable=true)
     */
    private $docTpWeek;

    /**
     * @var int
     *
     * @ORM\Column(name="DELIVERED_BY_IRD_EMP_NO", type="integer", nullable=true)
     */
    private $deliveredByIrdEmpNo;

    /**
     * @var int
     *
     * @ORM\Column(name="PHYSICAL_OBJECT_NO", type="integer", nullable=true)
     */
    private $physicalObjectNo;

    /**
     * @var string
     *
     * @ORM\Column(name="DOC_SUBJECT", type="string", length=255, nullable=true)
     */
    private $docSubject;

    /**
     * @var int
     *
     * @ORM\Column(name="LOST_BY_IRD_EMP_NO", type="integer", nullable=true)
     */
    private $lostByIrdEmpNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LOST_DATE", type="datetime", nullable=true)
     */
    private $lostDate;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_PAYER_NAME", type="string", length=255, nullable=true)
     */
    private $taxPayerName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="NOTIF_DATE", type="datetime", nullable=true)
     */
    private $notifDate;

    /**
     * @var int
     *
     * @ORM\Column(name="TITRE_NO", type="integer", nullable=true)
     */
    private $titreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TITRE_ORIGINE", type="string", length=255, nullable=true)
     */
    private $titreOrigine;


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
     * Set docNo
     *
     * @param integer $docNo
     *
     * @return Declarations
     */
    public function setDocNo($docNo)
    {
        $this->docNo = $docNo;

        return $this;
    }

    /**
     * Get docNo
     *
     * @return int
     */
    public function getDocNo()
    {
        return $this->docNo;
    }

    /**
     * Set taxTypeNo
     *
     * @param integer $taxTypeNo
     *
     * @return Declarations
     */
    public function setTaxTypeNo($taxTypeNo)
    {
        $this->taxTypeNo = $taxTypeNo;

        return $this;
    }

    /**
     * Get taxTypeNo
     *
     * @return int
     */
    public function getTaxTypeNo()
    {
        return $this->taxTypeNo;
    }

    /**
     * Set taxType
     *
     * @param string $taxType
     *
     * @return Declarations
     */
    public function setTaxType($taxType)
    {
        $this->taxType = $taxType;

        return $this;
    }

    /**
     * Get taxType
     *
     * @return string
     */
    public function getTaxType()
    {
        return $this->taxType;
    }

    /**
     * Set taxPeriodNo
     *
     * @param integer $taxPeriodNo
     *
     * @return Declarations
     */
    public function setTaxPeriodNo($taxPeriodNo)
    {
        $this->taxPeriodNo = $taxPeriodNo;

        return $this;
    }

    /**
     * Get taxPeriodNo
     *
     * @return int
     */
    public function getTaxPeriodNo()
    {
        return $this->taxPeriodNo;
    }

    /**
     * Set taxPayerNo
     *
     * @param integer $taxPayerNo
     *
     * @return Declarations
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
     * @return Declarations
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
     * Set rs
     *
     * @param string $rs
     *
     * @return Declarations
     */
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }

    /**
     * Get rs
     *
     * @return string
     */
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set docTypeNo
     *
     * @param integer $docTypeNo
     *
     * @return Declarations
     */
    public function setDocTypeNo($docTypeNo)
    {
        $this->docTypeNo = $docTypeNo;

        return $this;
    }

    /**
     * Get docTypeNo
     *
     * @return int
     */
    public function getDocTypeNo()
    {
        return $this->docTypeNo;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Declarations
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set letterNo
     *
     * @param integer $letterNo
     *
     * @return Declarations
     */
    public function setLetterNo($letterNo)
    {
        $this->letterNo = $letterNo;

        return $this;
    }

    /**
     * Get letterNo
     *
     * @return int
     */
    public function getLetterNo()
    {
        return $this->letterNo;
    }

    /**
     * Set assessNo
     *
     * @param integer $assessNo
     *
     * @return Declarations
     */
    public function setAssessNo($assessNo)
    {
        $this->assessNo = $assessNo;

        return $this;
    }

    /**
     * Get assessNo
     *
     * @return int
     */
    public function getAssessNo()
    {
        return $this->assessNo;
    }

    /**
     * Set receivedDate
     *
     * @param \DateTime $receivedDate
     *
     * @return Declarations
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;

        return $this;
    }

    /**
     * Get receivedDate
     *
     * @return \DateTime
     */
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    /**
     * Set printedDate
     *
     * @param \DateTime $printedDate
     *
     * @return Declarations
     */
    public function setPrintedDate($printedDate)
    {
        $this->printedDate = $printedDate;

        return $this;
    }

    /**
     * Get printedDate
     *
     * @return \DateTime
     */
    public function getPrintedDate()
    {
        return $this->printedDate;
    }

    /**
     * Set docTpStartDate
     *
     * @param \DateTime $docTpStartDate
     *
     * @return Declarations
     */
    public function setDocTpStartDate($docTpStartDate)
    {
        $this->docTpStartDate = $docTpStartDate;

        return $this;
    }

    /**
     * Get docTpStartDate
     *
     * @return \DateTime
     */
    public function getDocTpStartDate()
    {
        return $this->docTpStartDate;
    }

    /**
     * Set docTpEndDate
     *
     * @param \DateTime $docTpEndDate
     *
     * @return Declarations
     */
    public function setDocTpEndDate($docTpEndDate)
    {
        $this->docTpEndDate = $docTpEndDate;

        return $this;
    }

    /**
     * Get docTpEndDate
     *
     * @return \DateTime
     */
    public function getDocTpEndDate()
    {
        return $this->docTpEndDate;
    }

    /**
     * Set docTpDueDate
     *
     * @param \DateTime $docTpDueDate
     *
     * @return Declarations
     */
    public function setDocTpDueDate($docTpDueDate)
    {
        $this->docTpDueDate = $docTpDueDate;

        return $this;
    }

    /**
     * Get docTpDueDate
     *
     * @return \DateTime
     */
    public function getDocTpDueDate()
    {
        return $this->docTpDueDate;
    }

    /**
     * Set docTpMonth
     *
     * @param integer $docTpMonth
     *
     * @return Declarations
     */
    public function setDocTpMonth($docTpMonth)
    {
        $this->docTpMonth = $docTpMonth;

        return $this;
    }

    /**
     * Get docTpMonth
     *
     * @return int
     */
    public function getDocTpMonth()
    {
        return $this->docTpMonth;
    }

    /**
     * Set docTpYear
     *
     * @param integer $docTpYear
     *
     * @return Declarations
     */
    public function setDocTpYear($docTpYear)
    {
        $this->docTpYear = $docTpYear;

        return $this;
    }

    /**
     * Get docTpYear
     *
     * @return int
     */
    public function getDocTpYear()
    {
        return $this->docTpYear;
    }

    /**
     * Set personalText
     *
     * @param string $personalText
     *
     * @return Declarations
     */
    public function setPersonalText($personalText)
    {
        $this->personalText = $personalText;

        return $this;
    }

    /**
     * Get personalText
     *
     * @return string
     */
    public function getPersonalText()
    {
        return $this->personalText;
    }

    /**
     * Set irdFileNo
     *
     * @param integer $irdFileNo
     *
     * @return Declarations
     */
    public function setIrdFileNo($irdFileNo)
    {
        $this->irdFileNo = $irdFileNo;

        return $this;
    }

    /**
     * Get irdFileNo
     *
     * @return int
     */
    public function getIrdFileNo()
    {
        return $this->irdFileNo;
    }

    /**
     * Set installRateNo
     *
     * @param integer $installRateNo
     *
     * @return Declarations
     */
    public function setInstallRateNo($installRateNo)
    {
        $this->installRateNo = $installRateNo;

        return $this;
    }

    /**
     * Get installRateNo
     *
     * @return int
     */
    public function getInstallRateNo()
    {
        return $this->installRateNo;
    }

    /**
     * Set jobNo
     *
     * @param integer $jobNo
     *
     * @return Declarations
     */
    public function setJobNo($jobNo)
    {
        $this->jobNo = $jobNo;

        return $this;
    }

    /**
     * Get jobNo
     *
     * @return int
     */
    public function getJobNo()
    {
        return $this->jobNo;
    }

    /**
     * Set stateChangeDate
     *
     * @param \DateTime $stateChangeDate
     *
     * @return Declarations
     */
    public function setStateChangeDate($stateChangeDate)
    {
        $this->stateChangeDate = $stateChangeDate;

        return $this;
    }

    /**
     * Get stateChangeDate
     *
     * @return \DateTime
     */
    public function getStateChangeDate()
    {
        return $this->stateChangeDate;
    }

    /**
     * Set stateChangeUser
     *
     * @param string $stateChangeUser
     *
     * @return Declarations
     */
    public function setStateChangeUser($stateChangeUser)
    {
        $this->stateChangeUser = $stateChangeUser;

        return $this;
    }

    /**
     * Get stateChangeUser
     *
     * @return string
     */
    public function getStateChangeUser()
    {
        return $this->stateChangeUser;
    }

    /**
     * Set receipt
     *
     * @param string $receipt
     *
     * @return Declarations
     */
    public function setReceipt($receipt)
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * Get receipt
     *
     * @return string
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * Set estabNo
     *
     * @param integer $estabNo
     *
     * @return Declarations
     */
    public function setEstabNo($estabNo)
    {
        $this->estabNo = $estabNo;

        return $this;
    }

    /**
     * Get estabNo
     *
     * @return int
     */
    public function getEstabNo()
    {
        return $this->estabNo;
    }

    /**
     * Set docStateNo
     *
     * @param integer $docStateNo
     *
     * @return Declarations
     */
    public function setDocStateNo($docStateNo)
    {
        $this->docStateNo = $docStateNo;

        return $this;
    }

    /**
     * Get docStateNo
     *
     * @return int
     */
    public function getDocStateNo()
    {
        return $this->docStateNo;
    }

    /**
     * Set formNo
     *
     * @param integer $formNo
     *
     * @return Declarations
     */
    public function setFormNo($formNo)
    {
        $this->formNo = $formNo;

        return $this;
    }

    /**
     * Get formNo
     *
     * @return int
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
     * @return Declarations
     */
    public function setVersionNo($versionNo)
    {
        $this->versionNo = $versionNo;

        return $this;
    }

    /**
     * Get versionNo
     *
     * @return int
     */
    public function getVersionNo()
    {
        return $this->versionNo;
    }

    /**
     * Set licBaseNo
     *
     * @param integer $licBaseNo
     *
     * @return Declarations
     */
    public function setLicBaseNo($licBaseNo)
    {
        $this->licBaseNo = $licBaseNo;

        return $this;
    }

    /**
     * Get licBaseNo
     *
     * @return int
     */
    public function getLicBaseNo()
    {
        return $this->licBaseNo;
    }

    /**
     * Set payAgreeNo
     *
     * @param integer $payAgreeNo
     *
     * @return Declarations
     */
    public function setPayAgreeNo($payAgreeNo)
    {
        $this->payAgreeNo = $payAgreeNo;

        return $this;
    }

    /**
     * Get payAgreeNo
     *
     * @return int
     */
    public function getPayAgreeNo()
    {
        return $this->payAgreeNo;
    }

    /**
     * Set docInstMonth
     *
     * @param integer $docInstMonth
     *
     * @return Declarations
     */
    public function setDocInstMonth($docInstMonth)
    {
        $this->docInstMonth = $docInstMonth;

        return $this;
    }

    /**
     * Get docInstMonth
     *
     * @return int
     */
    public function getDocInstMonth()
    {
        return $this->docInstMonth;
    }

    /**
     * Set docInstYear
     *
     * @param integer $docInstYear
     *
     * @return Declarations
     */
    public function setDocInstYear($docInstYear)
    {
        $this->docInstYear = $docInstYear;

        return $this;
    }

    /**
     * Get docInstYear
     *
     * @return int
     */
    public function getDocInstYear()
    {
        return $this->docInstYear;
    }

    /**
     * Set entryUser
     *
     * @param string $entryUser
     *
     * @return Declarations
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
     * Set mvRegisNo
     *
     * @param integer $mvRegisNo
     *
     * @return Declarations
     */
    public function setMvRegisNo($mvRegisNo)
    {
        $this->mvRegisNo = $mvRegisNo;

        return $this;
    }

    /**
     * Get mvRegisNo
     *
     * @return int
     */
    public function getMvRegisNo()
    {
        return $this->mvRegisNo;
    }

    /**
     * Set taxCentreNo
     *
     * @param integer $taxCentreNo
     *
     * @return Declarations
     */
    public function setTaxCentreNo($taxCentreNo)
    {
        $this->taxCentreNo = $taxCentreNo;

        return $this;
    }

    /**
     * Get taxCentreNo
     *
     * @return int
     */
    public function getTaxCentreNo()
    {
        return $this->taxCentreNo;
    }

    /**
     * Set docTpPaymentDate
     *
     * @param \DateTime $docTpPaymentDate
     *
     * @return Declarations
     */
    public function setDocTpPaymentDate($docTpPaymentDate)
    {
        $this->docTpPaymentDate = $docTpPaymentDate;

        return $this;
    }

    /**
     * Get docTpPaymentDate
     *
     * @return \DateTime
     */
    public function getDocTpPaymentDate()
    {
        return $this->docTpPaymentDate;
    }

    /**
     * Set licenseNo
     *
     * @param integer $licenseNo
     *
     * @return Declarations
     */
    public function setLicenseNo($licenseNo)
    {
        $this->licenseNo = $licenseNo;

        return $this;
    }

    /**
     * Get licenseNo
     *
     * @return int
     */
    public function getLicenseNo()
    {
        return $this->licenseNo;
    }

    /**
     * Set extDocNo
     *
     * @param string $extDocNo
     *
     * @return Declarations
     */
    public function setExtDocNo($extDocNo)
    {
        $this->extDocNo = $extDocNo;

        return $this;
    }

    /**
     * Get extDocNo
     *
     * @return string
     */
    public function getExtDocNo()
    {
        return $this->extDocNo;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return Declarations
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set batchNo
     *
     * @param integer $batchNo
     *
     * @return Declarations
     */
    public function setBatchNo($batchNo)
    {
        $this->batchNo = $batchNo;

        return $this;
    }

    /**
     * Get batchNo
     *
     * @return int
     */
    public function getBatchNo()
    {
        return $this->batchNo;
    }

    /**
     * Set tpInstallRateNo
     *
     * @param integer $tpInstallRateNo
     *
     * @return Declarations
     */
    public function setTpInstallRateNo($tpInstallRateNo)
    {
        $this->tpInstallRateNo = $tpInstallRateNo;

        return $this;
    }

    /**
     * Get tpInstallRateNo
     *
     * @return int
     */
    public function getTpInstallRateNo()
    {
        return $this->tpInstallRateNo;
    }

    /**
     * Set deliveredDate
     *
     * @param \DateTime $deliveredDate
     *
     * @return Declarations
     */
    public function setDeliveredDate($deliveredDate)
    {
        $this->deliveredDate = $deliveredDate;

        return $this;
    }

    /**
     * Get deliveredDate
     *
     * @return \DateTime
     */
    public function getDeliveredDate()
    {
        return $this->deliveredDate;
    }

    /**
     * Set distributedToIrdEmpNo
     *
     * @param integer $distributedToIrdEmpNo
     *
     * @return Declarations
     */
    public function setDistributedToIrdEmpNo($distributedToIrdEmpNo)
    {
        $this->distributedToIrdEmpNo = $distributedToIrdEmpNo;

        return $this;
    }

    /**
     * Get distributedToIrdEmpNo
     *
     * @return int
     */
    public function getDistributedToIrdEmpNo()
    {
        return $this->distributedToIrdEmpNo;
    }

    /**
     * Set distributedDate
     *
     * @param \DateTime $distributedDate
     *
     * @return Declarations
     */
    public function setDistributedDate($distributedDate)
    {
        $this->distributedDate = $distributedDate;

        return $this;
    }

    /**
     * Get distributedDate
     *
     * @return \DateTime
     */
    public function getDistributedDate()
    {
        return $this->distributedDate;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Declarations
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set receiptPrinted
     *
     * @param string $receiptPrinted
     *
     * @return Declarations
     */
    public function setReceiptPrinted($receiptPrinted)
    {
        $this->receiptPrinted = $receiptPrinted;

        return $this;
    }

    /**
     * Get receiptPrinted
     *
     * @return string
     */
    public function getReceiptPrinted()
    {
        return $this->receiptPrinted;
    }

    /**
     * Set docTpWeek
     *
     * @param integer $docTpWeek
     *
     * @return Declarations
     */
    public function setDocTpWeek($docTpWeek)
    {
        $this->docTpWeek = $docTpWeek;

        return $this;
    }

    /**
     * Get docTpWeek
     *
     * @return int
     */
    public function getDocTpWeek()
    {
        return $this->docTpWeek;
    }

    /**
     * Set deliveredByIrdEmpNo
     *
     * @param integer $deliveredByIrdEmpNo
     *
     * @return Declarations
     */
    public function setDeliveredByIrdEmpNo($deliveredByIrdEmpNo)
    {
        $this->deliveredByIrdEmpNo = $deliveredByIrdEmpNo;

        return $this;
    }

    /**
     * Get deliveredByIrdEmpNo
     *
     * @return int
     */
    public function getDeliveredByIrdEmpNo()
    {
        return $this->deliveredByIrdEmpNo;
    }

    /**
     * Set physicalObjectNo
     *
     * @param integer $physicalObjectNo
     *
     * @return Declarations
     */
    public function setPhysicalObjectNo($physicalObjectNo)
    {
        $this->physicalObjectNo = $physicalObjectNo;

        return $this;
    }

    /**
     * Get physicalObjectNo
     *
     * @return int
     */
    public function getPhysicalObjectNo()
    {
        return $this->physicalObjectNo;
    }

    /**
     * Set docSubject
     *
     * @param string $docSubject
     *
     * @return Declarations
     */
    public function setDocSubject($docSubject)
    {
        $this->docSubject = $docSubject;

        return $this;
    }

    /**
     * Get docSubject
     *
     * @return string
     */
    public function getDocSubject()
    {
        return $this->docSubject;
    }

    /**
     * Set lostByIrdEmpNo
     *
     * @param integer $lostByIrdEmpNo
     *
     * @return Declarations
     */
    public function setLostByIrdEmpNo($lostByIrdEmpNo)
    {
        $this->lostByIrdEmpNo = $lostByIrdEmpNo;

        return $this;
    }

    /**
     * Get lostByIrdEmpNo
     *
     * @return int
     */
    public function getLostByIrdEmpNo()
    {
        return $this->lostByIrdEmpNo;
    }

    /**
     * Set lostDate
     *
     * @param \DateTime $lostDate
     *
     * @return Declarations
     */
    public function setLostDate($lostDate)
    {
        $this->lostDate = $lostDate;

        return $this;
    }

    /**
     * Get lostDate
     *
     * @return \DateTime
     */
    public function getLostDate()
    {
        return $this->lostDate;
    }

    /**
     * Set taxPayerName
     *
     * @param string $taxPayerName
     *
     * @return Declarations
     */
    public function setTaxPayerName($taxPayerName)
    {
        $this->taxPayerName = $taxPayerName;

        return $this;
    }

    /**
     * Get taxPayerName
     *
     * @return string
     */
    public function getTaxPayerName()
    {
        return $this->taxPayerName;
    }

    /**
     * Set notifDate
     *
     * @param \DateTime $notifDate
     *
     * @return Declarations
     */
    public function setNotifDate($notifDate)
    {
        $this->notifDate = $notifDate;

        return $this;
    }

    /**
     * Get notifDate
     *
     * @return \DateTime
     */
    public function getNotifDate()
    {
        return $this->notifDate;
    }

    /**
     * Set titreNo
     *
     * @param integer $titreNo
     *
     * @return Declarations
     */
    public function setTitreNo($titreNo)
    {
        $this->titreNo = $titreNo;

        return $this;
    }

    /**
     * Get titreNo
     *
     * @return int
     */
    public function getTitreNo()
    {
        return $this->titreNo;
    }

    /**
     * Set titreOrigine
     *
     * @param string $titreOrigine
     *
     * @return Declarations
     */
    public function setTitreOrigine($titreOrigine)
    {
        $this->titreOrigine = $titreOrigine;

        return $this;
    }

    /**
     * Get titreOrigine
     *
     * @return string
     */
    public function getTitreOrigine()
    {
        return $this->titreOrigine;
    }

}

