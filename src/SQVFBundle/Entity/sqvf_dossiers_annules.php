<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_annules
 *
 * @ORM\Table(name="sqvf_dossiers_annules")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_annulesRepository")
 */
class sqvf_dossiers_annules
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
     * @ORM\Column(name="new_id_dossier", type="integer", nullable=true)
     */
    private $newIdDossier;

    /**
     * @var int
     *
     * @ORM\Column(name="id_etape", type="integer", nullable=true)
     */
    private $idEtape;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;


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
     * @return sqvf_dossiers_annules
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
     * Set newIdDossier
     *
     * @param integer $newIdDossier
     *
     * @return sqvf_dossiers_annules
     */
    public function setNewIdDossier($newIdDossier)
    {
        $this->newIdDossier = $newIdDossier;

        return $this;
    }

    /**
     * Get newIdDossier
     *
     * @return int
     */
    public function getNewIdDossier()
    {
        return $this->newIdDossier;
    }

    /**
     * Set idEtape
     *
     * @param integer $idEtape
     *
     * @return sqvf_dossiers_annules
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
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return sqvf_dossiers_annules
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
}

