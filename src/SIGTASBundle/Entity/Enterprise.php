<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enterprise
 *
 * @ORM\Table(name="enterprise")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\EnterpriseRepository")
 */
class Enterprise
{
    /**
     * @var int
     *
     * @ORM\Column(name="ENTERPRISE_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $enterpriseNo;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     */
    private $taxPayerNo;

    /**
     * @var int
     *
     * @ORM\Column(name="ENT_TYPE_NO", type="integer")
     */
    private $entTypeNo;

    /**
     * @ORM\Column(name="SECTOR_ACT_NO", type="integer")
     */
    private $sectorActNo;

    /**
     * @ORM\Column(name="START_DATE", type="datetime")
     */
    private $startDate;
    
    /**
     * @ORM\Column(name="ENTRY_DATE", type="datetime")
     */
    private $entryDate;


    /**
     * @ORM\Column(name="FISC_YR_START", type="integer")
     */
    private $fisc_yr_start;


    /**
     * @ORM\Column(name="FISC_YR_END", type="integer")
     */
    private $fisc_yr_end;

    /**
     * @ORM\Column(name="TAX_CONTACT_NAME", type="string")
     */
    private $taxContactName;

    /**
     * @ORM\Column(name="REGIST_NAME", type="string")
     */
    private $registName;


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
     * @param integer $taxPayerNo
     *
     * @return Enterprise
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
     * Set entTypeNo
     *
     * @param integer $entTypeNo
     *
     * @return Enterprise
     */
    public function setEntTypeNo($entTypeNo)
    {
        $this->entTypeNo = $entTypeNo;

        return $this;
    }

    /**
     * Get entTypeNo
     *
     * @return int
     */
    public function getEntTypeNo()
    {
        return $this->entTypeNo;
    }

    /**
     * Set sectorActNo
     *
     * @param integer $sectorActNo
     *
     * @return Enterprise
     */
    public function setSectorActNo($sectorActNo)
    {
        $this->sectorActNo = $sectorActNo;

        return $this;
    }

    /**
     * Get sectorActNo
     *
     * @return integer
     */
    public function getSectorActNo()
    {
        return $this->sectorActNo;
    }

    /**
     * Set enterpriseNo
     *
     * @param integer $enterpriseNo
     *
     * @return Enterprise
     */
    public function setEnterpriseNo($enterpriseNo)
    {
        $this->enterpriseNo = $enterpriseNo;

        return $this;
    }

    /**
     * Get enterpriseNo
     *
     * @return integer
     */
    public function getEnterpriseNo()
    {
        return $this->enterpriseNo;
    }

         /**
     * Set fisc_yr_start
     *
     * @param date $fisc_yr_start
     *
     * @return Clients
     */
    public function setFiscYrStart($fisc_yr_start)
    {
        $this->fisc_yr_start = $fisc_yr_start;

        return $this;
    }

    /**
     * Get FiscYrStart
     *
     * @return date
     */
    public function getFiscYrStart()
    {
        return $this->fisc_yr_start;
    }

    /**
     * Set fisc_yr_end
     *
     * @param date $fisc_yr_end
     *
     * @return Clients
     */
    public function setFiscYrEnd($fisc_yr_end)
    {
        $this->fisc_yr_end = $fisc_yr_end;

        return $this;
    }

    /**
     * Get FiscYrEnd
     *
     * @return date
     */
    public function getFiscYrEnd()
    {
        return $this->fisc_yr_end;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Enterprise
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return Enterprise
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
     * Set registName
     *
     * @param string $registName
     *
     * @return Enterprise
     */
    public function setRegistName($registName)
    {
        $this->registName = $registName;

        return $this;
    }

    /**
     * Get registName
     *
     * @return string
     */
    public function getRegistName()
    {
        return $this->registName;
    }

    /**
     * Set taxContactName
     *
     * @param string $taxContactName
     *
     * @return Enterprise
     */
    public function setTaxContactName($taxContactName)
    {
        $this->taxContactName = $taxContactName;

        return $this;
    }

    /**
     * Get taxContactName
     *
     * @return string
     */
    public function getTaxContactName()
    {
        return $this->taxContactName;
    }

}
