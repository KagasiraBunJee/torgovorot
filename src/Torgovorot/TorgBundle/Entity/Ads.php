<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ads
 */
class Ads
{
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
     * @var integer
     */
    private $id;


    /**
     * Set title
     *
     * @param string $title
     * @return Ads
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
     * @return Ads
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
     * @return Ads
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
     * @return Ads
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
    private $ownerId;

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
     * Set ownerId
     *
     * @param integer $ownerId
     * @return Ads
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
     * Set price
     *
     * @param float $price
     * @return Ads
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
     * @return Ads
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
     * @return Ads
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
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    private $atype;

    /**
     * @var integer
     */
    private $stype;


    /**
     * Set days
     *
     * @param integer $days
     * @return Ads
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
     * Set atype
     *
     * @param integer $atype
     * @return Ads
     */
    public function setAtype($atype)
    {
        $this->atype = $atype;

        return $this;
    }

    /**
     * Get atype
     *
     * @return integer 
     */
    public function getAtype()
    {
        return $this->atype;
    }

    /**
     * Set stype
     *
     * @param integer $stype
     * @return Ads
     */
    public function setStype($stype)
    {
        $this->stype = $stype;

        return $this;
    }

    /**
     * Get stype
     *
     * @return integer 
     */
    public function getStype()
    {
        return $this->stype;
    }
}
