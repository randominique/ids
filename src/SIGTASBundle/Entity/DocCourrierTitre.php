<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocCourrierTitre
 *
 * @ORM\Table(name="doc_courrier_titre")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\DocCourrierTitreRepository")
 */
class DocCourrierTitre
{
    /**
     * @var int
     *
     * @ORM\Column(name="DOC_COURRIER_TITRE_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="DOC_COURRIER_TITRE", type="string", length=255)
     */
    private $docCourrierTitre;


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
     * Set docCourrierTitre
     *
     * @param string $docCourrierTitre
     *
     * @return DocCourrierTitre
     */
    public function setDocCourrierTitre($docCourrierTitre)
    {
        $this->docCourrierTitre = $docCourrierTitre;

        return $this;
    }

    /**
     * Get docCourrierTitre
     *
     * @return string
     */
    public function getDocCourrierTitre()
    {
        return $this->docCourrierTitre;
    }
}

