<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocCourrier
 *
 * @ORM\Table(name="doc_courrier")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DocCourrierRepository")
 */
class DocCourrier
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
     * @ORM\Column(name="DOC_NO", type="integer")
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
     * @ORM\Column(name="TYPE_COURRIER", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="OBJET_ACTE", type="string", length=255)
     */
    private $objetActe;

    /**
     * @var string
     *
     * @ORM\Column(name="DESTINATAIRE", type="string", length=255)
     */
    private $destinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="PourInfo", mappedBy="courrier", cascade={"persist"})
     */
    private $pourInfo;



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
     * Set docNo
     *
     * @param integer $docNo
     *
     * @return DocCourrier
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
     * Set destinataire
     *
     * @param string $destinataire
     *
     * @return DocCourrier
     */
    public function setDestinataire($destinataire)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return string
     */
    public function getDestinataire()
    {
        return $this->destinataire;
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
}
