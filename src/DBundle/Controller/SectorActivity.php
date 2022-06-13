<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SectorActivity
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="SIGTAS.SECTOR_ACTIVITY")
 */
class SectorActivity
{
    private function __construct() {}
    /**
     * @var int
     *
     * @ORM\Column(name="SECTOR_ACT_NO",  type="integer")
     * @ORM\Id
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="SECTOR_ACT_DESC", type="string", length=255)
     */
    public $sectorActDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="SECTOR_ACT_DESC_F", type="string", length=255)
     */
    public $sectorActivityDescF;

    /**
     * @var string
     *
     * @ORM\Column(name="SECTOR_ACT_DESC_S", type="string", length=255)
     */
    public $sectorActivityDescS;

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return SectorActivity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set sectorActDesc
     *
     * @param string $sectorActDesc
     *
     * @return SectorActivity
     */
    public function setSectorActDesc($sectorActDesc)
    {
        $this->sectorActDesc = $sectorActDesc;

        return $this;
    }

    /**
     * Get sectorActDesc
     *
     * @return string
     */
    public function getSectorActDesc()
    {
        return $this->sectorActDesc;
    }

    /**
     * Set sectorActivityDescF
     *
     * @param string $sectorActivityDescF
     *
     * @return SectorActivity
     */
    public function setSectorActivityDescF($sectorActivityDescF)
    {
        $this->sectorActivityDescF = $sectorActivityDescF;

        return $this;
    }

    /**
     * Get sectorActivityDescF
     *
     * @return string
     */
    public function getSectorActivityDescF()
    {
        return $this->sectorActivityDescF;
    }

    /**
     * Set sectorActivityDescS
     *
     * @param string $sectorActivityDescS
     *
     * @return SectorActivity
     */
    public function setSectorActivityDescS($sectorActivityDescS)
    {
        $this->sectorActivityDescS = $sectorActivityDescS;

        return $this;
    }

    /**
     * Get sectorActivityDescS
     *
     * @return string
     */
    public function getSectorActivityDescS()
    {
        return $this->sectorActivityDescS;
    }
}
