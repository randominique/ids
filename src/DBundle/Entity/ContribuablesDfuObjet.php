<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContribuablesDfuObjet
 *
 * @ORM\Table(name="ContribuablesDfuObjet")
 * @ORM\Entity(repositoryClass="DBundle\Repository\ContribuablesDfuObjetRepository")
 */
class ContribuablesDfuObjet
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
     * @ORM\Column(name="objet_id", type="integer")
     */
    private $objetId;

    /**
     * @var string
     *
     * @ORM\Column(name="objet_intitule", type="string", length=255)
     */
    private $objetIntitule;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text")
     */
    private $observations;


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
     * Set objetId
     *
     * @param integer $objetId
     *
     * @return ContribuablesDfuObjet
     */
    public function setObjetId($objetId)
    {
        $this->objetId = $objetId;

        return $this;
    }

    /**
     * Get objetId
     *
     * @return int
     */
    public function getObjetId()
    {
        return $this->objetId;
    }

    /**
     * Set objetIntitule
     *
     * @param string $objetIntitule
     *
     * @return ContribuablesDfuObjet
     */
    public function setObjetIntitule($objetIntitule)
    {
        $this->objetIntitule = $objetIntitule;

        return $this;
    }

    /**
     * Get objetIntitule
     *
     * @return string
     */
    public function getObjetIntitule()
    {
        return $this->objetIntitule;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return ContribuablesDfuObjet
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }
}