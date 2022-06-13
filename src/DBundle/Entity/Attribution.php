<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribution
 *
 * @ORM\Table(name="attribution")
 * @ORM\Entity(repositoryClass="DBundle\Repository\AttributionRepository")
 */
class Attribution
{
    /**
     * @var int
     *
     * @ORM\Column(name="ATTRIBUTION_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ATTRIBUTION_DESC", type="string", length=255)
     */
    private $attributionDesc;


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
     * Set attributionDesc
     *
     * @param string $attributionDesc
     *
     * @return Attribution
     */
    public function setAttributionDesc($attributionDesc)
    {
        $this->attributionDesc = $attributionDesc;

        return $this;
    }

    /**
     * Get attributionDesc
     *
     * @return string
     */
    public function getAttributionDesc()
    {
        return $this->attributionDesc;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entrant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entrant
     *
     * @param \DBundle\Entity\Attribution $entrant
     *
     * @return Attribution
     */
    public function addEntrant(\DBundle\Entity\Attribution $entrant)
    {
        $this->entrant[] = $entrant;

        return $this;
    }

    /**
     * Remove entrant
     *
     * @param \DBundle\Entity\Attribution $entrant
     */
    public function removeEntrant(\DBundle\Entity\Attribution $entrant)
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
