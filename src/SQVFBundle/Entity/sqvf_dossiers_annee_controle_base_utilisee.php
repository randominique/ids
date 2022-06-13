<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_annee_controle_base_utilisee
 *
 * @ORM\Table(name="sqvf_dossiers_annee_controle_base_utilisee")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_annee_controle_base_utiliseeRepository")
 */
class sqvf_dossiers_annee_controle_base_utilisee
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
     * @var int
     *
     * @ORM\Column(name="id_dossier_annee_controle", type="integer", nullable=true)
     */
    private $idDossierAnneeControle;

    /**
     * @var string
     *
     * @ORM\Column(name="base", type="text", nullable=true)
     */
    private $base;


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
     * @return sqvf_dossiers_annee_controle_base_utilisee
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
     * @return sqvf_dossiers_annee_controle_base_utilisee
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
     * Set base
     *
     * @param string $base
     *
     * @return sqvf_dossiers_annee_controle_base_utilisee
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }
}

