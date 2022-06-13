<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DossiersAnneeControleMontant
 *
 * @ORM\Table(name="dossiers_annee_controle_montant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DossiersAnneeControleMontantRepository")
 */
class DossiersAnneeControleMontant
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
     * @ORM\Column(name="id_dossier", type="integer", nullable=true)
     */
    private $idDossier;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=50, nullable=true)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="raison_sociale", type="string", length=50, nullable=true)
     */
    private $raisonSociale;

    /**
     * @var int
     *
     * @ORM\Column(name="id_dossier_annee_controle", type="integer", nullable=true)
     */
    private $idDossierAnneeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="id_etape", type="integer", nullable=true)
     */
    private $idEtape;

    /**
     * @var string
     *
     * @ORM\Column(name="etape", type="string", length=50, nullable=true)
     */
    private $etape;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_principal", type="integer", nullable=true)
     */
    private $montantPrincipal;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_amende", type="integer", nullable=true)
     */
    private $montantAmende;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_total", type="integer", nullable=true)
     */
    private $montantTotal;


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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return DossiersAnneeControleMontant
     */
    public function setIdDossier($idDossier)
    {
        $this->idDossier = $idDossier;

        return $this;
    }

    /**
     * Get idDossier
     *
     * @return int
     */
    public function getIdDossier()
    {
        return $this->idDossier;
    }

    /**
     * Set idDossierAnneeControle
     *
     * @param integer $idDossierAnneeControle
     *
     * @return DossiersAnneeControleMontant
     */
    public function setIdDossierAnneeControle($idDossierAnneeControle)
    {
        $this->idDossierAnneeControle = $idDossierAnneeControle;

        return $this;
    }

    /**
     * Get idDossierAnneeControle
     *
     * @return int
     */
    public function getIdDossierAnneeControle()
    {
        return $this->idDossierAnneeControle;
    }

    /**
     * Set idEtape
     *
     * @param integer $idEtape
     *
     * @return DossiersAnneeControleMontant
     */
    public function setIdEtape($idEtape)
    {
        $this->idEtape = $idEtape;

        return $this;
    }

    /**
     * Get idEtape
     *
     * @return int
     */
    public function getIdEtape()
    {
        return $this->idEtape;
    }

    /**
     * Set montantPrincipal
     *
     * @param integer $montantPrincipal
     *
     * @return DossiersAnneeControleMontant
     */
    public function setMontantPrincipal($montantPrincipal)
    {
        $this->montantPrincipal = $montantPrincipal;

        return $this;
    }

    /**
     * Get montantPrincipal
     *
     * @return int
     */
    public function getMontantPrincipal()
    {
        return $this->montantPrincipal;
    }

    /**
     * Set montantAmende
     *
     * @param integer $montantAmende
     *
     * @return DossiersAnneeControleMontant
     */
    public function setMontantAmende($montantAmende)
    {
        $this->montantAmende = $montantAmende;

        return $this;
    }

    /**
     * Get montantAmende
     *
     * @return int
     */
    public function getMontantAmende()
    {
        return $this->montantAmende;
    }

    /**
     * Set montantTotal
     *
     * @param integer $montantTotal
     *
     * @return DossiersAnneeControleMontant
     */
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get montantTotal
     *
     * @return int
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return DossiersAnneeControleMontant
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
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return DossiersAnneeControleMontant
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
     * Set etape
     *
     * @param string $etape
     *
     * @return DossiersAnneeControleMontant
     */
    public function setEtape($etape)
    {
        $this->etape = $etape;

        return $this;
    }

    /**
     * Get etape
     *
     * @return string
     */
    public function getEtape()
    {
        return $this->etape;
    }
}
