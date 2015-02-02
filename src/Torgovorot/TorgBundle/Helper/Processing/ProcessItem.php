<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProcessItem
 *
 * @author serg
 */


namespace Torgovorot\TorgBundle\Helper\Processing;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManager;
//use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsDiscounts;
use Torgovorot\TorgBundle\Entity\Cats;
use Symfony\Component\HttpFoundation\Request;
use Torgovorot\TorgBundle\Entity\Views;
use Torgovorot\TorgBundle\Entity\Users;
use Torgovorot\TorgBundle\Entity\Complains;

class ProcessItem 
{    
    
    private $em;
    private $request;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function addViews($ads_type = 1, $id = 0, $param = array())
    {
        $entity = "none";
        $em = $this->em;
        
        switch($ads_type)
        {
            case 1:
                $entity = "AdsRealty";
                break;
            case 2:
                $entity = "AdsVacance";
                break;
            case 3:
                $entity = "AdsResume";
                break;
            case 4:
                $entity = "AdsGoods";
                break;
            case 5:
                $entity = "AdsEvents";
                break;
            /*case 6:
                $entity = "Users";
                break;*/
            case 7:
                $entity = "AdsDiscounts";
                break;
            case 8:
                $entity = "AdsCars";
                break;
            default :
                $entity = "none";
                break;
        }
        
        if($entity == "none")
        {
            return false;
        }
        
        $item = $em->getRepository("TorgovorotTorgBundle:$entity")->find($id);
        
        if($this->saveView($ads_type, $id))
        {
            $item->setViews($item->getViews()+1);
        }
        
        $em->persist($item);
        $em->flush();
        
        return true;
    }
    
    public function sendClaim($ads_type = 1, $id = 0, $param = array())
    {
        
    }
    
    private function saveView($ads_type, $id)
    {
        $em = $this->em;
        
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $item = new Views();
        
        $item = $em->getRepository("TorgovorotTorgBundle:Views")->findOneBy(array('ip' => $ip));
        
        $can_add = false;
        
        $hash = md5($ads_type."+".$id."+".$ip);
        
        if(isset($_COOKIE[$ads_type."|".$id]))
        {
            $can_add = false;
            return false;
            
        }
        else
        {
            
            $can_add = true;
        }
        if($item == NULL and $can_add)
        {
            $new_item = new Views();
            $new_item->setIp($ip);
            $new_item->setTime(new \DateTime("now"));
            $em->persist($new_item);
            $em->flush();
            
            setcookie ($ads_type."|".$id, $hash,time()+3500*24);
            
            return true;
        }
        else if($item != NULL and $can_add)
        {
            setcookie ($ads_type."|".$id, $hash,time()+3500*24);
            if($item->getTime() >= new \DateTime("now"))
            {
                $em->remove($item);
                $em->flush();
                
                $new_item = new Views();
                $new_item->setIp($ip);
                $new_item->setTime(new \DateTime("now"));
                $em->persist($new_item);
                $em->flush();
            }
            return true;
            
        }
        else
        {
            return false;
        }
    }
    
