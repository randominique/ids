<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documents
 *
 * @ORM\Table(name="sqvf_documents")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_documentsRepository")
 */
class sqvf_documents
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
     * @ORM\Column(name="id_etape", type="integer", nullable=true)
     */
    private $idEtape;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type", type="integer", nullable=true)
     */
    private $idType;


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
     * Set idEtape
     *
     * @param integer $idEtape
     *
     * @return Documents
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
     * Set idType
     *
     * @param integer $idType
     *
     * @return Documents
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return int
     */
    public function getIdType()
    {
        return $this->idType;
    }
}

