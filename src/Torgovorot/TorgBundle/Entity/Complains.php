<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Complains
 */
class Complains
{
    /**
     * @var integer
     */
    private $adsType;

    /**
     * @var integer
     */
    private $adsId;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set adsType
     *
     * @param integer $adsType
     * @return Complains
     */
    public function setAdsType($adsType)
    {
        $this->adsType = $adsType;

        return $this;
    }

    /**
     * Get adsType
     *
     * @return integer 
     */
    public function getAdsType()
    {
        return $this->adsType;
    }

    /**
     * Set adsId
     *
     * @param integer $adsId
     * @return Complains
     */
    public function setAdsId($adsId)
    {
        $this->adsId = $adsId;

        return $this;
    }

    /**
     * Get adsId
     *
     * @return integer 
     */
    public function getAdsId()
    {
        return $this->adsId;
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
}
