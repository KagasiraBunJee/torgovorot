<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Torgovorot\TorgBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Torgovorot\TorgBundle\Form\Type\RegistrationType;
use Torgovorot\TorgBundle\Form\Model\Registration;
use Torgovorot\TorgBundle\Entity\Users;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Torgovorot\TorgBundle\Helper\Processing\ProcessItem;
use Torgovorot\TorgBundle\Helper\Uploader\Document;
//
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsVitrina;
use Torgovorot\TorgBundle\Entity\Banners;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsCars;
//realty databases
use Torgovorot\TorgBundle\Entity\ChApartment;
use Torgovorot\TorgBundle\Entity\ChComm;
use Torgovorot\TorgBundle\Entity\ChGarage;
use Torgovorot\TorgBundle\Entity\ChHouse;

use Torgovorot\TorgBundle\Entity\GarageType;
use Torgovorot\TorgBundle\Entity\RealtyType;
use Torgovorot\TorgBundle\Entity\Timetable;
use Torgovorot\TorgBundle\Entity\Convinience;
use Torgovorot\TorgBundle\Entity\CommType;
use Torgovorot\TorgBundle\Entity\GoodsSellTypes;
use Torgovorot\TorgBundle\Entity\HouseMaterial;
use Torgovorot\TorgBundle\Entity\HousePlaning;
use Torgovorot\TorgBundle\Entity\HouseType;
use Torgovorot\TorgBundle\Entity\Videos;
use Torgovorot\TorgBundle\Entity\Address;
//end realty databases
//forms
use Torgovorot\TorgBundle\Form\UsersType;
use Torgovorot\TorgBundle\Form\AddressType;
use Torgovorot\TorgBundle\Form\CatsType;
use Torgovorot\TorgBundle\Form\AdsGoodsType;
use Torgovorot\TorgBundle\Form\AdsVacanceType;
use Torgovorot\TorgBundle\Form\AdsResumeType;
use Torgovorot\TorgBundle\Form\BannersType;
use Torgovorot\TorgBundle\Form\AdsEventsType;

use Symfony\Component\DomCrawler;
//endforms
//external
use Torgovorot\TorgBundle\Helper\Parser\Parser;
/**
 * Description of AdminParserController
 *
 * @author Sergei
 */
class AdminParserController extends Controller{
    
    private $main_link = "http://www.avito.ru";
    
    private $realty_link = "http://www.avito.ru/rybinsk/nedvizhimost";
    
    private $work_link = "http://www.avito.ru/rybinsk/rabota";
    
    private $vehicle_link = "http://www.avito.ru/rybinsk/transport";
    
    private $region_keywords = array(
        'р-н',
        'район'
    );
    
    private $house_keywords = array(
        'д.',
        'дом'
    );
    
    private $street_keyword = array(
        'ул.',
        'улица',
        'набережная',
        'м.',
        'ул',
        'Крестовая,'
    );
    
    public function parseInfoAction()
    {

    }
    