    public function makeHigh($type, $id, Users $new_user)
    {
        $user = new Users();
        $user = $new_user;
        $item = NULL;
        if($user->getCredits() >= 100)
        {
            switch ($type)
            {
                case 1:
                    $item = "AdsRealty";
                    break;
                case 2:
                    $item = "AdsVacance";
                    break;
                case 3:
                    $item = "AdsResume";
                    break;
                case 4:
                    $item = "AdsGoods";
                    break;
                case 5:
                    $item = "AdsEvents";
                    break;
                case 6:
                    $item = NULL;
                    break;
                case 8:
                    $item = "AdsCars";
                    break;
                default :
                    $item = NULL;
                    break;
            }
            
            if($item != NULL)
            {
                $iitem = $this->em->getRepository("TorgovorotTorgBundle:$item")->find($id);
                if($iitem != null)
                {
                    
                    $iitem->setPremium($iitem->getPremium()+1);
                    $user->setCredits($user->getCredits()-100);
                    $this->em->persist($iitem);
                    $this->em->flush();
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
    }
    
    public function makeVip($type, $id, Users $new_user)
    {
        $user = new Users();
        $user = $new_user;
        $item = NULL;
        if($user->getCredits() >= 100)
        {
            switch ($type)
            {
                case 1:
                    $item = "AdsRealty";
                    break;
                case 2:
                    $item = "AdsVacance";
                    break;
                case 3:
                    $item = "AdsResume";
                    break;
                case 4:
                    $item = "AdsGoods";
                    break;
                case 5:
                    $item = "AdsEvents";
                    break;
                case 6:
                    $item = NULL;
                    break;
                case 8:
                    $item = "AdsCars";
                    break;
                default :
                    $item = NULL;
                    break;
            }
            
            if($item != NULL)
            {
                $iitem = $this->em->getRepository("TorgovorotTorgBundle:$item")->find($id);
                if($iitem != null)
                {
                    
                    $iitem->setVip(1);
                    $user->setCredits($user->getCredits()-100);
                    $this->em->persist($iitem);
                    $this->em->flush();
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
        }
    }
    
    public function makePremium($type, $id, Users $new_user)
    {
        $user = new Users();
        $user = $new_user;
        $item = NULL;
        
        if($new_user->getCredits() >= 100)
        {
            
            switch ($type)
            {
                case 1:
                    $item = "AdsRealty";
                    break;
                case 2:
                    $item = "AdsVacance";
                    break;
                case 3:
                    $item = "AdsResume";
                    break;
                case 4:
                    $item = "AdsGoods";
                    break;
                case 5:
                    $item = "AdsEvents";
                    break;
                case 6:
                    $item = NULL;
                    break;
                case 8:
                    $item = "AdsCars";
                    break;
                default :
                    $item = NULL;
                    break;
            }
            if($item != NULL)
            {
                $iitem = $this->em->getRepository("TorgovorotTorgBundle:$item")->find($id);
                if($iitem != null)
                {
                    
                    $iitem->setEmergency(1);
                    $user->setCredits($user->getCredits()-100);
                    $this->em->persist($iitem);
                    $this->em->flush();
                    $this->em->persist($user);
                    $this->em->flush();
                }
                
            }
        }
    }
    
    public function translit($str)
    {
        $new_name = "";
        
        $trans = array("а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e", "ё"=>"yo","ж"=>"j","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t", "у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch", "ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u","я"=>"ya",
                "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E", "Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I","Й"=>"I","К"=>"K", "Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P", "Р"=>"R","С"=>"S","Т"=>"T","У"=>"Y","Ф"=>"F", "Х"=>"H","Ц"=>"C","Ч"=>"Ch","Ш"=>"Sh","Щ"=>"Sh", "Ы"=>"I","Э"=>"E","Ю"=>"U","Я"=>"Ya",
                "ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>""," "=>"-","'"=>"",'"'=>"",","=>"","."=>"","/"=>"",'\\'=>"",":"=>"",";"=>"","!"=>"","@"=>"","#"=>"","$"=>"","%"=>"","^"=>"","&"=>"","*"=>"","("=>"",")"=>"","+"=>"","="=>"","`"=>"","~"=>"","№"=>"","?"=>"","\\"=>"","/"=>"","|"=>"","«"=>"","»"=>"");
        
        $new_name = strtr($str,$trans);
        
        return strtolower($new_name);
    }
    
    public function makeComplain($ads_type = 1, $id = 0, $param = array())
    {
        $entity = "none";
        $ip = $_SERVER['REMOTE_ADDR'];
        switch($ads_type)
        {
            case 1:
                $entity = "AdsRealty";
                break;
            case 2:
                $entity = "AdsVacance";
                break;
            case 3:
                $entity = "AdsResume";
                break;
            case 4:
                $entity = "AdsGoods";
                break;
            case 5:
                $entity = "AdsEvents";
                break;
            case 6:
                //$entity = "AdsRealty";
                break;
            case 7:
                //$entity = "AdsRealty";
                break;
            case 8:
                $entity = "AdsCars";
                break;
            default:
                $entity = "none";
                break;
            
        }
        if($entity != "none")
        {
            $complain = new Complains();
            
            $complain->setAdsType($ads_type);
            $complain->setAdsId($id);
            
            $this->em->persist($complain);
            $this->em->flush();
        }
    }
}
