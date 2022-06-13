<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourrierDispatching
 *
 * @ORM\Table(name="courrier_dispatching")
 * @ORM\Entity(repositoryClass="DBundle\Repository\CourrierDispatchingRepository")
 */
class CourrierDispatching
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
     * @ORM\Column(name="doc_no", type="integer")
     */
    private $docNo;
    
    public $doc;

    /**
     * @var traite
     *
     * @ORM\Column(name="traite", type="boolean", options={"default":false})
     */
    private $traite;

    /**
     * @var cloturer
     *
     * @ORM\Column(name="cloturer", type="boolean", options={"default":false})
     */
    private $cloturer;

    /**
    * @ORM\ManyToOne(targetEntity="DBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $gestionnaire;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="DBundle\Entity\Service")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @var informative
     *
     * @ORM\Column(name="informative", type="boolean", options={"default":false})
     */
    private $informative; 

    public function __construct(){
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return CourrierDispatching
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

    /**
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return CourrierDispatching
     */
    public function setService(\DBundle\Entity\Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \DBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set docNo
     *
     * @param integer $docNo
     *
     * @return CourrierDispatching
     */
    public function setDocNo($docNo)
    {
        $this->docNo = $docNo;

        return $this;
    }

    /**
     * Get docNo
     *
     * @return integer
     */
    public function getDocNo()
    {
        return $this->docNo;
    }

    /**
     * Set gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     *
     * @return CourrierDispatching
     */
    public function setGestionnaire(\DBundle\Entity\User $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \DBundle\Entity\User
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }

    /**
     * Set traite
     *
     * @param boolean $traite
     *
     * @return CourrierDispatching
     */
    public function setTraite($traite)
    {
        $this->traite = $traite;

        return $this;
    }

    /**
     * Get traite
     *
     * @return boolean
     */
    public function getTraite()
    {
        return $this->traite;
    }

    /**
     * Set cloturer
     *
     * @param boolean $cloturer
     *
     * @return CourrierDispatching
     */
    public function setCloturer($cloturer)
    {
        $this->cloturer = $cloturer;

        return $this;
    }

    /**
     * Get cloturer
     *
     * @return boolean
     */
    public function getCloturer()
    {
        return $this->cloturer;
    }

    /**
     * Set informative
     *
     * @param boolean $informative
     *
     * @return CourrierDispatching
     */
    public function setInformative($informative)
    {
        $this->informative = $informative;

        return $this;
    }

    /**
     * Get informative
     *
     * @return boolean
     */
    public function getInformative()
    {
        return $this->informative;
    }
}
