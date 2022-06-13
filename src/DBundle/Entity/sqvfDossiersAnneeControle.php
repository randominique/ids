<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvfDossiersAnneeControle
 *
 * @ORM\Table(name="sqvf_dossiers_annee_controle")
 * @ORM\Entity(repositoryClass="DBundle\Repository\sqvfDossiersAnneeControleRepository")
 */
class sqvfDossiersAnneeControle
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
     * @ORM\Column(name="id_dossier_annee_controle", type="integer", nullable=true)
     */
    private $idDossierAnneeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="id_dossier", type="integer", nullable=true)
     */
    private $idDossier;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type_impot", type="integer", nullable=true)
     */
    private $idTypeImpot;

    /**
     * @var int
     *
     * @ORM\Column(name="annee_controle", type="integer", nullable=true)
     */
    private $anneeControle;


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
     * Set idDossierAnneeControle
     *
     * @param integer $idDossierAnneeControle
     *
     * @return sqvfDossiersAnneeControle
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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return sqvfDossiersAnneeControle
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
     * Set idTypeImpot
     *
     * @param integer $idTypeImpot
     *
     * @return sqvfDossiersAnneeControle
     */
    public function setIdTypeImpot($idTypeImpot)
    {
        $this->idTypeImpot = $idTypeImpot;

        return $this;
    }

    /**
     * Get idTypeImpot
     *
     * @return int
     */
    public function getIdTypeImpot()
    {
        return $this->idTypeImpot;
    }

    /**
     * Set anneeControle
     *
     * @param integer $anneeControle
     *
     * @return sqvfDossiersAnneeControle
     */
    public function setAnneeControle($anneeControle)
    {
        $this->anneeControle = $anneeControle;

        return $this;
    }

    /**
     * Get anneeControle
     *
     * @return int
     */
    public function getAnneeControle()
    {
        return $this->anneeControle;
    }
}

