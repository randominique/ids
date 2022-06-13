<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FiscalRegime
 *
 * @ORM\Table(name="FISCAL_REGIME")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\FiscalRegimeRepository")
 */
class FiscalRegime
{
    /**
     * @var int
     *
     * @ORM\Column(name="FISCAL_REGIME_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $fiscalRegimeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_REGIME_DESC", type="string", length=30)
     */
    private $fiscalRegimeDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_REGIME_DESC_F", type="string", length=30)
     */
    private $fiscalRegimeDescF;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_REGIME_DESC_S", type="string", length=30)
     */
    private $fiscalRegimeDescS;


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
     * Set fiscalRegimeNo
     *
     * @param integer $fiscalRegimeNo
     *
     * @return FiscalRegime
     */
    public function setFiscalRegimeNo($fiscalRegimeNo)
    {
        $this->fiscalRegimeNo = $fiscalRegimeNo;

        return $this;
    }

    /**
     * Get fiscalRegimeNo
     *
     * @return int
     */
    public function getFiscalRegimeNo()
    {
        return $this->fiscalRegimeNo;
    }

    /**
     * Set fiscalRegimeDesc
     *
     * @param string $fiscalRegimeDesc
     *
     * @return FiscalRegime
     */
    public function setFiscalRegimeDesc($fiscalRegimeDesc)
    {
        $this->fiscalRegimeDesc = $fiscalRegimeDesc;

        return $this;
    }

    /**
     * Get fiscalRegimeDesc
     *
     * @return string
     */
    public function getFiscalRegimeDesc()
    {
        return $this->fiscalRegimeDesc;
    }

    /**
     * Set fiscalRegimeDescF
     *
     * @param string $fiscalRegimeDescF
     *
     * @return FiscalRegime
     */
    public function setFiscalRegimeDescF($fiscalRegimeDescF)
    {
        $this->fiscalRegimeDescF = $fiscalRegimeDescF;

        return $this;
    }

    /**
     * Get fiscalRegimeDescF
     *
     * @return string
     */
    public function getFiscalRegimeDescF()
    {
        return $this->fiscalRegimeDescF;
    }

    /**
     * Set fiscalRegimeDescS
     *
     * @param string $fiscalRegimeDescS
     *
     * @return FiscalRegime
     */
    public function setFiscalRegimeDescS($fiscalRegimeDescS)
    {
        $this->fiscalRegimeDescS = $fiscalRegimeDescS;

        return $this;
    }

    /**
     * Get fiscalRegimeDescS
     *
     * @return string
     */
    public function getFiscalRegimeDescS()
    {
        return $this->fiscalRegimeDescS;
    }
}