    public function parseRealtyAction()
    {
        $parser = new Parser();
        
        set_time_limit(0);
        
        $em = $this->getDoctrine()->getManager();
        
        $current_page = 1;
        
        $html = $parser->file_get_html($this->realty_link);
        //$html = $parser->file_get_html($this->realty_link."?p=".$current_page);
        
        $pages_div = $html->find("div.b-paginator", 0);
        
        $last_url = $pages_div->find("a.last", 0);
        
        $last_page = $this->parsePageFromUrl($last_url->href);
        
        //global vars for checking
        $user = array(
            'name' => '',
            'prefix' => '',
            'mobile' => ''
        );
        
        $addresses = array();
        
        
        //global vars for checking
        //echo "<pre>";
        for($i = 1; $i <= $last_page; $i++)
        {
            $_html = $parser->file_get_html($this->realty_link."?p=".$i);
            
            $items = $_html->find("div.item");
            
            foreach($items as $key=>$item)
            {
                /*
                 * item prem
                 */
                $prem = 0;
                if($this->isPremium($item->class))
                {
                    $prem = 1;
                }
                
                $_post = $item->find("div.description",0)->find("h3.title", 0)->find("a", 0);
                
                
                $_item_link = $_post->href;
                
                $_item = $parser->file_get_html($this->main_link.$_item_link);
                
                //reading of current item page
                
                /*
                 * type of realty
                 */
                
                
                
                $realty_str = $_item->find("div.breadcrumbs-links",0)->find("a", 2)->plaintext;
                
                $realty_type = 1;
                
                switch($realty_str)
                {
                    case "Квартиры":
                        $realty_type = 1;
                        break;
                    case "Комнаты":
                        $realty_type = 2;
                        break;
                    case "Дома, дачи, коттеджи":
                        $realty_type = 3;
                        break;
                    case "Земельные участки":
                        $realty_type = 3;
                        break;
                    case "Гаражи и машиноместа":
                        $realty_type = 5;
                        break;
                    case "Коммерческая недвижимость":
                        $realty_type = 4;
                        break;
                    case "Недвижимость за рубежом":
                        $realty_type = 4;
                        break;
                }
                
                /*
                 * end of type of realty
                 */
                
                /*
                 * type of adv
                 */
                
                $type_str = $_item->find("div.breadcrumbs-links",0)->find("a", 3)->plaintext;
                
                $buy_type = 1;
                
                switch($type_str)
                {
                    case "Продам":
                        $buy_type = 0;
                        break;
                    case "Куплю":
                        $buy_type = 1;
                        break;
                    case "Сдам":
                        $buy_type = 2;
                        break;
                    case "Сниму":
                        $buy_type = 3;
                        break;
                }
                
                /*
                 * end of type of adv
                 */
                
                /*
                 * item title
                 */
                $_item_title = trim($_post->plaintext);
                
                
                $realty_check = $em->getRepository("TorgovorotTorgBundle:AdsRealty")->findOneBy(array('title'=>$_item_title));
                if($realty_check != null)
                {
                    continue;
                }
                /*
                 * end of title
                 */
                
                /*
                 * item price
                 */
                
                $item_price = $this->getPrice(trim($_item->find("span.t-item-price", 0)->find("span", 0)->plaintext));
                //echo $_item->find("span.t-item-price", 0)->find("span", 0)->plaintext."<br>";
                //print_r($item_price)."<br>";
                
                /*
                 * end of price
                 */
                
                /*
                 * description
                 */
                $item_description = trim($_item->find("div.description-text",0)->plaintext);
                //echo ($key+1)."|".$item_description."<br><hr>"."<br>";
                
                /*
                 * end of description
                 */
                
                /*
                 * addr
                 */
                $addr_full_str = "";
                
                $address = array(
                    'city' => 'Рыбинск',
                    'street' => '',
                    'house' => '',
                    'office' => ''
                );
                
                if($_item->find("span#toggle_map",0) != null)
                {
                    $addr_full_str = trim($_item->find("span#toggle_map",0)->plaintext);
                
                    $addr_array = explode(",", $addr_full_str);
                    
                    //print_r($addr_array);
                    
                    $addr_full_str = "";
                    
                    foreach($addr_array as $element)
                    {
                        $element = trim($element);
                        
                        if(mb_stripos($element, "Ярославская") === false and mb_stripos($element, "Рыбинск") === false)
                        {
                            $addr_full_str .= $element.", ";
                        }
                        
                        
                    }
                    //echo "<br>";
                    $addr_full_str = substr(trim($addr_full_str) ,0,  -1);
                    //echo $addr_full_str."<br>";
                    
                    $addr_arr = explode(" ", $addr_full_str);
                    //print_r($addr_arr);
                    
                    foreach($addr_arr as $akey=>$one_addr)
                    {

                        foreach($this->street_keyword as $keyword)
                        {
                            $keyword = strtolower($keyword);
                            if(stripos($one_addr, $keyword) !== false)
                            {
                                
                                if($one_addr == $keyword)
                                {
                                    //echo "ok";
                                    if($akey != 0)
                                    {
                                        
                                        if(stripos($addr_arr[($akey-1)], ",") !== false)
                                        {
                                            //echo ($akey+1)."=>".$addr_arr[($akey+1)];
                                            $address['street'] = $addr_arr[($akey+1)];
                                            //$address['street'] = str_replace($keyword, "", $address['street']);
                                            break;
                                        }
                                        
                                    }
                                    else
                                    {
                                        //echo "ok1";
                                        $address['street'] = $addr_arr[($akey+1)];
                                        //$address['street'] = str_replace($keyword, "", $address['street']);
                                        break;
                                    }
                                }
                                else
                                {
                                    
                                    $address['street'] = $one_addr;
                                    //$address['street'] = str_replace($keyword, "", $address['street']);
                                    break;
                                }
                                
                            }
                            
                        }
                    }
                    
                    foreach($addr_arr as $akey=>$one_addr)
                    {
                        if(is_numeric($one_addr))
                        {
                            $address['house'] = preg_replace('~\D+~','',$one_addr);
                            if($addr_arr[($akey-1)] != $address['street'] and $address['street'] != "")
                                    {
                                        $address['street'] .= " ".$addr_arr[($akey-1)];
                                    
                                    }
                            
                        }
                        foreach($this->house_keywords as $keyword)
                        {
                            if(stripos($one_addr, $keyword) !== false)
                            {
                                if($one_addr == $keyword)
                                {
                                    $address['house'] = preg_replace('~\D+~','',$addr_arr[($akey+1)]);
                                    if($addr_arr[($akey-1)] != $address['street'] and $address['street'] != "" and in_array($addr_arr[($akey-1)], $this->house_keywords))
                                    {
                                        $address['street'] .= " ".$addr_arr[($akey-1)];
                                    
                                    }
                                    
                                }
                                
                                else
                                {
                                    $address['house'] = preg_replace('~\D+~','',$one_addr);
                                    if($addr_arr[($akey-1)] != $address['street'] and $address['street'] != "" and in_array($addr_arr[($akey-1)], $this->house_keywords))
                                    {
                                        $address['street'] .= " ".$addr_arr[($akey-1)];
                                    
                                    }
                                    
                                }
                                
                               
                            }
                        }
                        
                        
                    }
                    //print_r($addr_arr);
                    //print_r($address);
                    $addresses[] = $address;
                    //foreach($this->region_keywords)
                    //{
                        
                    //}
                }
                
                $obj_addr = new Address();
                
                $check_addr = $em->getRepository("TorgovorotTorgBundle:Address")->findOneBy(array('street' => $address['street']));
                
                if($check_addr != null)
                {
                    $obj_addr = $check_addr;
                }
                else
                {
                    $obj_addr->setStreet($address['street']);
                    $obj_addr->setHouse($address['house']);
                    $obj_addr->setCity("Рыбинск");
                    $em->persist($obj_addr);
                    $em->flush();
                }
                /*
                 * end of addr
                 */
                
                /*
                 * user
                 */
                
                $user_obj = $_item->find("div#i_contact", 0)->find("div");
                
                $user_name = "";
                
                $user_company = "";
                
                foreach($user_obj as $id=>$value)
                {
                    if(trim($value->plaintext) == "Продавец" or trim($value->plaintext) == "Контактное лицо" or trim($value->plaintext) == "Арендодатель" or trim($value->plaintext) == "Покупатель")
                    {
                        $content_str = $user_obj[($id+1)];
                        if($content_str->find("strong", 0) != null)
                        {
                            
                            $user_name = trim($content_str->plaintext);
                        }
                        else
                        {
                            
                            $user_name = trim($content_str->plaintext);
                        }
                        
                    }
                    if(trim($value->plaintext) == "Агентство")
                    {
                        
                        $content_str = $user_obj[($id+1)];
                        $clear = explode("      ", trim($content_str->plaintext));
                        $user_company = trim($clear[0]);
                    }
                }
                
                if($user_name == "" and $user_company != "")
                {
                    $user_name = $user_company;
                }
                if($user_name != "" and $user_company == "")
                {
                    $user_company = $user_name;
                }
                
                $user = new Users();
                $user_entity = $em->getRepository("TorgovorotTorgBundle:Users")->findOneBy(array('fio'=>$user_name));
                if($user_entity != null)
                {
                    $user = $user_entity;
                }
                else
                {
                    $user->setCompanyName($user_company);
                    $user->setFio($user_name);
                    $user_login = uniqid();
                    $user->setUserName($user_login);
                    $user->setEmail($user_login."@email.test");
                    $user->setRegisterTime(new \DateTime("now"));
                    $em->persist($user);
                    $em->flush();
                }
                
                /*
                 * end user
                 */
                
                /*
                 * details
                 */
                
                $details = trim($_item->find("div.item-params",0)->plaintext);
                
                $details_array = explode(" ", $details);
                $details_array = array_filter ($details_array );
                //echo ($key+1)."|".$details."<br>";
                
                $rooms = 1;
                $square = 0.00;
                $ground_square = 0.00;
                $floor = 1;
                $stages = 1;
                $material_type = 1;
                $distance = 0;
                $house_type = 6;
                $garage_type = 1;
                $commType = 1;
                
                foreach($details_array as $key1=>$value1)
                {
                    $value1 = trim($value1);
                    
                    //$value1 = str_replace(array('(', ')'), '', $value1);
                    //rooms
                    //if(stripos("-", $value1) !== false)
                    //{
                        $rooms_array = explode("-", $value1);
                        
                        foreach($rooms_array as $key2=>$value2)
                        {
                            if($value2 == "к")
                            {
                                $rooms = $rooms_array[($key2-1)];
                            }
                            elseif($value2 == "этажного" | $value2 == "этажный")
                            {
                                $stages = $rooms_array[($key2-1)];
                            }
                        }
                        
                        
                        
                    //}
                    //square
                    if($value1 == "м²")
                    {
                        $square = $details_array[($key1-1)];
                    }
                    //floor
                    if($value1 == "этаже")
                    {
                        $floor = $details_array[($key1-1)];
                    }
                    
                    $mt_name = preg_replace ("/[^a-zа-я\s]/ui","",$value1);

                    $mt = $em->getRepository("TorgovorotTorgBundle:HouseMaterial")->findAll();
                    //print_r($mt);
                    //print_r($mt);
                    foreach($mt as $objt)
                    {
                        $name = mb_strtolower($objt->getName(),"utf-8");
                        
                        if(stripos($mt_name, $name) !== false || stripos($name, $mt_name) !== false)
                        {
                            $material_type = $objt->getId();
                        }
                        elseif(stripos($mt_name, "железобет") !== false)
                        {
                            $material_type = 11;
                        }
                    }
                    
                    //ground square
                    if($value1 == "сот." or $value1 == "сот.,")
                    {
                        $ground_square = $details_array[($key1-1)];
                    }
                    if($value1 == "до")
                    {
                        $distance = $details_array[($key1-2)];
                    }
                    
                    $ht = $em->getRepository("TorgovorotTorgBundle:HouseType")->findOneBy(array('name'=>ucfirst($value1)));
                    if($value1 == "дачу")
                    {
                        $ht = $em->getRepository("TorgovorotTorgBundle:HouseType")->findOneBy(array('name'=>"Дача"));
                    }
                    if($ht != null)
                    {
                        $house_type = $ht->getId();
                    }
                    
                    $gt = $em->getRepository("TorgovorotTorgBundle:GarageType")->findOneBy(array('name'=>ucfirst($value1)));
                    if($gt != null)
                    {
                        $garage_type = $gt->getId();
                    }
                    $off_types = $em->getRepository("TorgovorotTorgBundle:CommType")->findAll();
                    //echo "$value1<br>";
                    foreach($off_types as $type)
                    {
                        $name = mb_strtolower($type->getName(), "utf-8");
                        
                        if(stripos($name, $value1) !== false)
                        {
                            $commType = $type->getId();
                        }
                    }
                }
                
                /*
                 * images
                 */
                
                $images_id = "";
                
                $images_obj = $_item->find("div.fit");
                
                //if($images_obj != null)
                //{
                    //echo "ok";
                    foreach($images_obj as $each_div)
                    {
                        $img = $each_div->find("a", 0);
                    
                        $img_url = substr($img->href, 2);
                        //echo $key."-".$img_url."<br>";
                        sleep(10);
                        try{
                            file_get_contents("http://".$img_url);
                        
                            $file = new Document(null, 0, $em);
                            $id = $file->download("http://".$img_url);
                        }
                        catch(Exception $ex){
                            sleep(10);
                            continue;
                        }
                        
                        $images_id .= $id.";";
                    }
                    //echo "$images_id<br>";
                //}
                /*
                 * end images
                 */
                
                /*echo "
                
                Link: $_item_link<br>
                Наименование: $_item_title<br>
                Комнат: $rooms<br>
                Площадь: $square<br>
                Земля: $ground_square<br>
                Этаж: $floor<br>
                Этажность: $stages<br>
                Материал дома: $material_type<br>
                До города: $distance<br>
                Тип дома: $house_type<br>
                Тип коммерции: $commType<br>
                Тип гаража: $garage_type<br><br><br><br><br>
                ";*/
                
                $realty_obj = new AdsRealty();
                $realty_check = $em->getRepository("TorgovorotTorgBundle:AdsRealty")->findOneBy(array('title'=>$_item_title));
                if($realty_check != null)
                {
                    $realty_obj = $realty_check;
                }
                else
                {
                    
                
                $realty_obj->setTitle($_item_title);
                $ch_id = 0;
                if($realty_type == 1)
                {
                    $ch = new ChApartment();
                    $ch->setFloor($floor);
                    $ch->setFloorCount($stages);
                    $ch->setGeneralSquare($square);
                    $ch->setRooms($rooms);
                    $ch->setRtype(1);
                    $ch->setHouseType($material_type);
                    $em->persist($ch);
                    $em->flush();
                    $ch_id = $ch->getId();
                }
                elseif($realty_type == 2)
                {
                    $ch = new ChApartment();
                    $ch->setFloor($floor);
                    $ch->setFloorCount($stages);
                    $ch->setGeneralSquare($square);
                    $ch->setRooms($rooms);
                    $ch->setRtype(1);
                    $ch->setHouseType($material_type);
                    $em->persist($ch);
                    $em->flush();
                    $ch_id = $ch->getId();
                }
                elseif($realty_type == 3)
                {
                    $ch = new ChHouse();
                    $ch->setDistanceToCity($distance);
                    $ch->setHouseMaterial($material_type);
                    $ch->setHouseType($house_type);
                    $ch->setSquareEarth($ground_square);
                    $ch->setSquareHouse($square);
                    $em->persist($ch);
                    $em->flush();
                    $ch_id = $ch->getId();
                }
                elseif($realty_type == 4)
                {
                    $ch = new ChComm();
                    $ch->setCommType($commType);
                    $ch->setSquareEarth($ground_square);
                    $ch->setSquarePlace($square);
                    $em->persist($ch);
                    $em->flush();
                    $ch_id = $ch->getId();
                }
                elseif($realty_type == 5)
                {
                    $ch = new ChGarage();
                    $ch->setGarageType($garage_type);
                    //$em->persist($ch);
                    //$em->flush();
                    //$ch_id = $ch->getId();
                }
                
                $realty_obj->setAdsState(2);
                $realty_obj->setAdsType($realty_type);
                $realty_obj->setOtherType($buy_type);
                $realty_obj->setChId($ch_id);
                $realty_obj->setEmergency(0);
                $realty_obj->setPremium($prem);
                $realty_obj->setPrice($item_price['price']);
                $realty_obj->setDescription($item_description);
                $realty_obj->setRecommend(0);
                $realty_obj->setSpecial(0);
                $realty_obj->setTime(new \DateTime("now"));
                $realty_obj->setVip(0);
                $realty_obj->setAddrId($obj_addr->getId());
                
                
                $short_desc = "";
                $desc_arr = explode(" ", $item_description);
                foreach($desc_arr as $key=>$dc)
                {
                    if($key < 8)
                        $short_desc .= "$dc ";
                    elseif($key == 8)
                    {
                        $short_desc .= "...";
                        break;
                    }
                }
                
                $realty_obj->setShortDesc($short_desc);
                $realty_obj->setPhotoIds($images_id);
                //print_r($realty_obj);
                $em->persist($realty_obj);
                $em->flush();
                }
                /*
                 * end of details
                 */
            }
            //break;
        }
        //echo "</pre>";
        return null;
    }
    
