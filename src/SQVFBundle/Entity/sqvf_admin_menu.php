<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_admin_menu
 *
 * @ORM\Table(name="sqvf_admin_menu")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_admin_menuRepository")
 */
class sqvf_admin_menu
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
     * @ORM\Column(name="id_parent", type="integer", nullable=true)
     */
    private $idParent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=50, nullable=true)
     */
    private $controller;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=50, nullable=true)
     */
    private $action;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idParent
     *
     * @param integer $idParent
     *
     * @return sqvf_admin_menu
     */
    public function setIdParent($idParent)
    {
        $this->idParent = $idParent;

        return $this;
    }

    /**
     * Get idParent
     *
     * @return int
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return sqvf_admin_menu
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
     * Set controller
     *
     * @param string $controller
     *
     * @return sqvf_admin_menu
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return sqvf_admin_menu
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return sqvf_admin_menu
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
     * @return sqvf_admin_menu
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

