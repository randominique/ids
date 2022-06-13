<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_centre_fiscal
 *
 * @ORM\Table(name="sqvf_centre_fiscal")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_centre_fiscalRepository")
 */
class sqvf_centre_fiscal
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=200, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="bureau", type="string", length=200, nullable=true)
     */
    private $bureau;

    /**
     * @var string
     *
     * @ORM\Column(name="code_bureau", type="string", length=50, nullable=true)
     */
    private $codeBureau;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return sqvf_centre_fiscal
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set bureau
     *
     * @param string $bureau
     *
     * @return sqvf_centre_fiscal
     */
    public function setBureau($bureau)
    {
        $this->bureau = $bureau;

        return $this;
    }

    /**
     * Get bureau
     *
     * @return string
     */
    public function getBureau()
    {
        return $this->bureau;
    }

    /**
     * Set codeBureau
     *
     * @param string $codeBureau
     *
     * @return sqvf_centre_fiscal
     */
    public function setCodeBureau($codeBureau)
    {
        $this->codeBureau = $codeBureau;

        return $this;
    }

    /**
     * Get codeBureau
     *
     * @return string
     */
    public function getCodeBureau()
    {
        return $this->codeBureau;
    }
}

