<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocCourrierObjet
 *
 * @ORM\Table(name="doc_courrier_object")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DocCourrierObjetRepository")
 */
class DocCourrierObjet
{
    /**
     * @var int
     *
     * @ORM\Column(name="DOC_COURRIER_OBJECT_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DOC_COURRIER_OBJECT", type="string", length=250)
     */
    private $docCourrierObjet;


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
     * Set docCourrierObjet
     *
     * @param string $docCourrierObjet
     *
     * @return DocCourrierObjet
     */
    public function setDocCourrierObjet($docCourrierObjet)
    {
        $this->docCourrierObjet = $docCourrierObjet;

        return $this;
    }

    /**
     * Get docCourrierObjet
     *
     * @return string
     */
    public function getDocCourrierObjet()
    {
        return $this->docCourrierObjet;
    }
}

