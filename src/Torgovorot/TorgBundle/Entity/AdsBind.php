<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsBind
 */
class AdsBind
{
    /**
     * @var integer
     */
    private $charaterId;

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
     * Set charaterId
     *
     * @param integer $charaterId
     * @return AdsBind
     */
    public function setCharaterId($charaterId)
    {
        $this->charaterId = $charaterId;

        return $this;
    }

    /**
     * Get charaterId
     *
     * @return integer 
     */
    public function getCharaterId()
    {
        return $this->charaterId;
    }

    /**
     * Set adsType
     *
     * @param integer $adsType
     * @return AdsBind
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
     * @return AdsBind
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
