<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_categories
 *
 * @ORM\Table(name="sqvf_categories")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_categoriesRepository")
 */
class sqvf_categories
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
     * @ORM\Column(name="uniqid", type="string", length=150, nullable=true)
     */
    private $uniqid;

    /**
     * @var int
     *
     * @ORM\Column(name="id_category_group", type="integer", nullable=true)
     */
    private $idCategoryGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=50, nullable=true)
     */
    private $icon;

    /**
     * @var int
     *
     * @ORM\Column(name="order_item", type="integer", nullable=true)
     */
    private $orderItem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $updateTime;


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
     * Set uniqid
     *
     * @param string $uniqid
     *
     * @return sqvf_categories
     */
    public function setUniqid($uniqid)
    {
        $this->uniqid = $uniqid;

        return $this;
    }

    /**
     * Get uniqid
     *
     * @return string
     */
    public function getUniqid()
    {
        return $this->uniqid;
    }

    /**
     * Set idCategoryGroup
     *
     * @param integer $idCategoryGroup
     *
     * @return sqvf_categories
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
     * Set icon
     *
     * @param string $icon
     *
     * @return sqvf_categories
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set orderItem
     *
     * @param integer $orderItem
     *
     * @return sqvf_categories
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

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return sqvf_categories
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param \DateTime $updateTime
     *
     * @return sqvf_categories
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
}

