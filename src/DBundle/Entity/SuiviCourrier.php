<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviCourrier
 *
 * @ORM\Table(name="suivi_courrier")
 * @ORM\Entity(repositoryClass="DBundle\Repository\SuiviCourrierRepository")
 */
class SuiviCourrier
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
     * @ORM\Column(name="doc_no", type="string", length=255)
     */
    private $docNo;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set docNo
     *
     * @param string $docNo
     *
     * @return SuiviCourrier
     */
    public function setDocNo($docNo)
    {
        $this->docNo = $docNo;

        return $this;
    }

    /**
     * Get docNo
     *
     * @return string
     */
    public function getDocNo()
    {
        return $this->docNo;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return SuiviCourrier
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SuiviCourrier
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

