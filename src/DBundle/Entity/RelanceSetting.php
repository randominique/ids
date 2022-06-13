<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelanceSetting
 *
 * @ORM\Table(name="relance_setting")
 * @ORM\Entity(repositoryClass="DBundle\Repository\RelanceSettingRepository")
 */
class RelanceSetting
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
     * @ORM\OneToOne(targetEntity="DBundle\Entity\CategorieCourierSortant", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $courrierSortantObjetNo;


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
     * Set courrierSortantObjetNo
     *
     * @param \DBundle\Entity\CategorieCourierSortant $courrierSortantObjetNo
     *
     * @return RelanceSetting
     */
    public function setCourrierSortantObjetNo(\DBundle\Entity\CategorieCourierSortant $courrierSortantObjetNo = null)
    {
        $this->courrierSortantObjetNo = $courrierSortantObjetNo;

        return $this;
    }

    /**
     * Get courrierSortantObjetNo
     *
     * @return \DBundle\Entity\CategorieCourierSortant
     */
    public function getCourrierSortantObjetNo()
    {
        return $this->courrierSortantObjetNo;
    }
}
