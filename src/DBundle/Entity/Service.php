<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="DBundle\Repository\ServiceRepository")
 */
class Service
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
     * @var string
     *
     * @ORM\OneToOne(targetEntity="DBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $chef;

    /**
     * @ORM\OneToMany(targetEntity="DBundle\Entity\User", mappedBy="service")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=Entrant::class, inversedBy="Services")
     */
    private $entrant;

    public function __toString(){
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
     * @return Service
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
     * Set chef
     *
     * @param string $chef
     *
     * @return Service
     */
    public function setChef($chef)
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * Get chef
     *
     * @return string
     */
    public function getChef()
    {
        return $this->chef;
    }
    
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entrant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \DBundle\Entity\User $user
     *
     * @return Service
     */
    public function addUser(\DBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \DBundle\Entity\User $user
     */
    public function removeUser(\DBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add entrant
     *
     * @param \DBundle\Entity\Entrant $entrant
     *
     * @return Service
     */
    public function addEntrant(\DBundle\Entity\Entrant $entrant)
    {
        $this->entrant[] = $entrant;

        return $this;
    }

    /**
     * Remove entrant
     *
     * @param \DBundle\Entity\Entrant $entrant
     */
    public function removeEntrant(\DBundle\Entity\Entrant $entrant)
    {
        $this->entrant->removeElement($entrant);
    }

    /**
     * Get entrant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntrant()
    {
        return $this->entrant;
    }
}
