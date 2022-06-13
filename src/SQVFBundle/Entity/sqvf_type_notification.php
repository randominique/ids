<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeNotification
 *
 * @ORM\Table(name="sqvf_type_notification")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_type_notificationRepository")
 */
class sqvf_type_notification
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
     * @ORM\Column(name="titre", type="string", length=150, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", length=150, nullable=true)
     */
    private $permalink;

    /**
     * @var string
     *
     * @ORM\Column(name="type_notification", type="string", length=50, nullable=true)
     */
    private $typeNotification;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=50, nullable=true)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="text_class", type="string", length=50, nullable=true)
     */
    private $textClass;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return TypeNotification
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     *
     * @return TypeNotification
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
     * Set typeNotification
     *
     * @param string $typeNotification
     *
     * @return TypeNotification
     */
    public function setTypeNotification($typeNotification)
    {
        $this->typeNotification = $typeNotification;

        return $this;
    }

    /**
     * Get typeNotification
     *
     * @return string
     */
    public function getTypeNotification()
    {
        return $this->typeNotification;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return TypeNotification
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
     * Set textClass
     *
     * @param string $textClass
     *
     * @return TypeNotification
     */
    public function setTextClass($textClass)
    {
        $this->textClass = $textClass;

        return $this;
    }

    /**
     * Get textClass
     *
     * @return string
     */
    public function getTextClass()
    {
        return $this->textClass;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return TypeNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

