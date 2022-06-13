<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourierSortant
 *
 * @ORM\Table(name="courier_sortant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\CourierSortantRepository")
 */
class CourierSortant
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
     * @ORM\ManyToOne(targetEntity="CategorieCourierSortant", inversedBy="couriers")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courierSortant")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gestionaire;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=true)
     */
    private $nif;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=true)
     */
    private $observation;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reponse", type="datetime", nullable = true)
     */
    private $dateReponse;

    public function __construct(){
        $this->date = new \DateTime();
    }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CourierSortant
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set categorie
     *
     * @param \DBundle\Entity\CategorieCourierSortant $categorie
     *
     * @return CourierSortant
     */
    public function setCategorie(\DBundle\Entity\CategorieCourierSortant $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \DBundle\Entity\CategorieCourierSortant
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return CourierSortant
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
     * Set observation
     *
     * @param string $observation
     *
     * @return CourierSortant
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set gestionaire
     *
     * @param \DBundle\Entity\User $gestionaire
     *
     * @return CourierSortant
     */
    public function setGestionaire(\DBundle\Entity\User $gestionaire)
    {
        $this->gestionaire = $gestionaire;

        return $this;
    }

    /**
     * Get gestionaire
     *
     * @return \DBundle\Entity\User
     */
    public function getGestionaire()
    {
        return $this->gestionaire;
    }

    /**
     * Set dateReponse
     *
     * @param \DateTime $dateReponse
     *
     * @return CourierSortant
     */
    public function setDateReponse($dateReponse)
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    /**
     * Get dateReponse
     *
     * @return \DateTime
     */
    public function getDateReponse()
    {
        return $this->dateReponse;
    }
}
