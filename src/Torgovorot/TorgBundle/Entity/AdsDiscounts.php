<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsDiscounts
 */
class AdsDiscounts
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $ownerId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $discount;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set title
     *
     * @param string $title
     * @return AdsDiscounts
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return AdsDiscounts
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return AdsDiscounts
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return AdsDiscounts
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer 
     */
    public function getDiscount()
    {
        return $this->discount;
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
     * @var integer
     */
    private $emergency;

    /**
     * @var integer
     */
    private $premium;

    /**
     * @var integer
     */
    private $vip;


    /**
     * Set emergency
     *
     * @param integer $emergency
     * @return AdsDiscounts
     */
    public function setEmergency($emergency)
    {
        $this->emergency = $emergency;

        return $this;
    }

    /**
     * Get emergency
     *
     * @return integer 
     */
    public function getEmergency()
    {
        return $this->emergency;
    }

    /**
     * Set premium
     *
     * @param integer $premium
     * @return AdsDiscounts
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * Get premium
     *
     * @return integer 
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set vip
     *
     * @param integer $vip
     * @return AdsDiscounts
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return integer 
     */
    public function getVip()
    {
        return $this->vip;
    }
    /**
     * @var integer
     */
    private $views;


    /**
     * Set views
     *
     * @param integer $views
     * @return AdsDiscounts
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }
    /**
     * @var string
     */
    private $shortDesc;


    /**
     * Set shortDesc
     *
     * @param string $shortDesc
     * @return AdsDiscounts
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }

    /**
     * Get shortDesc
     *
     * @return string 
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }
    /**
     * @var \DateTime
     */
    private $time;


    /**
     * Set time
     *
     * @param \DateTime $time
     * @return AdsDiscounts
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @var integer
     */
    private $special;


    /**
     * Set special
     *
     * @param integer $special
     * @return AdsDiscounts
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get special
     *
     * @return integer 
     */
    public function getSpecial()
    {
        return $this->special;
    }
    /**
     * @var integer
     */
    private $adsState;


    /**
     * Set adsState
     *
     * @param integer $adsState
     * @return AdsDiscounts
     */
    public function setAdsState($adsState)
    {
        $this->adsState = $adsState;

        return $this;
    }

    /**
     * Get adsState
     *
     * @return integer 
     */
    public function getAdsState()
    {
        return $this->adsState;
    }
    /**
     * @var string
     */
    private $addrId;

    /**
     * @var string
     */
    private $town;


    /**
     * Set addrId
     *
     * @param string $addrId
     * @return AdsDiscounts
     */
    public function setAddrId($addrId)
    {
        $this->addrId = $addrId;

        return $this;
    }

    /**
     * Get addrId
     *
     * @return string 
     */
    public function getAddrId()
    {
        return $this->addrId;
    }

    /**
     * Set town
     *
     * @param string $town
     * @return AdsDiscounts
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string 
     */
    public function getTown()
    {
        return $this->town;
    }
    /**
     * @var string
     */
    private $photoIds;


    /**
     * Set photoIds
     *
     * @param string $photoIds
     * @return AdsDiscounts
     */
    public function setPhotoIds($photoIds)
    {
        $this->photoIds = $photoIds;

        return $this;
    }

    /**
     * Get photoIds
     *
     * @return string 
     */
    public function getPhotoIds()
    {
        return $this->photoIds;
    }
}
