<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocAmount
 *
 * @ORM\Table(name="DOC_AMOUNT")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DocAmountRepository")
 */
class DocAmount
{
    /**
     * @var int
     *
     * @ORM\Column(name="DOC_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $docNo;

    // /**
    //  * @var int
    //  *
    //  * @ORM\Column(name="DOC_NO", type="integer", unique=true)
    //  */
    // private $docNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_AMOUNT", type="integer", nullable=true)
     */
    private $taxAmount;

    /**
     * @var int
     *
     * @ORM\Column(name="INTEREST_AMT", type="integer", nullable=true)
     */
    private $iNTERESTAMT;

    /**
     * @var int
     *
     * @ORM\Column(name="PENALTY_AMT", type="integer", nullable=true)
     */
    private $pENALTYAMT;

    /**
     * @var int
     *
     * @ORM\Column(name="INSTALL_AMOUNT", type="integer", nullable=true)
     */
    private $iNSTALLAMOUNT;


    /**
     * Set docNo
     *
     * @param integer $docNo
     *
     * @return DocAmount
     */
    public function setdocNo($docNo)
    {
        $this->docNo = $docNo;

        return $this;
    }

    /**
     * Get docNo
     *
     * @return int
     */
    public function getdocNo()
    {
        return $this->docNo;
    }

    /**
     * Set taxAmount
     *
     * @param integer $taxAmount
     *
     * @return DocAmount
     */
    public function settaxAmount($taxAmount)
    {
        $this->taxAmount = $taxAmount;

        return $this;
    }

    /**
     * Get taxAmount
     *
     * @return int
     */
    public function gettaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * Set iNTERESTAMT
     *
     * @param integer $iNTERESTAMT
     *
     * @return DocAmount
     */
    public function setINTERESTAMT($iNTERESTAMT)
    {
        $this->iNTERESTAMT = $iNTERESTAMT;

        return $this;
    }

    /**
     * Get iNTERESTAMT
     *
     * @return int
     */
    public function getINTERESTAMT()
    {
        return $this->iNTERESTAMT;
    }

    /**
     * Set pENALTYAMT
     *
     * @param integer $pENALTYAMT
     *
     * @return DocAmount
     */
    public function setPENALTYAMT($pENALTYAMT)
    {
        $this->pENALTYAMT = $pENALTYAMT;

        return $this;
    }

    /**
     * Get pENALTYAMT
     *
     * @return int
     */
    public function getPENALTYAMT()
    {
        return $this->pENALTYAMT;
    }

    /**
     * Set iNSTALLAMOUNT
     *
     * @param integer $iNSTALLAMOUNT
     *
     * @return DocAmount
     */
    public function setINSTALLAMOUNT($iNSTALLAMOUNT)
    {
        $this->iNSTALLAMOUNT = $iNSTALLAMOUNT;

        return $this;
    }

    /**
     * Get iNSTALLAMOUNT
     *
     * @return int
     */
    public function getINSTALLAMOUNT()
    {
        return $this->iNSTALLAMOUNT;
    }
}

