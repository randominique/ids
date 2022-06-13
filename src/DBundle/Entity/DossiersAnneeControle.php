<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DossiersAnneeControle
 *
 * @ORM\Table(name="dossiers_annee_controle")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DossiersAnneeControleRepository")
 */
class DossiersAnneeControle
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
     * @ORM\Column(name="id_type_impot", type="integer", nullable=true)
     */
    private $idTypeImpot;

    /**
     * @var string
     *
     * @ORM\Column(name="typeImpot", type="string", length=255, nullable=true)
     */
    private $typeImpot;

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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return DossiersAnneeControle
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
     * @return DossiersAnneeControle
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
     * @return DossiersAnneeControle
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

    /**
     * Set typeImpot
     *
     * @param string $typeImpot
     *
     * @return DossiersAnneeControle
     */
    public function setTypeImpot($typeImpot)
    {
        $this->typeImpot = $typeImpot;

        return $this;
    }

    /**
     * Get typeImpot
     *
     * @return string
     */
    public function getTypeImpot()
    {
        return $this->typeImpot;
    }
}
