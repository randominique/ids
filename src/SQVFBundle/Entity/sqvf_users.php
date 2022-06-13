<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="sqvf_users")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_usersRepository")
 */
class sqvf_users
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
     * @ORM\Column(name="id_type_user", type="integer", nullable=true)
     */
    private $idTypeUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_centre_fiscal", type="integer", nullable=true)
     */
    private $idCentreFiscal;

    /**
     * @var int
     *
     * @ORM\Column(name="id_chef", type="integer", nullable=true)
     */
    private $idChef;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=200, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=200, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=400, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=1020, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=1020, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=200, nullable=true)
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    private $lastActivity;

    /**
     * @var int
     *
     * @ORM\Column(name="archive", type="integer", nullable=true)
     */
    private $archive;

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
     * Set idTypeUser
     *
     * @param integer $idTypeUser
     *
     * @return Users
     */
    public function setIdTypeUser($idTypeUser)
    {
        $this->idTypeUser = $idTypeUser;

        return $this;
    }

    /**
     * Get idTypeUser
     *
     * @return int
     */
    public function getIdTypeUser()
    {
        return $this->idTypeUser;
    }

    /**
     * Set idCentreFiscal
     *
     * @param integer $idCentreFiscal
     *
     * @return Users
     */
    public function setIdCentreFiscal($idCentreFiscal)
    {
        $this->idCentreFiscal = $idCentreFiscal;

        return $this;
    }

    /**
     * Get idCentreFiscal
     *
     * @return int
     */
    public function getIdCentreFiscal()
    {
        return $this->idCentreFiscal;
    }

    /**
     * Set idChef
     *
     * @param integer $idChef
     *
     * @return Users
     */
    public function setIdChef($idChef)
    {
        $this->idChef = $idChef;

        return $this;
    }

    /**
     * Get idChef
     *
     * @return int
     */
    public function getIdChef()
    {
        return $this->idChef;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Users
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Users
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Users
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return Users
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return Users
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set archive
     *
     * @param integer $archive
     *
     * @return Users
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return int
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return Users
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
     * @return Users
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

