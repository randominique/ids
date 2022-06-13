<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DocCourrier
 *
 * @ORM\Table(name="DOC_COURRIER")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DocCourrierRepository")
 */
class DocCourrier
{
    /**
     * @var int
     *
     * @ORM\Column(name="DOC_NO", type="integer")
     * @ORM\id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $docNo;


    /**
     * @var int
     *
     * @ORM\Column(name="NUMERO", type="integer")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE_COURRIER", type="string", length=1, nullable=true)
     */
    private $typeCourrier;

    /**
     * @var int
     *
     * @ORM\Column(name="DIVISION_NO", type="integer", nullable=true)
     */
    private $divisionNo;

    /**
     * @var int
     * 
     * @ORM\Column(name="YEAR_COURR", type="integer")
     */
    private $yearCourr;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_COURRIER_OBJECT_NO", type="integer", nullable=true)
     */
    private $docCourrierObjectNo;

    /**
     * @var int
     *
     * @ORM\Column(name="DOC_COURRIER_TITRE_NO", type="integer", nullable=true)
     */
    private $docCourrierTitreNo;


    /**
     * @var string
     *
     *
     */
    private $objetActe;

    /**
     * @var string
     *
     * 
     */
    private $responsable;

    /**
     * @var string
     *
     * 
     */
    private $nom;

    /**
     * @var \DateTime
     *
     */
    private $date;

    /**
     * @var \DateTime
     *
     */
    private $delegationDate;

    /**
     * @var \DateTime
     *
     */
    private $traitementDate;

    /**
     * @var string
     *
     */
    private $status;

    /**
     * @var string
     *
     */
    private $priority;

    
    private $service;

   
    private $pourInfo;


    /**
    * @Assert\NotBlank
    */
    private $observationContent;


    
    public $dispatchings;



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
     * Set numero
     *
     * @param integer $numero
     *
     * @return DocCourrier
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set typeCourrier
     *
     * @param string $typeCourrier
     *
     * @return DocCourrier
     */
    public function setTypeCourrier($typeCourrier)
    {
        $this->typeCourrier = $typeCourrier;

        return $this;
    }

    /**
     * Get typeCourrier
     *
     * @return string
     */
    public function getTypeCourrier()
    {
        return $this->typeCourrier;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return DocCourrier
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * Set divisionNo
     *
     * @param integer $divisionNo
     *
     * @return DocCourrier
     */
    public function setDivisionNo($divisionNo)
    {
        $this->divisionNo = $divisionNo;

        return $this;
    }

    /**
     * Get divisionNo
     *
     * @return integer
     */
    public function getDivisionNo()
    {
        return $this->divisionNo;
    }

    /**
     * Set docCourrierObjectNo
     *
     * @param integer $docCourrierObjectNo
     *
     * @return DocCourrier
     */
    public function setDocCourrierObjectNo($docCourrierObjectNo)
    {
        $this->docCourrierObjectNo = $docCourrierObjectNo;

        return $this;
    }

    /**
     * Get docCourrierObjectNo
     *
     * @return integer
     */
    public function getDocCourrierObjectNo()
    {
        return $this->docCourrierObjectNo;
    }

    /**
     * Set docCourrierTitreNo
     *
     * @param integer $docCourrierTitreNo
     *
     * @return DocCourrier
     */
    public function setDocCourrierTitreNo($docCourrierTitreNo)
    {
        $this->docCourrierTitreNo = $docCourrierTitreNo;

        return $this;
    }

    /**
     * Get docCourrierTitreNo
     *
     * @return integer
     */
    public function getDocCourrierTitreNo()
    {
        return $this->docCourrierTitreNo;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return DocCourrier
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set objetActe
     *
     * @param string $objetActe
     *
     * @return DocCourrier
     */
    public function setObjetActe($objetActe)
    {
        $this->objetActe = $objetActe;

        return $this;
    }

    /**
     * Get objetActe
     *
     * @return string
     */
    public function getObjetActe()
    {
        return $this->objetActe;
    }

    /**
     * Set observationContent
     *
     * @param string $observationContent
     *
     * @return DocCourrier
     */
    public function setObservationContent($observationContent)
    {
        $this->observationContent = $observationContent;

        return $this;
    }

    /**
     * Get observationContent
     *
     * @return string
     */
    public function getObservationContent()
    {
        return $this->observationContent;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return DocCourrier
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return DocCourrier
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
     * Set date
     *
     * @param \dateTime $date
     *
     * @return DocCourrier
     */
    public function setDate(\dateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \dateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set yearCourr
     *
     * @param int $yearCourr
     *
     * @return DocCourrier
     */
    public function setYearCourr(int $yearCourr)
    {
        $this->yearCourr = $yearCourr;

        return $this;
    }

    /**
     * Get yearCourr
     *
     * @return innt
     */
    public function getYearCourr()
    {
        return $this->yearCourr;
    }


    /**
     * Set traitementDate
     *
     * @param \dateTime $traitementDate
     *
     * @return DocCourrier
     */
    public function setTraitementDate(\dateTime $traitementDate)
    {
        $this->traitementDate = $traitementDate;

        return $this;
    }

    /**
     * Get traitement Date
     *
     * @return \dateTime
     */
    public function getTraitementDate()
    {
        return $this->traitementDate;
    }

    /**
     * Set delegationDate
     *
     * @param \dateTime $delegationDate
     *
     * @return DocCourrier
     */
    public function setDelegationDate(\dateTime $delegationDate)
    {
        $this->delegationDate = $delegationDate;

        return $this;
    }

    /**
     * Get delegationDate
     *
     * @return \dateTime
     */
    public function getDelegationDate()
    {
        return $this->delegationDate;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return DocCourrier
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return DocCourrier
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pourInfo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dispatchings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pourInfo
     *
     * @param \SIGTASBundle\Entity\PourInfo $pourInfo
     *
     * @return DocCourrier
     */
    public function addPourInfo(\SIGTASBundle\Entity\PourInfo $pourInfo)
    {
        $this->pourInfo[] = $pourInfo;

        return $this;
    }

    /**
     * Remove pourInfo
     *
     * @param \SIGTASBundle\Entity\PourInfo $pourInfo
     */
    public function removePourInfo(\SIGTASBundle\Entity\PourInfo $pourInfo)
    {
        $this->pourInfo->removeElement($pourInfo);
    }

    /**
     * Get pourInfo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPourInfo()
    {
        return $this->pourInfo;
    }

    /**
     * Add dispatching
     *
     * @param \DBundle\Entity\CourrierDispatching $dispatching
     *
     * @return DocCourrier
     */
    public function addDispatching(\DBundle\Entity\CourrierDispatching $dispatching)
    {
        $this->dispatchings[] = $dispatching;

        return $this;
    }

    /**
     * Remove dispatching
     *
     * @param \DBundle\Entity\CourrierDispatching $dispatching
     */
    public function removeDispatching(\DBundle\Entity\CourrierDispatching $dispatching)
    {
        $this->dispatchings->removeElement($dispatching);
    }

    /**
     * Get dispatchings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDispatchings()
    {
        return $this->dispatchings;
    }
}
