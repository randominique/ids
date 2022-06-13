<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @var int
     *
     * @ORM\Column(name="DOC_NO", type="integer")
     * @ORM\id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $docNo; 

    /**
     * @var int
     *
     * @ORM\Column(name="tax_type_no", type="integer")
     */
    private $taxTypeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="tax_period_no", type="integer")
     */
    private $taxPeriodNo;

    /**
     * @var int
     *
     * @ORM\Column(name="tax_payer_no", type="integer")
     */
    private $taxPayerNo;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_type_no", type="integer")
     */
    private $docTypeNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var int
     *
     * @ORM\Column(name="letter_no", type="integer")
     */
    private $letterNo;

    /**
     * @var int
     *
     * @ORM\Column(name="assess_no", type="integer")
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
     * @ORM\Column(name="printed_date", type="datetime")
     */
    private $printedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="doc_tp_start_date", type="datetime")
     */
    private $docTpStartDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="doc_tp_end_date", type="datetime")
     */
    private $docTpEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="doc_tp_due_date", type="datetime")
     */
    private $docTpDueDate;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_tp_month", type="integer")
     */
    private $docTpMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_tp_year", type="integer")
     */
    private $docTpYear;

    /**
     * @var string
     *
     * @ORM\Column(name="personal_text", type="string", length=2000, nullable=true)
     */
    private $personalText;

    /**
     * @var int
     *
     * @ORM\Column(name="ird_file_no", type="integer")
     */
    private $irdFileNo;

    /**
     * @var int
     *
     * @ORM\Column(name="install_rate_no", type="integer")
     */
    private $installRateNo;

    /**
     * @var int
     *
     * @ORM\Column(name="job_no", type="integer")
     */
    private $jobNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="state_change_date", type="datetime")
     */
    private $stateChangeDate;

    /**
     * @var string
     *
     * @ORM\Column(name="state_change_user", type="string")
     */
    private $stateChangeUser;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt", type="string", length=255)
     */
    private $receipt;

    /**
     * @var int
     *
     * @ORM\Column(name="estab_no", type="integer")
     */
    private $estabNo;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_state_no", type="integer")
     */
    private $docStateNo;

    /**
     * @var int
     *
     * @ORM\Column(name="form_no", type="integer")
     */
    private $formNo;

    /**
     * @var int
     *
     * @ORM\Column(name="version_no", type="integer", nullable=true)
     */
    private $versionNo;

    /**
     * @var int
     *
     * @ORM\Column(name="lic_base_no", type="integer", nullable=true)
     */
    private $licBaseNo;

    /**
     * @var int
     *
     * @ORM\Column(name="pay_agree_no", type="integer", nullable=true)
     */
    private $payAgreeNo;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_inst_month", type="integer", nullable=true)
     */
    private $docInstMonth;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_inst_year", type="integer", nullable=true)
     */
    private $docInstYear;

    /**
     * @var string
     *
     * @ORM\Column(name="entry_user", type="string", length=255)
     */
    private $entryUser;

    /**
     * @var int
     *
     * @ORM\Column(name="mv_regis_no", type="integer", nullable=true)
     */
    private $mvRegisNo;

    /**
     * @var int
     *
     * @ORM\Column(name="tax_centre_no", type="integer")
     */
    private $taxCentreNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="doc_tp_payment_date", type="datetime", nullable=true)
     */
    private $docTpPaymentDate;

    /**
     * @var int
     *
     * @ORM\Column(name="license_no", type="integer", nullable=true)
     */
    private $licenseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="ext_doc_no", type="string", length=255)
     */
    private $extDocNo;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=true)
     */
    private $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="batch_no", type="integer", nullable=true)
     */
    private $batchNo;

    /**
     * @var int
     *
     * @ORM\Column(name="tp_install_rate_no", type="integer", nullable=true)
     */
    private $tpInstallRateNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivered_date", type="datetime", nullable=true)
     */
    private $deliveredDate;

    /**
     * @var int
     *
     * @ORM\Column(name="distributed_to_ird_emp_no", type="integer", nullable=true)
     */
    private $distributedToIrdEmpNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="distributed_date", type="datetime", nullable=true)
     */
    private $distributedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=500, nullable=true)
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="receipt_printed", type="string", length=255, nullable=true)
     */
    private $receiptPrinted;

    /**
     * @var int
     *
     * @ORM\Column(name="doc_tp_week", type="integer", nullable=true)
     */
    private $docTpWeek;

    /**
     * @var int
     *
     * @ORM\Column(name="delivered_by_ird_emp_no", type="integer", nullable=true)
     */
    private $deliveredByIrdEmpNo;

    /**
     * @var int
     *
     * @ORM\Column(name="physical_object_no", type="integer", nullable=true)
     */
    private $physicalObjectNo;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_subject", type="string", length=255, nullable=true)
     */
    private $docSubject;

    /**
     * @var int
     *
     * @ORM\Column(name="lost_by_ird_emp_no", type="integer", nullable=true)
     */
    private $lostByIrdEmpNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lost_date", type="datetime", nullable=true)
     */
    private $lostDate;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_payer_name", type="string", length=255, nullable=true)
     */
    private $taxPayerName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notif_date", type="datetime", nullable=true)
     */
    private $notifDate;

    /**
     * @var int
     *
     * @ORM\Column(name="titre_no", type="integer", nullable=true)
     */
    private $titreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_origine", type="string", length=255, nullable=true)
     */
    private $titreOrigine;


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
     * @return Document
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
     * Set taxPeriodNo
     *
     * @param integer $taxPeriodNo
     *
     * @return Document
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
     * @return Document
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
     * Set docTypeNo
     *
     * @param integer $docTypeNo
     *
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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
     * @return Document
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

