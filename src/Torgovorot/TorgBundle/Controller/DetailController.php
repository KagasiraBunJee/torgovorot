<?php

namespace Torgovorot\TorgBundle\Controller;

use Torgovorot\TorgBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdvImages;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsVitrina;
use Torgovorot\TorgBundle\Entity\AdsDiscounts;

use Symfony\Component\HttpFoundation\Request;

use Torgovorot\TorgBundle\Entity\ChApartment;
use Torgovorot\TorgBundle\Entity\ChComm;
use Torgovorot\TorgBundle\Entity\ChGarage;
use Torgovorot\TorgBundle\Entity\ChHouse;

use Torgovorot\TorgBundle\Controller\DetailController;
use Torgovorot\TorgBundle\Entity\Address;
use Torgovorot\TorgBundle\Entity\Users;
use Torgovorot\TorgBundle\Entity\Convinience;
use Torgovorot\TorgBundle\Entity\HouseType;
use Torgovorot\TorgBundle\Entity\HouseMaterial;
use Torgovorot\TorgBundle\Entity\HousePlaning;
use Torgovorot\TorgBundle\Entity\BathType;
use Torgovorot\TorgBundle\Entity\CommType;
use Torgovorot\TorgBundle\Entity\Timetable;
use Torgovorot\TorgBundle\Entity\Cats;
use Torgovorot\TorgBundle\Helper\InfoDataBase;
use Torgovorot\TorgBundle\Entity\AdsCars;
use Torgovorot\TorgBundle\Helper\Processing\ProcessItem;
use Torgovorot\TorgBundle\Entity\GarageType;
use Doctrine\ORM\EntityManager;



class DetailController extends Controller
{
    
    private $info;
    
    public function __construct()
    {
        
        //$this->info = new InfoDataBase($this->getDoctrine()->getEntityManager());
    }
    
    public function rDetailAction($id)
    {
        $resume = new AdsResume();
        
        $resume = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->find($id);
        
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($resume->getOwnerId());
        
        $timet = $this->TimetableList($resume->getTimetable());
        
        $job_list = $this->getJobName($resume->getJobsIds());
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(3, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_resume.html.twig', 
                array(
                    'item' => $resume,
                    'user' => $user,
                    'time' => $timet,
                    'jobs' => $job_list
                ));
    }
    
    public function vDetailAction($id, $cat = 0)
    {
        $vacancy = new AdsVacance();
        
        $vacancy = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->find($id);
        
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($vacancy->getOwnerId());
                
        $timet = $this->TimetableList($vacancy->getTimetableIds());
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(2, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_vacancy.html.twig', 
                array(
                    'item' => $vacancy,
                    'user' => $user,
                    'time' => $timet
                ));
    }
    
