<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarteStat
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="SIGTASAD.CARTE_STAT")
 * 
 */
class CarteStat
{
    private function __construct() {}

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="FISCAL_NO", type="string", length=255)
     */
    public $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="ANARANA", type="string", length=255)
     */
    public $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="LIEU", type="string", length=255)
     */
    public $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="ADDRESS", type="string", length=255)
     */
    public $adress;
    
    /**
     * @var int
     *
     * @ORM\Column(name="CARTE_NUM", type="integer")
     */
    public $carteNum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CARTE_DATE",  type="oracledate")
     */
    public $carteDate;

    /**
     * @var string
     *
     * @ORM\Column(name="CARTE_TYPE_NO", type="string", length=255)
     */
    public $carteTypeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_VEHICULE", type="string", length=255)
     */
    public $numVehicule;
    
    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer")
     */
    public $annee;
    
    /**
     * @var int
     *
     * @ORM\Column(name="CARTE_MOTIF_NO", type="integer")
     */
    public $carteMotifNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=255)
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(name="CARTE_DESC", type="string", length=255)
     */
    public $carteDesc;
}