<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_categories_groups_multilingual
 *
 * @ORM\Table(name="sqvf_categories_groups_multilingual")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_categories_groups_multilingualRepository")
 */
class sqvf_categories_groups_multilingual
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
     * @ORM\Column(name="id_category_group", type="integer", nullable=true)
     */
    private $idCategoryGroup;

    /**
     * @var int
     *
     * @ORM\Column(name="id_language", type="integer", nullable=true)
     */
    private $idLanguage;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=765, nullable=true)
     */
    private $title;


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
     * Set idCategoryGroup
     *
     * @param integer $idCategoryGroup
     *
     * @return sqvf_categories_groups_multilingual
     */
    public function setIdCategoryGroup($idCategoryGroup)
    {
        $this->idCategoryGroup = $idCategoryGroup;

        return $this;
    }

    /**
     * Get idCategoryGroup
     *
     * @return int
     */
    public function getIdCategoryGroup()
    {
        return $this->idCategoryGroup;
    }

    /**
     * Set idLanguage
     *
     * @param integer $idLanguage
     *
     * @return sqvf_categories_groups_multilingual
     */
    public function setIdLanguage($idLanguage)
    {
        $this->idLanguage = $idLanguage;

        return $this;
    }

    /**
     * Get idLanguage
     *
     * @return int
     */
    public function getIdLanguage()
    {
        return $this->idLanguage;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return sqvf_categories_groups_multilingual
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}