    public function rtDetailAction($id)
    {
        $realty = new AdsRealty();
        
        
        $realty = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($id);
        
        // !
        $realty_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($realty->getAdsType());
        
        $ads_id = $realty->getAdsType();
        
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($realty->getOwnerId());

        
        $ch;
        $convinience_list_id = "";
        $other_param = array();
        switch ($ads_id)
        {
            case 1:
            case 2:
                $ch = new ChApartment();
                $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChApartment')->find($realty->getChId());
                if($ch->getBalcony() == 1)
                {
                   $convinience_list_id .= ",0"; 
                }
                if($ch->getRefregirator() == 1)
                {
                    $convinience_list_id .= ",1";
                }
                if($ch->getTv() == 1)
                {
                    $convinience_list_id .= ",2";
                }
                if($ch->getPhone() == 1)
                {
                    $convinience_list_id .= ",3";
                }
                if($ch->getConditiononeer() == 1)
                {
                    $convinience_list_id .= ",4";
                }
                if($ch->getDishwasher() == 1)
                {
                    $convinience_list_id .= ",5";
                }
                if($ch->getWashingmachine() == 1)
                {
                    $convinience_list_id .= ",6";
                }
                if($ch->getBoiler() == 1)
                {
                    $convinience_list_id .= ",7";
                }
                $other_param['house_type'] = $this->getHouseTypeNames($ch->getHouseType());
                $other_param['bath_type'] = $this->getBathNames($ch->getBathType());
                
                break;
            case 3:
                $ch = new ChHouse();
                $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChHouse')->find($realty->getChId());
                if($ch->getConvinient() == 1)
                {
                   $convinience_list_id .= ",0"; 
                }
                if($ch->getCanalization() == 1)
                {
                    $convinience_list_id .= ",1";
                }
                if($ch->getWater() == 1)
                {
                    $convinience_list_id .= ",2";
                }
                if($ch->getElectricity() == 1)
                {
                    $convinience_list_id .= ",3";
                }
                if($ch->getGas() == 1)
                {
                    $convinience_list_id .= ",4";
                }
                $other_param['hmaterial'] = $this->getHouseMaterialNames($ch->getHouseMaterial());
                $other_param['house_type'] = $this->getHouseTypeNames($ch->getHouseType());
                $other_param['planing'] = $this->getHousePlaningNames($ch->getPlaning());
                break;
            case 4:
                $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChComm')->find($realty->getChId());
                $other_param['commType'] = $this->getCommTypeNames($ch->getCommType());
                break;
            case 5:
                $ch = new ChGarage();
                $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChGarage')->find($realty->getChId());
                if($ch->getConvinient() == 1)
                {
                   $convinience_list_id .= ",0"; 
                }
                if($ch->getWater() == 1)
                {
                    $convinience_list_id .= ",1";
                }
                if($ch->getElectricity() == 1)
                {
                    $convinience_list_id .= ",2";
                }
                if($ch->getKesson() == 1)
                {
                    $convinience_list_id .= ",3";
                }
                if($ch->getObservationPit() == 1)
                {
                    $convinience_list_id .= ",4";
                }
                $other_param['gtype'] = $this->getGarageNames($ch->getGarageType());
                
                break;
        }
        
        $address = new Address();
        
        $address = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($realty->getAddrId());
        
        $convinience_list = "";
        $explode = explode(",", $convinience_list_id);
        foreach($explode as $objects)
        {
            if($objects != "")
            {
                $conv_object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Convinience')->findOneBy(array('chId'=>$ads_id,'typeId'=>$objects));
                
                $convinience_list .= $conv_object->getName().",";
            }
        }
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(1, $id);
        
        $convinience_list = substr($convinience_list, 0,-1);
        return $this->render('TorgovorotTorgBundle:Default:item_realty.html.twig', 
                array(
                    'item' => $realty,
                    'ads' => $realty_type,
                    'current_id' => $id,
                    'character' => $ch,
                    'address' => $address,
                    'adsId' => $ads_id,
                    'user' => $user,
                    'conv' => $convinience_list,
                    'other' => $other_param,
                    'images' => $this->getImagesByIds($realty->getPhotoIds())
                ));        
    }
    
    //product
    public function goodAction($id)
    {
        $vacancy = new AdsGoods();
        
        $vacancy = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods')->find($id);
        
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($vacancy->getOwnerId());
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(4, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_good.html.twig', 
                array(
                    'item' => $vacancy,
                    'user' => $user,
                    'images' => $this->getImagesByIds($vacancy->getPhotoIds())
                ));
    }
    
    //event
    public function eventAction($id)
    {
        $vacancy = new AdsEvents();
        
        $vacancy = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsEvents')->find($id);
        
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($vacancy->getOwnerId());
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(5, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_event.html.twig', 
                array(
                    'item' => $vacancy,
                    'user' => $user,
                    'images' => $this->getImagesByIds($vacancy->getPhotoIds())
                ));
    }
    
    //profile
    public function userAction($id)
    {
        $vacancy = new Users();
        
        $vacancy = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users')->find($id);
        
        $addr_array = array();
        
        if($vacancy->getAddrId() != "")
        {
            $explode = explode(";", $vacancy->getAddrId());
            foreach($explode as $aid)
            {
                $address = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($aid);
                
                if($address != null)
                {
                    $addr_array[] = $address;
                }
            }
        }
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(6, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_profile.html.twig', 
                array(
                    'item' => $vacancy,
                    'images' => $this->getImagesByIds($vacancy->getPhoto()),
                    'address' => $addr_array
                ));
    }
    
