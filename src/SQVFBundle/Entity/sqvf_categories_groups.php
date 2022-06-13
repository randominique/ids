<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_categories_groups
 *
 * @ORM\Table(name="sqvf_categories_groups")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_categories_groupsRepository")
 */
class sqvf_categories_groups
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
     * Set orderItem
     *
     * @param integer $orderItem
     *
     * @return sqvf_categories_groups
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
     * @return sqvf_categories_groups
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
     * @return sqvf_categories_groups
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