    //parsing one item
    
        
    //helper function
    private function parsePageFromUrl($url)
    {
        $url_exp = explode("?", $url);
        
        $url_exp = explode("&", $url_exp[1]);
        
        $page = 1;
        
        for($i = 0; $i < count($url_exp); $i++)
        {
            $new_url_exp = explode("=", $url_exp[$i]);
            
            if($new_url_exp[0] == "p")
            {
                $page = $new_url_exp[1];
                break;
            }
        }
        
        return $page;
    }
    
    private function isPremium($class_name)
    {
        $isPremium = false;
        
        $class_exp = explode(" ", $class_name);
        
        foreach($class_exp as $class)
        {
            if($class == "premium")
            {
                $isPremium = true;
                break;
            }
        }
        
        return $isPremium;
    }
    
    private function getPrice($price_str)
    {
        $price = array(
            'type' => 'sell',
            'price' => 0
        );
        
        
        
        $expl = explode(" ", $price_str);
        
        foreach($expl as $onep)
        {
            if($onep == "руб.")
            {
                
            }
            elseif($onep == "месяц")
            {
                $price['type'] = 'rent';
                
            }
            elseif($onep == "Договорная")
            {
                $price['type'] = 'deal';
            }
            elseif($onep == "указана")
            {
                $price['type'] = 'empty';
            }
            else
            {
                //$onep = str_replace(" ", "", $onep);
                
                //$price .= preg_replace('~[^0-9]+~', '', $onep);
                //var_dump($onep)."<br>";
            }

        }
        
        $price['price'] = preg_replace('~[^0-9]+~', '', $price_str);
        //$price = trim($price);
        
        return $price;
        //return intval($price);
        //return intval($price);
    }
    
    private function setProperName($name)
    {
        $letter = strtoupper(substr($name, 0, 1));
        
        $name = $letter.substr($name, 1);
        
        return $name;
    }
    
    private function reconvertWord($word)
    {
        
    }
}
