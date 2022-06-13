<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_etapes
 *
 * @ORM\Table(name="sqvf_etapes")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_etapesRepository")
 */
class sqvf_etapes
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
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=50, nullable=true)
     */
    private $permalink;

    /**
     * @var int
     *
     * @ORM\Column(name="order_item", type="integer", nullable=true)
     */
    private $orderItem;


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
     * @return sqvf_etapes
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
     * Set permalink
     *
     * @param string $permalink
     *
     * @return sqvf_etapes
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set orderItem
     *
     * @param integer $orderItem
     *
     * @return sqvf_etapes
     */
    public function setOrderItem($orderItem)
    {
        $this->orderItem = $orderItem;

        return $this;
    }

    /**
     * Get orderItem
     *
     * @return int
     */
    public function getOrderItem()
    {
        return $this->orderItem;
    }
}

