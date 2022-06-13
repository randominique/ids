<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nif
 *
 * @ORM\Table(name="sqvf_nif")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_nifRepository")
 */
class sqvf_nif
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
     * @ORM\Column(name="numero", type="string", length=50, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="raison_sociale", type="string", length=50, nullable=true)
     */
    private $raisonSociale;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="activite", type="text", nullable=true)
     */
    private $activite;

    /**
     * @var string
     *
     * @ORM\Column(name="centre_gestionnaire", type="string", length=50, nullable=true)
     */
    private $centreGestionnaire;

    /**
     * @var string
     *
     * @ORM\Column(name="code_bureau", type="string", length=50)
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Nif
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Nif
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Nif
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set activite
     *
     * @param string $activite
     *
     * @return Nif
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return string
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set centreGestionnaire
     *
     * @param string $centreGestionnaire
     *
     * @return Nif
     */
    public function setCentreGestionnaire($centreGestionnaire)
    {
        $this->centreGestionnaire = $centreGestionnaire;

        return $this;
    }

    /**
     * Get centreGestionnaire
     *
     * @return string
     */
    public function getCentreGestionnaire()
    {
        return $this->centreGestionnaire;
    }

    /**
     * Set codeBureau
     *
     * @param string $codeBureau
     *
     * @return Nif
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

