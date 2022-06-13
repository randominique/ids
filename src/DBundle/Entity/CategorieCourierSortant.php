<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CategorieCourierSortant
 *
 * @ORM\Table(name="categorie_courier_sortant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\CategorieCourierSortantRepository")
 */
class CategorieCourierSortant
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * 
     * @ORM\Column(name="nb_courrier_01", type="integer")
     */
    private $nbcourrier01;

    /**
     * 
     * @ORM\Column(name="nb_courrier_02", type="integer")
     */
    private $nbcourrier02;

    /**
     * 
     * @ORM\Column(name="nb_courrier_03", type="integer")
     */
    private $nbcourrier03;

    /**
     * 
     * @ORM\Column(name="nb_courrier_04", type="integer")
     */
    private $nbcourrier04;

    /**
     * 
     * @ORM\Column(name="nb_courrier_05", type="integer")
     */
    private $nbcourrier05;

    /**
     * 
     * @ORM\Column(name="nb_courrier_06", type="integer")
     */
    private $nbcourrier06;

    /**
     * 
     * @ORM\Column(name="nb_courrier_07", type="integer")
     */
    private $nbcourrier07;

    /**
     * 
     * @ORM\Column(name="nb_courrier_08", type="integer")
     */
    private $nbcourrier08;

    /**
     * 
     * @ORM\Column(name="nb_courrier_09", type="integer")
     */
    private $nbcourrier09;

    /**
     * 
     * @ORM\Column(name="nb_courrier_10", type="integer")
     */
    private $nbcourrier10;

    /**
     * 
     * @ORM\Column(name="nb_courrier_11", type="integer")
     */
    private $nbcourrier11;

    /**
     * 
     * @ORM\Column(name="nb_courrier_12", type="integer")
     */
    private $nbcourrier12;

    /**
     * @ORM\OneToMany(targetEntity="CourierSortant", mappedBy="categorie")
     */
    private $couriers;
    
    private $nbr;

    public function __construct()
    {
        $this->couriers = new ArrayCollection();
    }

    function __toString(){
        return $this->nom;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return CategorieCourierSortant
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
     * Add courier
     *
     * @param \DBundle\Entity\CourierSortant $courier
     *
     * @return CategorieCourierSortant
     */
    public function addCourier(\DBundle\Entity\CourierSortant $courier)
    {
        $this->couriers[] = $courier;

        return $this;
    }

    /**
     * Remove courier
     *
     * @param \DBundle\Entity\CourierSortant $courier
     */
    public function removeCourier(\DBundle\Entity\CourierSortant $courier)
    {
        $this->couriers->removeElement($courier);
    }

    /**
     * Get couriers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouriers()
    {
        return $this->couriers;
    }
    
    /**
     * Set nbr
     *
     * @param string $nbr
     *
     * @return CategorieCourierEntrant
     */
    public function setNbr($nbr)
    {
        $this->nbr = $nbr;

        return $this;
    }

    /**
     * Get nbr
     *
     * @return string
     */
    public function getNbr()
    {
        return $this->nbr;
    }

    /**
     * Set nbcourrier01
     *
     * @param integer $nbcourrier01
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier01($nbcourrier01)
    {
        $this->nbcourrier01 = $nbcourrier01;

        return $this;
    }

    /**
     * Get nbcourrier01
     *
     * @return integer
     */
    public function getNbcourrier01()
    {
        return $this->nbcourrier01;
    }

    /**
     * Set nbcourrier02
     *
     * @param integer $nbcourrier02
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier02($nbcourrier02)
    {
        $this->nbcourrier02 = $nbcourrier02;

        return $this;
    }

    /**
     * Get nbcourrier02
     *
     * @return integer
     */
    public function getNbcourrier02()
    {
        return $this->nbcourrier02;
    }

    /**
     * Set nbcourrier03
     *
     * @param integer $nbcourrier03
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier03($nbcourrier03)
    {
        $this->nbcourrier03 = $nbcourrier03;

        return $this;
    }

    /**
     * Get nbcourrier03
     *
     * @return integer
     */
    public function getNbcourrier03()
    {
        return $this->nbcourrier03;
    }

    /**
     * Set nbcourrier04
     *
     * @param integer $nbcourrier04
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier04($nbcourrier04)
    {
        $this->nbcourrier04 = $nbcourrier04;

        return $this;
    }

    /**
     * Get nbcourrier04
     *
     * @return integer
     */
    public function getNbcourrier04()
    {
        return $this->nbcourrier04;
    }

    /**
     * Set nbcourrier05
     *
     * @param integer $nbcourrier05
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier05($nbcourrier05)
    {
        $this->nbcourrier05 = $nbcourrier05;

        return $this;
    }

    /**
     * Get nbcourrier05
     *
     * @return integer
     */
    public function getNbcourrier05()
    {
        return $this->nbcourrier05;
    }

    /**
     * Set nbcourrier06
     *
     * @param integer $nbcourrier06
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier06($nbcourrier06)
    {
        $this->nbcourrier06 = $nbcourrier06;

        return $this;
    }

    /**
     * Get nbcourrier06
     *
     * @return integer
     */
    public function getNbcourrier06()
    {
        return $this->nbcourrier06;
    }

    /**
     * Set nbcourrier07
     *
     * @param integer $nbcourrier07
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier07($nbcourrier07)
    {
        $this->nbcourrier07 = $nbcourrier07;

        return $this;
    }

    /**
     * Get nbcourrier07
     *
     * @return integer
     */
    public function getNbcourrier07()
    {
        return $this->nbcourrier07;
    }

    /**
     * Set nbcourrier08
     *
     * @param integer $nbcourrier08
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier08($nbcourrier08)
    {
        $this->nbcourrier08 = $nbcourrier08;

        return $this;
    }

    /**
     * Get nbcourrier08
     *
     * @return integer
     */
    public function getNbcourrier08()
    {
        return $this->nbcourrier08;
    }

    /**
     * Set nbcourrier09
     *
     * @param integer $nbcourrier09
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier09($nbcourrier09)
    {
        $this->nbcourrier09 = $nbcourrier09;

        return $this;
    }

    /**
     * Get nbcourrier09
     *
     * @return integer
     */
    public function getNbcourrier09()
    {
        return $this->nbcourrier09;
    }

    /**
     * Set nbcourrier10
     *
     * @param integer $nbcourrier10
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier10($nbcourrier10)
    {
        $this->nbcourrier10 = $nbcourrier10;

        return $this;
    }

    /**
     * Get nbcourrier10
     *
     * @return integer
     */
    public function getNbcourrier10()
    {
        return $this->nbcourrier10;
    }

    /**
     * Set nbcourrier11
     *
     * @param integer $nbcourrier11
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier11($nbcourrier11)
    {
        $this->nbcourrier11 = $nbcourrier11;

        return $this;
    }

    /**
     * Get nbcourrier11
     *
     * @return integer
     */
    public function getNbcourrier11()
    {
        return $this->nbcourrier11;
    }

    /**
     * Set nbcourrier12
     *
     * @param integer $nbcourrier12
     *
     * @return CategorieCourierEntrant
     */
    public function setNbcourrier12($nbcourrier12)
    {
        $this->nbcourrier12 = $nbcourrier12;

        return $this;
    }

    /**
     * Get nbcourrier12
     *
     * @return integer
     */
    public function getNbcourrier12()
    {
        return $this->nbcourrier12;
    }

}