<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dge
 *
 * @ORM\Table(name="dge")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DgeRepository")
 */
class Dge
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
     * @ORM\OneToOne(targetEntity="DBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;


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
     * Set user
     *
     * @param \DBundle\Entity\User $user
     *
     * @return Dge
     */
    public function setUser(\DBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