    public function carAction($id = 0, Request $request = null)
    {
        $car = new AdsCars();
        
        $car = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsCars")->find($id);
        
        $comfort_ids = explode(";", $car->getChIds());
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($car->getOwnerId());
        
        $comfort = array();
        
        foreach($comfort_ids as $val)
        {
            $comfort[] = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Characteristics")->find($val);
        }
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(8, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_car.html.twig', 
                array(
                    'item' => $car,
                    'comfort' => $comfort,
                    'user' => $user
                ));
    }
    
    public function discountAction($id = 0, Request $request = null)
    {
        $disc = new AdsDiscounts();
        
        $disc = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsDiscounts")->find($id);
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($disc->getOwnerId());
        
        $process = new ProcessItem($this->getDoctrine()->getEntityManager());
        $process->addViews(7, $id);
        
        return $this->render('TorgovorotTorgBundle:Default:item_discount.html.twig', 
                array(
                    'item' => $disc,
                    'user' => $user
                ));
    }


    //help functions
    public function getBathNames($array)
    {
        $bath_object = new BathType();
        //$bath_object = $this->em->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findAll();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $bath_object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findBy(array('id' => $value));
                if($bath_object)
                {
                    $arr_name[] = $bath_object;
                }
            
            }
            
            return $arr_name;
        }
        else 
        {
            $bath_object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findOneById($explode[0]);
            $name = $bath_object->getName();
            return $name;
        }
        
    }
    
    public function getCommTypeNames($array)
    {
        $object = new CommType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CommType')->findOneById($value);
                
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CommType')->findOneById($explode[0]);
            
            return $object->getName();
        }
    }
    
    public function getGarageNames($array)
    {
        $object = new GarageType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:GarageType')->findOneById($value);
                
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:GarageType')->findOneById($explode[0]);
            
            return $object->getName();
        }
    }
    
    public function getHouseMaterialNames($array)
    {
        $object = new HouseMaterial();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseMaterial')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseMaterial')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getHousePlaningNames($array)
    {
        $object = new HousePlaning();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HousePlaning')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HousePlaning')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getHouseTypeNames($array)
    {
        $object = new HouseType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getTimetableNames($array)
    {
        $object = new Timetable();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Timetable')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getVacanciesNames($array)
    {
        $object = new Vacancies();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Vacancies')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Vacancies')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getConvinientName($ch_id, $type)
    {
        $conv_object = new Convinience();
        $conv_object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Convinience')->findOneBy(array('chId'=>$ch_id,'typeId'=>$type));
        
        return $conv_object->getName();
    }
    
    public function TimetableList($ids)
    {
        $str = "нет";
        if($ids != "")
        {
            $str = "";
            $ids = explode(";", $ids);
        
            foreach($ids as $value)
            {
                if($value != "")
                {
                    $timetable = new Timetable();
                    $timetable = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Timetable")->find($value);
                    if($timetable != null)
                    {
                        $str .= $timetable->getName().",";
                    }
                }
            }
        
            $str = substr($str, 0, -1);
        }
        return $str;
    }
    
    public function getJobName($ids)
    {
        $str = "";
        $ids = explode(";", $ids);
        
        if($ids != "")
        {
            foreach($ids as $value)
            {
                if($value != "")
                {
                    $job = new Cats();
                    $job = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->find($value);
                    $str .= $job->getName().",";
                }
            }
            $str = substr($str, 0, -1);
        }
        
        return $str;
    }
    
    public function getImagesByIds($ids)
    {
        $ids_arr = explode(";", $ids);
        
        $img_arr = array();
        
        foreach ($ids_arr as $id)
        {
            $img_object = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdvImages")->find($id);
            
            $img_arr[] = $img_object;
        }
        
        return $img_arr;
    }
    
    
    
}
