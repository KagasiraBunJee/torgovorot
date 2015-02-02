<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CharacterBind
 */
class CharacterBind
{
    /**
     * @var integer
     */
    private $characterId;

    /**
     * @var integer
     */
    private $labelId;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set characterId
     *
     * @param integer $characterId
     * @return CharacterBind
     */
    public function setCharacterId($characterId)
    {
        $this->characterId = $characterId;

        return $this;
    }

    /**
     * Get characterId
     *
     * @return integer 
     */
    public function getCharacterId()
    {
        return $this->characterId;
    }

    /**
     * Set labelId
     *
     * @param integer $labelId
     * @return CharacterBind
     */
    public function setLabelId($labelId)
    {
        $this->labelId = $labelId;

        return $this;
    }

    /**
     * Get labelId
     *
     * @return integer 
     */
    public function getLabelId()
    {
        return $this->labelId;
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
    private $adsType;


    /**
     * Set adsType
     *
     * @param integer $adsType
     * @return CharacterBind
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

}
