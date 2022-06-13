<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_categories_multilingual
 *
 * @ORM\Table(name="sqvf_categories_multilingual")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_categories_multilingualRepository")
 */
class sqvf_categories_multilingual
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
     * @ORM\Column(name="id_category", type="integer", nullable=true)
     */
    private $idCategory;

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
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=765, nullable=true)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=765, nullable=true)
     */
    private $seoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_meta_description", type="text", nullable=true)
     */
    private $seoMetaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_meta_keyword", type="text", nullable=true)
     */
    private $seoMetaKeyword;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_permalink", type="string", length=765, nullable=true)
     */
    private $seoPermalink;


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
     * Set idCategory
     *
     * @param integer $idCategory
     *
     * @return sqvf_categories_multilingual
     */
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * Get idCategory
     *
     * @return int
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set idLanguage
     *
     * @param integer $idLanguage
     *
     * @return sqvf_categories_multilingual
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
     * @return sqvf_categories_multilingual
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

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     *
     * @return sqvf_categories_multilingual
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return sqvf_categories_multilingual
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return sqvf_categories_multilingual
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoMetaDescription
     *
     * @param string $seoMetaDescription
     *
     * @return sqvf_categories_multilingual
     */
    public function setSeoMetaDescription($seoMetaDescription)
    {
        $this->seoMetaDescription = $seoMetaDescription;

        return $this;
    }

    /**
     * Get seoMetaDescription
     *
     * @return string
     */
    public function getSeoMetaDescription()
    {
        return $this->seoMetaDescription;
    }

    /**
     * Set seoMetaKeyword
     *
     * @param string $seoMetaKeyword
     *
     * @return sqvf_categories_multilingual
     */
    public function setSeoMetaKeyword($seoMetaKeyword)
    {
        $this->seoMetaKeyword = $seoMetaKeyword;

        return $this;
    }

    /**
     * Get seoMetaKeyword
     *
     * @return string
     */
    public function getSeoMetaKeyword()
    {
        return $this->seoMetaKeyword;
    }

    /**
     * Set seoPermalink
     *
     * @param string $seoPermalink
     *
     * @return sqvf_categories_multilingual
     */
    public function setSeoPermalink($seoPermalink)
    {
        $this->seoPermalink = $seoPermalink;

        return $this;
    }

    /**
     * Get seoPermalink
     *
     * @return string
     */
    public function getSeoPermalink()
    {
        return $this->seoPermalink;
    }
}

