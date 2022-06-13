<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Division
 *
 * @ORM\Table(name="DIVISION")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DivisionRepository")
 */
class Division
{
    /**
     * @var int
     *
     * @ORM\Column(name="DIVISION_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $divisionNo;

    /**
     * @var string
     *
     * @ORM\Column(name="DIVISION_DESC", type="string", length=255)
     */
    public $divisionDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="DIVISION_DESC_F", type="string", length=255)
     */
    private $divisionDescF;

    /**
     * @var string
     *
     * @ORM\Column(name="DIVISION_DESC_s", type="string", length=255)
     */
    private $divisionDescS;


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
     * Set divisionDesc
     *
     * @param string $divisionDesc
     *
     * @return Division
     */
    public function setDivisionDesc($divisionDesc)
    {
        $this->divisionDesc = $divisionDesc;

        return $this;
    }

    /**
     * Get divisionDesc
     *
     * @return string
     */
    public function getDivisionDesc()
    {
        return $this->divisionDesc;
    }

    /**
     * Set divisionDescF
     *
     * @param string $divisionDescF
     *
     * @return Division
     */
    public function setDivisionDescF($divisionDescF)
    {
        $this->divisionDescF = $divisionDescF;

        return $this;
    }

    /**
     * Get divisionDescF
     *
     * @return string
     */
    public function getDivisionDescF()
    {
        return $this->divisionDescF;
    }

    /**
     * Set divisionDescS
     *
     * @param string $divisionDescS
     *
     * @return Division
     */
    public function setDivisionDescS($divisionDescS)
    {
        $this->divisionDescS = $divisionDescS;

        return $this;
    }

    /**
     * Get divisionDescS
     *
     * @return string
     */
    public function getDivisionDescS()
    {
        return $this->divisionDescS;
    }
}

