<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsRealty
 */
class AdsRealty
{
    /**
     * @var integer
     */
    private $ownerId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var integer
     */
    private $categoryId;

    /**
     * @var float
     */
    private $price;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var integer
     */
    private $recommend;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    private $addrId;

    /**
     * @var integer
     */
    private $adsType;

    /**
     * @var integer
     */
    private $chId;

    /**
     * @var string
     */
    private $otherType;

    /**
     * @var integer
     */
    private $id;

    //virtual
    /**
     * @var string
     */
    private $imageUrl;
    

    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return AdsRealty
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
     * Set title
     *
     * @param string $title
     * @return AdsRealty
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
     * Set description
     *
     * @param string $description
     * @return AdsRealty
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
     * Set time
     *
     * @param \DateTime $time
     * @return AdsRealty
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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return AdsRealty
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return AdsRealty
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return AdsRealty
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set recommend
     *
     * @param integer $recommend
     * @return AdsRealty
     */
    public function setRecommend($recommend)
    {
        $this->recommend = $recommend;

        return $this;
    }

    /**
     * Get recommend
     *
     * @return integer 
     */
    public function getRecommend()
    {
        return $this->recommend;
    }

    /**
     * Set days
     *
     * @param integer $days
     * @return AdsRealty
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set addrId
     *
     * @param integer $addrId
     * @return AdsRealty
     */
    public function setAddrId($addrId)
    {
        $this->addrId = $addrId;

        return $this;
    }

    /**
     * Get addrId
     *
     * @return integer 
     */
    public function getAddrId()
    {
        return $this->addrId;
    }

    /**
     * Set adsType
     *
     * @param integer $adsType
     * @return AdsRealty
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
     * Set chId
     *
     * @param integer $chId
     * @return AdsRealty
     */
    public function setChId($chId)
    {
        $this->chId = $chId;

        return $this;
    }

    /**
     * Get chId
     *
     * @return integer 
     */
    public function getChId()
    {
        return $this->chId;
    }

    /**
     * Set otherType
     *
     * @param string $otherType
     * @return AdsRealty
     */
    public function setOtherType($otherType)
    {
        $this->otherType = $otherType;

        return $this;
    }

    /**
     * Get otherType
     *
     * @return string 
     */
    public function getOtherType()
    {
        return $this->otherType;
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
     * @var string
     */
    private $photoIds;


    /**
     * Set photoIds
     *
     * @param string $photoIds
     * @return AdsRealty
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
    
    //virtual
    public function setPhotoUrl($url)
    {
        $this->imageUrl = $url;
    }
    
    public function getPhotoUrl()
    {
        return $this->imageUrl;
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
     * @return AdsRealty
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
     * @return AdsRealty
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
     * @return AdsRealty
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
     * @return AdsRealty
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
     * @var integer
     */
    private $special;

    /**
     * @var string
     */
    private $shortDesc;


    /**
     * Set special
     *
     * @param integer $special
     * @return AdsRealty
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
     * Set shortDesc
     *
     * @param string $shortDesc
     * @return AdsRealty
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
     * @var integer
     */
    private $adsState;


    /**
     * Set adsState
     *
     * @param integer $adsState
     * @return AdsRealty
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
    private $town;


    /**
     * Set town
     *
     * @param string $town
     * @return AdsRealty
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
}
