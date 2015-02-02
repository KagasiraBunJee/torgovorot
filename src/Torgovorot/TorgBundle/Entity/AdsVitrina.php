<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsVitrina
 */
class AdsVitrina
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $descr;

    /**
     * @var string
     */
    private $categoryIds;

    /**
     * @var string
     */
    private $photoIds;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var \DateTime
     */
    private $updateTime;

    /**
     * @var integer
     */
    private $recommend;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set title
     *
     * @param string $title
     * @return AdsVitrina
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
     * Set descr
     *
     * @param string $descr
     * @return AdsVitrina
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set categoryIds
     *
     * @param string $categoryIds
     * @return AdsVitrina
     */
    public function setCategoryIds($categoryIds)
    {
        $this->categoryIds = $categoryIds;

        return $this;
    }

    /**
     * Get categoryIds
     *
     * @return string 
     */
    public function getCategoryIds()
    {
        return $this->categoryIds;
    }

    /**
     * Set photoIds
     *
     * @param string $photoIds
     * @return AdsVitrina
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

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return AdsVitrina
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
     * Set updateTime
     *
     * @param \DateTime $updateTime
     * @return AdsVitrina
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

    /**
     * Set recommend
     *
     * @param integer $recommend
     * @return AdsVitrina
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
    private $premium;

    /**
     * @var integer
     */
    private $vip;

    /**
     * @var integer
     */
    private $views;


    /**
     * Set premium
     *
     * @param integer $premium
     * @return AdsVitrina
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
     * @return AdsVitrina
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
     * Set views
     *
     * @param integer $views
     * @return AdsVitrina
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
    private $shortDescr;


    /**
     * Set shortDescr
     *
     * @param string $shortDescr
     * @return AdsVitrina
     */
    public function setShortDescr($shortDescr)
    {
        $this->shortDescr = $shortDescr;

        return $this;
    }

    /**
     * Get shortDescr
     *
     * @return string 
     */
    public function getShortDescr()
    {
        return $this->shortDescr;
    }
    /**
     * @var integer
     */
    private $special;


    /**
     * Set special
     *
     * @param integer $special
     * @return AdsVitrina
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
     * @var string
     */
    private $town;


    /**
     * Set town
     *
     * @param string $town
     * @return AdsVitrina
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
