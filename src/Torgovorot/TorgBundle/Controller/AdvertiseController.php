<?php

namespace Torgovorot\TorgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Torgovorot\TorgBundle\Entity\Users;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Torgovorot\TorgBundle\Entity\Ads;
use Torgovorot\TorgBundle\Entity\AdvImages;
use Torgovorot\TorgBundle\Entity\Cats;
use Torgovorot\TorgBundle\Entity\CatBind;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\Address;
use Torgovorot\TorgBundle\Entity\ChApartment;
use Torgovorot\TorgBundle\Entity\ChHouse;
use Torgovorot\TorgBundle\Entity\ChComm;
use Torgovorot\TorgBundle\Entity\ChGarage;
use Torgovorot\TorgBundle\Entity\Videos;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsDiscounts;
use Torgovorot\TorgBundle\Entity\AdsCars;
use Torgovorot\TorgBundle\Helper\Uploader\Document;


class AdvertiseController extends Controller {
    
    
    public function advertAction($param = 0, Request $request)
    {
        
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('addAdvert',array('param' => $param)));
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        //if realty
        if($param == 1)
        {
           return $this->redirect($this->generateUrl('selectRealty')); 
        }
        //if vacancy
        else if($param == 2)
        {
        
            //$cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findAll();
        
            $cat_bind = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => $param));
        
            $cat_arr = array();
        
            foreach($cat_bind as $cat_b)
            {
                $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                $cat_arr[$cat->getId()] = $cat->getName();
            
            }
            
            $timetable_arr = array();
            
            $all_time = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Timetable')->findAll();
            
            foreach($all_time as $time)
            {
               $timetable_arr[$time->getId()] = $time->getName();  
            }
            //Образование
            $education = array(
                        0 => 'высшее',
                        1 => 'неполное высшее',
                        2 => 'среднее специальное',
                        3 => 'среднее',
                        4 => 'начальное',
                        5 => 'не важно');
            
            $sex = array(
                        0 => 'не важно',
                        1 => 'мужской',
                        2 => 'женский'
                    );
            
            $family = array(
                        0 => 'не важно',
                        1 => 'холост(а)',
                        2 => 'женат(замужем)'
                    );
            
            $form->add('title','text')
                 ->add('vacancyIds', 'choice', array(
                    'choices' => $cat_arr,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('timetableIds','choice', array(
                    'choices' => $timetable_arr,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('moneyFrom','text')
                 ->add('moneyTo','text')
                 ->add('education','choice', array(
                    'choices' => $education,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('experience','text')
                 ->add('sex','choice', array(
                    'choices' => $sex,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('family','choice', array(
                    'choices' => $family,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('requirement','textarea', array(
                     'required' => false
                 ))
                 ->add('save','submit')
                ;
            
        }
        //resume
        else if($param == 3)
        {
            $cat_bind = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => $param));
        
            $cat_arr = array();
        
            foreach($cat_bind as $cat_b)
            {
                $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
                
                if($cat != null)
                {
                    $cat_arr[$cat->getId()] = $cat->getName();
                }
                
            
            }
            
            $timetable_arr = array();
            
            $all_time = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Timetable')->findAll();
            
            foreach($all_time as $time)
            {
               $timetable_arr[$time->getId()] = $time->getName();  
            }
            //Образование
            $education = array(
                        0 => 'высшее',
                        1 => 'неполное высшее',
                        2 => 'среднее специальное',
                        3 => 'среднее',
                        4 => 'начальное',
                        5 => 'не важно');
            
            $sex = array(
                        0 => 'не важно',
                        1 => 'мужской',
                        2 => 'женский'
                    );
            
            $family = array(
                        0 => 'не важно',
                        1 => 'холост(а)',
                        2 => 'женат(замужем)'
                    );
            
            $driver = array(
                        0 => 'Нет',
                        1 => 'Да, имею права'
                    );
            
            $children = array(
                        0 => 'Нет',
                        1 => 'Есть'
                    );
            
            $form->add('fio','text')
                 ->add('position','text')
                 ->add('birthDate', 'date', array(
                    'data' => new \DateTime()))
                 ->add('sex','choice', array(
                    'choices' => $sex,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('family','choice', array(
                    'choices' => $family,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('driver','choice', array(
                    'choices' => $driver,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('children','choice', array(
                    'choices' => $children,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('jobsIds', 'choice', array(
                    'choices' => $cat_arr,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('timetable','choice', array(
                    'choices' => $timetable_arr,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('education','choice', array(
                    'choices' => $education,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('startStudy', 'date', array(
                    'data' => new \DateTime()))
                 ->add('endStudy', 'date', array(
                    'data' => new \DateTime()))
                 ->add('experience', 'choice', array(
                    'choices' => array('0' => 'Нет опыта / студент', '1' => 'Есть опыт'),
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('skills','textarea',array(
                    'required' => false
                 ))
                 ->add('aboutMe','textarea', array(
                     'required' => false
                 ))
                 ->add('contacts','textarea',array(
                     'required' => false
                 ))
                 ->add('save','submit')
                ;
        }
        //Other
        else if($param == 4)
        {

            $doctr = $this->getDoctrine();
            
            $cat = $doctr->getRepository('TorgovorotTorgBundle:Cats')->findBy(array('type' => '3'));
            
            $cat_arr = array();
            
            foreach($cat as $cat_b)
            {
                
                /*$pod_arr = array();
                
                $cat_class = new Cats();
                
                $cat_class = $cat_b;
                
                $cat_bind = $doctr->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => $cat_b->getId()));
                
                foreach($cat_bind as $pod)
                {
                   $pod_object = $doctr->getRepository('TorgovorotTorgBundle:Cats')->find($pod->getPodId());
                   
                   $pod_arr[$pod_object->getId()] =  $pod_object->getName();
                   
                }
                
                if(!empty($pod_arr))
                {
                    $cat_arr[$cat_b->getName()] = $pod_arr;
                }*/
                $cat_arr[$cat_b->getId()] = $cat_b->getName();
            }
            
            $good_sell_price = $doctr->getRepository('TorgovorotTorgBundle:GoodsSellTypes')->findAll();
            
            $sell_type = array();
            
            foreach($good_sell_price as $type)
            {
                $sell_type[$type->getId()] = $type->getName();
            }
            
            $form->add('title','text')
                 ->add('text','textarea', array(
                    'required' => false
                 ))
                 ->add('shortDesc','textarea',array(
                    'label' => 'Короткое описание',
                    'required' => false
                 ))
                 ->add('catId','choice', array(
                    'choices' => $cat_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                 /*->add('type','choice', array(
                    'choices' => $sell_type,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))*/
                 ->add('days', 'choice', array(
                    'choices' => array('7' => '7', '14' => '14', '30' => '30'),
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('price','text')
                    
                 ->add('photo','file', array(
                     'required'    => false
                 ))   
                    
                 ->add('save','submit')
                ;
        }
        elseif($param == 5)
        {
            $form->add('title','text',array(
                'label' => 'Название'))
            ->add('shortDescr','textarea',array(
                'label' => 'Короткое описание',
                'required' => false
            ))
            ->add('description','textarea',array(
                'label' => 'Полное описание',
                'required' => false
            ))
            ->add('ageRestrict','text',array(
                'label' => 'Возрастное ограничение',
                'required' => false
            ))
            ->add('eventTime','date',array(
                'label' => 'Время начала',
                'required' => true))
            ->add('endEvent','date',array(
                'label' => 'Время окончания',
                'required' => true))
            ->add('photo','file', array(
                         'required'    => false,
                         "attr" => array(
                            "accept" => "image/*",
                            "multiple" => "multiple",
                            )
                     ))
            ->add('else','textarea',array(
                'required' => false
            ))
            ->add('length','text',array(
                'label' => false))
            ->add('director','text',array(
                'label' => false))
            ->add('genre','text',array(
                'label' => false))
            ->add('save','submit');
        }
        elseif($param == 6)
        {
            $form->add('title','text',array(
                'label' => 'Название'))
            ->add('shortDescr','textarea',array(
                'label' => 'Короткое описание',
                'required' => false
            ))
            ->add('description','textarea',array(
                'label' => 'Полное описание',
                'required' => false
            ))
            ->add('discount','text',array(
                'label' => 'Скидка (%)',
                'required' => true))
            ->add('save','submit');
        }
        elseif($param == 8)
        {
            return $this->redirect($this->generateUrl('add_car'));
        }
        if($user == "anon.")
        {
            $form->add('email', 'text',array(
                    'mapped' => false
                 ))
                 ->add('phone', 'text',array(
                     'mapped' => false
                 ));
        }
        
        $form->add('index','text')
             ->add('street','text')
             ->add('house','text')
             ->add('flat','text');
        
        $form->getForm();        
        
        $get_form = $form->getForm();
        
        $get_form->handleRequest($request);
        
        if($get_form->isValid())
        {
           $data = $get_form->getData();
           
           
           if($user == "anon.")
            {
                if($get_form->get("email")->getData() != null)
                {
                    
                    $email = $get_form->get("email")->getData();
                    
                    $mobile = $get_form->get("phone")->getData() != null ? $get_form->get("phone")->getData() : "";
                    
                    $user = $em->getRepository("TorgovorotTorgBundle:Users")->findOneBy(array("email" => $email));
                    
                    if($user != null)
                    {
                        
                    }
                    else
                    {
                    $user_login = explode("@", $email);
                    
                    $pass = uniqid();
                    
                    $user = new Users();
            
                    $user->setAccessLvl(0);
                    $user->setRegisterTime(new \DateTime());
                    $user->setEmail($email);
                    $user->setUserName($user_login[0]);
                    $user->setPassNorm($pass);
                    $user->setSalt(md5(time()));
                    $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                    $password = $encoder->encodePassword($user->getPassNorm(), $user->getSalt());
                    $user->setMobile($mobile);
                    $user->setPassHex($password);  
                    $user->setFio("Аноним");
                    
                    
                    $em->persist($user);
                    $em->flush();
                    }
                }
            }
           
           foreach($data as $key=>$val) $$key = $val;
               
           
           if($param == 1)
           {
               
           }
           else if($param == 2)
           {
               $new_vac = new AdsVacance();
               /*echo "<pre>";
               print_r($data);
               echo "</pre>";*/
               
               $addr = new Address();
               $addr->setCity("Рыбинск");
               $addr->setIndexN($data['index']);
               $addr->setOffice($data['flat']);
               $addr->setHouse($data['house']);
               $addr->setStreet($data['street']);
               
               $em->persist($addr);
               $em->flush();
               
               $new_vac->setAddrId($addr->getId());
               $new_vac->setOwnerId($user->getId());
               $new_vac->setDays(7);
               $new_vac->setCreateTime(new \DateTime);
               $new_vac->setTitle($data['title']);
               $new_vac->setMoneyFrom($data['moneyFrom']);
               $new_vac->setMoneyTo($data['moneyTo']);
               $new_vac->setRequirement($data['requirement']);
               $new_vac->setFamily($data['family']);
               $new_vac->setSex($data['sex']);
               $new_vac->setEducation($data['education']);
               $new_vac->setExperience($data['experience']);
               
               if($data['vacancyIds'])
               {
                   $cats = "";
                   foreach($data['vacancyIds'] as $value)
                   {
                       $cats .= $value.";";
                   }
                   $new_vac->setVacancyIds($cats);
               }
               
               if($data['timetableIds'])
               {
                   $time_t = "";
                   foreach($data['timetableIds'] as $value)
                   {
                       $time_t .= $value.";";
                   }
                   $new_vac->setTimetableIds($time_t);
               }
               
               $em->persist($new_vac);
               $em->flush();
           }
           else if($param == 3)
           {
               $new_vac = new AdsResume();
               
               $addr = new Address();
               $addr->setCity("Рыбинск");
               $addr->setIndexN($data['index']);
               $addr->setOffice($data['flat']);
               $addr->setHouse($data['house']);
               $addr->setStreet($data['street']);
               
               $em->persist($addr);
               $em->flush();
               
               $new_vac->setAddrId($addr->getId());
               $new_vac->setOwnerId($user->getId());
               $new_vac->setDays(7);
               $new_vac->setCreateTime(new \DateTime);
               $new_vac->setFio($data['fio']);
               $new_vac->setStartStudy($data['study_from']);
               $new_vac->setEndStudy($data['study_to']);
               $new_vac->setSkills($data['skills']);
               $new_vac->setFamily($data['family']);
               $new_vac->setSex($data['gender']);
               $new_vac->setEducation($data['education']);
               $new_vac->setAboutMe($data['about']);
               $new_vac->setDriver($data['driver']);
               $new_vac->setBirthDate($data['birth']);
               $new_vac->setContacts($data['contact']);
               
               
               $isStudy = 0;
               /*if($data['is_study'])
               {
                   if($data['is_study'] == 1)
                   {
                        $isStudy = 1;
                   }
               }*/
               
               $new_vac->setIsStudy($isStudy);
               
               $new_vac->setPosition($data['prof']);
               
               $new_vac->setChildren($data['children']);
               
               $new_vac->setExperience($data['experience']);
               
               if($data['cats'])
               {
                   $cats = "";
                   foreach($data['cats'] as $value)
                   {
                       $cats .= $value.";";
                   }
                   $new_vac->setJobsIds($cats);
               }
               
               if($data['timetable'])
               {
                   $time_t = "";
                   foreach($data['timetable'] as $value)
                   {
                       $time_t .= $value.";";
                   }
                   $new_vac->setTimetable($time_t);
               }
               
               $em->persist($new_vac);
               $em->flush();               
           }
           else if($param == 4)
           {
               $this->saveGood($data);
           }
           else if($param == 5)
           {
               $item = new AdsEvents();
               
               $addr = new Address();
               $addr->setCity("Рыбинск");
               $addr->setIndexN($data['index']);
               $addr->setOffice($data['flat']);
               $addr->setHouse($data['house']);
               $addr->setStreet($data['street']);
               
               $em->persist($addr);
               $em->flush();
               
               $item->setAddrId($addr->getId());
               
               $item->setAgeRestrict($data['ageRestrict']);
               $item->setDescription($data['description']);
               $item->setEventTime($data['eventTime']);
               $item->setShortDescr($data['shortDescr']);
               $item->setTitle($data['title']);
               $item->setTime(new \DateTime());
               $item->setOwnerId($user->getId());
               $item->setAnything($data['else']);
               $item->setDirector($data['director']);
               $item->setLength($data['length']);
               $item->setGenre($data['genre']);
               $em->persist($item);
               $em->flush();
           }
           else if($param == 6)
           {
               $item = new AdsDiscounts();
               
               $addr = new Address();
               $addr->setCity("Рыбинск");
               $addr->setIndexN($data['index']);
               $addr->setOffice($data['flat']);
               $addr->setHouse($data['house']);
               $addr->setStreet($data['street']);
               
               $em->persist($addr);
               $em->flush();
               
               $item->setAddrId($addr->getId());
               
               $item->setDiscount($data['discount']);
               $item->setDescription($data['description']);
               $item->setShortDesc($data['shortDescr']);
               
               $item->setTitle($data['title']);
               $item->setTime(new \DateTime());
               $item->setOwnerId($user->getId());
               
               $em->persist($item);
               $em->flush();
           }
        }        
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
            'form' => $get_form->createView() , 'form_type' => $param, 'ads_type' => 'ready_form'
        ));        
    }
    
    public function selectAction(Request $request)
    {
        /*$user = $this->get('security.context')->getToken()->getUser();
        if($user == "anon.")
        {
            return $this->redirect($this->generateUrl('login'));
        }
        */
        $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('selectAdvert'))
            ->add('type','choice', array(
                    'choices' => array( 
                        '1' => 'Недвижимость',
                        '2' => 'Вакансии',
                        '3' => 'Резюме',
                        
                        '5' => 'Афиша',
                        '8' => 'Авто, Мото',
                        '4' => 'Прочее'
                ),
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        if($form->isValid())
        {
           $data = $form->getData();
           
           return $this->redirect($this->generateUrl('addAdvert', array('param' => $data['type']))); 
        }
        
        return $this->render('TorgovorotTorgBundle:Default:catalog.html.twig', array(
            'form' => $form->createView() ,
        ));
    }
    
    public function createAdvert()
    {
        
    }
      
    public function realtyAction($param = 0, Request $request = null)
    {
        
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('addRealty',array('param' => $param)));
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
            //Params
            //apartment realty type
            $rtype = array('0' => 'Продам','1' => 'Куплю','2' => 'Сдам','3' => 'Сниму');
            //rooms count
            $rooms = array('1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => 'больше',);
            //convinience
            $conv = array(
                '0' => 'Наличие балкона',
                '1' => 'Наличие холодильника',
                '2' => 'Наличие телевизора',
                '3' => 'Наличие телефона',
                '4' => 'Наличие кондиционера',
                '5' => 'Наличие посудомоечной машины',
                '6' => 'Наличие стиральной машины',
                '7' => 'Наличие нагревателя воды',
            );
            
            //bath type
            $bath_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findAll();
            
            $bath_arr = array();
            
            foreach($bath_type as $bath) $bath_arr[$bath->getId()] = $bath->getName();
            
            //house material
            $house_material = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseMaterial')->findAll();
            
            $mat_arr = array();
            
            foreach($house_material as $bath) $mat_arr[$bath->getId()] = $bath->getName();        
            
            //HouseType
            $house_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseType')->findAll();
            
            $htype = array();
            
            foreach($house_type as $htype1) $htype[$htype1->getId()] = $htype1->getName();  
            
            //House Planning
            $house_plan = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HousePlaning')->findAll();
            
            $ptype = array();
            
            foreach($house_plan as $ptype1) $ptype[$ptype1->getId()] = $ptype1->getName();            
        
            
            //converse in house
            $hconv = array(
                '0' => 'Наличие отопления',
                '1' => 'Наличие канализации',
                '2' => 'Наличии водопровода',
                '3' => 'Наличие электричества',
                '4' => 'Наличие газа'
            );
            
            //Commercial house type
            $commtype = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CommType')->findAll();
            
            $ctype = array();
            
            foreach($commtype as $ctype1) $ctype[$ctype1->getId()] = $ctype1->getName();              
            
            //Garage type
            $gartype = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:GarageType')->findAll();
            
            $gtype = array();
            
            foreach($gartype as $gtype1) $gtype[$gtype1->getId()] = $gtype1->getName();            
            
            //converse in house
            $gconv = array(
                '0' => 'Наличие отопления',
                '1' => 'Наличие электричества',
                '2' => 'Наличии водопровода',
                '3' => 'Наличие кессона',
                '4' => 'Наличие смотровой ямы'
            );
            
        if($param != 0)
        {
            $form->add('rtype','choice', array(
                    'choices' => $rtype,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('title','text')
                 ->add('price','text')
                 ->add('description','textarea',array(
                     "required" => false
                 ))
                 ->add('shortDesc','textarea',array(
                'label' => 'Короткое описание',
                'required' => false
                 ));            
        }
            
        if($param == 1 or $param == 2)
        {               
            //first line of form

            //Second line of form Table "ch_apartment"
              //rooms
                 $form->add('rooms','choice', array(
                    'choices' => $rooms,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
              //living square
                 ->add('living_square','text')
              //general square
                 ->add('general_square','text')
              //floor
                 ->add('floor','text')
              //floor count
                 ->add('floor_count','text')   
              //convinience
                 ->add('conv', 'choice', array(
                    'choices' => $conv,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
              //bath type
                 ->add('bath_type','choice', array(
                    'choices' => $bath_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
              //house material
                 ->add('house_mat','choice', array(
                    'choices' => $mat_arr,
                    'empty_value' => false,
                    'expanded' => false,
                    'required'    => false));
        }
        else if($param == 3)
        {
            $form->add('ttype','choice', array(
                    'choices' => $htype,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('plan','choice', array(
                    'choices' => $ptype,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('hsquare','text')
                 ->add('esquare','text')
                 ->add('distance','text')
                 ->add('conv', 'choice', array(
                    'choices' => $hconv,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false))
                 ->add('house_mat','choice', array(
                    'choices' => $mat_arr,
                    'empty_value' => false,
                    'expanded' => false,
                    'required'    => false));
        }
        else if($param == 4)
        {
            $form->add('type','choice', array(
                    'choices' => $ctype,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('hsquare','text')
                 ->add('esquare','text');
        }
        else if($param == 5)
        {
            $form->add('type','choice', array(
                    'choices' => $gtype,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('conv', 'choice', array(
                    'choices' => $gconv,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => false));
        }
        else
        {
            $form->add('rtype','choice', array(
                    'choices' => array(
                        '1' => 'Квартира',
                        '2' => 'Комната',
                        '3' => 'Дом/Участок',
                        '4' => 'Коммерческая недвижимость',
                        '5' => 'Гараж'
                    ),
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('save','submit')
                ;
        }
        if($param != 0)
        {
            $form->add('photo','file', array(
                     'required'    => false,
                     "attr" => array(
                        "accept" => "image/*",
                        "multiple" => "multiple"
                    )
                 ))
                ->add('video','textarea',array(
                     "required" => false
                 ))
                //->add('city','text')
                ->add('index','text')
                ->add('street','text')
                ->add('house','text')
                ->add('flat','text')
                /*->add('hints','textarea',array(
                     "required" => false
                 ))*/
                
                ->add('save','submit');
        }
        
        $form->getForm();        
        
        $get_form = $form->getForm();
        
        $get_form->handleRequest($request);
        
        if($get_form->isValid())
        {
           $data = $get_form->getData();
           
           if($param == 1)
           {
               
               $this->saveNewFlat($data,$param);
               
               
           }
           else if($param == 2)
           {
               $this->saveNewFlat($data,$param);
           }
           else if($param == 3)
           {
               $this->saveNewHouse($data,$param);
           }
           else if($param == 4)
           {
               $this->saveNewRealty($data,$param);
           }
           else if($param == 5)
           {
               
               $this->saveNewGarage($data,$param);
           }
           else
           {
               return $this->redirect($this->generateUrl('addRealty', array('param'=> $data['rtype'])));
           }
        
           
           
        }
        return $this->render('TorgovorotTorgBundle:Default:realty_add.html.twig', array(
                'form' => $get_form->createView() , 'form_type' => $param, 'ads_type' => 'realty'
           ));
    }
    
    public function saveNewFlat($array, $param = 0)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr->setCity("Рыбинск");
        //$addr->setFax("");
        //$addr->setPhone("");
        //$addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        //$addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        //adding characteristics
        $ch_a = new ChApartment();
        
        $ch_a->setRooms($array['rooms']);
        $ch_a->setLivingSquare($array['living_square']);
        $ch_a->setGeneralSquare($array['general_square']);
        $ch_a->setFloor($array['floor']);
        $ch_a->setFloorCount($array['floor_count']);
        $ch_a->setRtype(1);
        $conv_arr = $array['conv'];
            
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $ch_a->setBalcony(1);
                    break;
                case 1:
                    $ch_a->setRefregirator(1);
                    break;
                case 2:
                    $ch_a->setTv(1);
                    break;
                case 3:
                    $ch_a->setPhone(1);
                    break;
                case 4:
                    $ch_a->setRefregirator(1);
                    break;
                case 5:
                    $ch_a->setDishwasher(1);
                    break;
                case 6:
                    $ch_a->setWashingmachine(1);
                    break;
                case 7:
                    $ch_a->setBoiler(1);
                    break;
            }
        }
        $ch_a->setHouseType($array['house_mat']);
        $ch_a->setBathType($array['bath_type']);
        //saving video
        $video = new Videos;
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch_a->setVideoId($video_id);
        
        $em->persist($ch_a);
        $em->flush();
        $ch_id = $ch_a->getId();
        
        //finish adding flat ads
        
        $ads = new AdsRealty();
        
        $ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        $ads->setOtherType($array['rtype']);
        if(isset($array['photo']))
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();
    }
    
    public function saveNewHouse($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr->setCity("Рыбинск");
        //$addr->setFax("");
        //$addr->setPhone("");
        //$addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        //$addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        
        $char = new ChHouse();
        
        $char->setHouseMaterial($array['house_mat']);
        $char->setHouseType($array['ttype']);
        $char->setPlaning($array['plan']);
        $char->setSquareEarth($array['esquare']);
        $char->setSquareHouse($array['hsquare']);
        
        $conv_arr = $array['conv'];
        
        
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $char->setConvinient(1);
                    break;
                case 1:
                    $char->setCanalization(1);
                    break;
                case 2:
                    $char->setWater(1);
                    break;
                case 3:
                    $char->setElectricity(1);
                    break;
                case 4:
                    $char->setGas(1);
                    break;
            }
        }        
        
        
        //saving video
        $video = new Videos;
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $char->setVideoId($video_id);
        
        $em->persist($char);
        $em->flush();
        $char_id = $char->getId();        
        //finish adding flat ads
        
        $ads = new AdsRealty();
        
        $ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($char_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        $ads->setOtherType($array['rtype']);
        if(isset($array['photo']))
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();        
        
    }
    
    public function saveNewRealty($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr->setCity("Рыбинск");
        //$addr->setFax("");
        //$addr->setPhone("");
        //$addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        //$addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        
        
        $ch = new ChComm();
        
        $ch->setCommType($array['type']);
        $ch->setSquareEarth($array['esquare']);
        $ch->setSquarePlace($array['hsquare']);
        
        $em->persist($ch);
        $em->flush();        
        
        $ch_id = $ch->getId();
        //saving video
        $video = new Videos;
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch->setVideoId($video_id);
        
        $ads = new AdsRealty();
        
        $ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        $ads->setOtherType($array['rtype']);
        if(isset($array['photo']))
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();        
    }
    
    public function saveNewGarage($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr->setCity("Рыбинск");
        //$addr->setFax("");
        //$addr->setPhone("");
        //$addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        //$addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        $ch = new ChGarage();
        $ch->setGarageType($array['type']);
        
        $conv_arr = $array['conv'];
        
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $ch->setConvinient(1);
                    break;
                case 1:
                    $ch->setElectricity(1);
                    break;
                case 2:
                    $ch->setWater(1);
                    break;
                case 3:
                    $ch->setKesson(1);
                    break;
                case 4:
                    $ch->setObservationPit(1);
                    break;
            }
        }
        
        
        
        $em->persist($ch);
        $em->flush();        
        
        $ch_id = $ch->getId();
        
        //saving video
        $video = new Videos;
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch->setVideoId($video_id);
        
        $ads = new AdsRealty();
        
        $ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        $ads->setOtherType($array['rtype']);
        if(isset($array['photo']))
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        
        $em->persist($ads);
        $em->flush();
    }
    
    public function saveGood($array, $param = 0)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user != "anon.")
        {
            $user_id = $user->getId();
        
            foreach($array as $key => $value) $$key = $value;
        
            $good = new AdsGoods();
        
            $good->setCatId($cats);
            $good->setDays($days);
            $good->setOwnerId($user_id);
            $good->setText($descr);
            $good->setTime(new \DateTime("now"));
            $good->setTitle($title);
            $good->setType($type);
            $good->setPrice($price);
            //$ads->setOtherType($array['rtype']);
            if($array['photo'])
            {
                $good->setPhotoIds($this->savePhoto($array['photo']));
            }
            
            $addr = new Address();
            $addr->setCity("Рыбинск");
            $addr->setIndexN($data['index']);
            $addr->setOffice($data['flat']);
            $addr->setHouse($data['house']);
            $addr->setStreet($data['street']);
               
            $em->persist($addr);
            $em->flush();
               
            $good->setAddrId($addr->getId());
            
            $em->persist($good);
            $em->flush();
        }
        else
        {
            return $this->redirect($this->generateUrl('login'));
        }
    }
    
    private function savePhoto($file, $param = array())
    {
        $photo = new Document($file, 0, $this->getDoctrine()->getEntityManager());
        
        $param['type'] = "photo";
        
        return $photo->upload($param);
    }
    
    public function addEventAction(Request $request = null)
    {
        $item = new AdsEvents();

        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ( !$this->get('security.context')->isGranted('ROLE_USER')) { 
            return $this->redirect($this->generateUrl('login'));
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        
        //form
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'form_type' => '7'
           ));
    }
    
    
    
    public function addCarAdsAction($id = 0, Request $request = null)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $label = new AdsCars();
        
        if($id != 0 and $id > 0)
        {
            $label = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsCars")->find($id);
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $label_arr = array();
        
        $checks_arr = array();
        
        $labels = $em->getRepository("TorgovorotTorgBundle:Labels")->findAll();
        
        foreach ($labels as $label_one)
        {
            $label_arr[$label_one->getName()] = $em->createQuery("select c from TorgovorotTorgBundle:Characteristics as c JOIN TorgovorotTorgBundle:CharacterBind as b WITH b.characterId = c.id where b.adsType = 7 and b.labelId = ".$label_one->getId())
                 ->getResult();
        }
        
        $form = $this->createFormBuilder($label);
        $form->add('title','text',array(
                "label" => "Тип авто"
            ))
            //->add('catId')
            ->add('markId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:Cars',
                'property' => 'name',
                'label' => 'Марка'
            ))
            ->add('model', 'text', array(
                "label" => "Модель"
            ))
            ->add('yearProd', 'text', array(
                "label" => "Год выпуска"
            ))
            ->add('bodyId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:BodyType',
                'property' => 'name',
                'label' => 'Тип кузова'
            ))
            ->add('runDistance', 'text', array(
                "label" => "Пробег"
            ))
            ->add('yearsOld', 'choice', array(
                'label' => 'Срок владения',
                'choices' => array('0' => 'Меньше года', '1' => 'Больше года'),
                'required'    => false,
                'expanded' => true,
                'multiple' => false,
                'empty_value' => false
            ))
            ->add('wincode', 'text', array(
                "label" => "Винкод"
            ))
            ->add('volume', 'text', array(
                "label" => "Объем"
            ))
            ->add('power', 'text', array(
                "label" => "Мощность"
            ))
            ->add('engineId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:EngineType',
                'property' => 'name',
                'label' => 'Топливо'
            ))
            ->add('conditionId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:BodyCondition',
                'property' => 'name',
                'label' => 'Состояние'
            ))
            ->add('color', 'text', array(
                "label" => "Цвет"
            ))
            ->add('photo','file', array(
                "label" => "Фото",
                "mapped" => false,
                'required'    => false,
                "attr" => array(
                    "accept" => "image/*",
                    "multiple" => "multiple"
                )
            ))
            ->add('price', 'text', array(
                "label" => "Цена"
            ))
            ->add('torg', 'checkbox', array(
                "label" => "торг",
                "data" => $label->getTorg() == 1 ? true : false,
                "value" => 1
            ))
            ->add('isYourTown', 'checkbox', array(
                "label" => "Наличие в городе продажи",
                "value" => 1,
                "data" => $label->getIsYourTown() == 1 ? true : false
            ))
            ->add('town',"text", array(
                "label" => "Город продажи"
            ))
            ->add('description', 'textarea',array(
                "label" => "Текст объявления",
                "required" => false
            ))
            ->add('shortDesc','textarea', array(
                "label" => "Краткий текст обьявления",
                "required" => false
            ))
            ->add('save','submit', array(
                "label" => "Сохранить"
            ));
        
        if($user == "anon.")
        {
            $form->add('email', 'text', array(
                    'mapped' => false
                 ))
                 ->add('phone', 'text', array(
                    'mapped' => false
                 ))
            ;
        }
        
        $form = $form->getForm();

        $form->handleRequest($request);
        
        if($form->isValid())
        {
            
            
            
            if($user == "anon.")
            {
                if($form->get("email")->getData() != null)
                {
                    $email = $form->get("email")->getData();
                    
                    $mobile = $form->get("phone")->getData() != null ? $form->get("phone")->getData() : "";
                    
                    $user_login = explode("@", $email);
                    
                    $pass = uniqid();
                    
                    $user = new Users();
            
                    $user->setAccessLvl(0);
                    $user->setRegisterTime(new \DateTime());
                    $user->setEmail($email);
                    $user->setUserName($user_login[0]);
                    $user->setPassNorm($pass);
                    $user->setSalt(md5(time()));
                    $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                    $password = $encoder->encodePassword($user->getPassNorm(), $user->getSalt());
                    $user->setMobile($mobile);
                    $user->setPassHex($password);  
                    $user->setFio("Аноним");
                    
                    
                    $em->persist($user);
                    $em->flush();
                }
            }
            
            $data = $form->getData();
            
            $chs = $request->get("chid");
            
            foreach($data as $key => $val)
                $$key = $val;
            
            
            $ch_id = "";
            if($chs)
            {
                $ch_id = implode(";", $chs);
            }
            
            $data->setMarkId($data->getMarkId()->getId());
            $data->setBodyId($data->getBodyId()->getId());
            $data->setEngineId($data->getEngineId()->getId());
            $data->setConditionId($data->getConditionId()->getId());
            
            $files = $form->get("photo")->getData();
            
            $photos = "";
            
            foreach($files as $file)
            {
                if($file != null)
                    $photos .= $this->savePhoto($file, array("type"=>"photo")).";";
            }
            
            $data->setPhotoIds($photos);
                        
            $data->setVip(0)
                 ->setEmergency(0)
                 ->setPremium(0)
                 ->setTime(new \DateTime("now"))
                 ->setUpdated(new \DateTime("now"))
                 ->setSpecial(0)
                 ->setViews(0);
            
            $data->setOwnerId($user->getId());
            
            $data->setChIds($ch_id);

            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
            
            
        }
        
        

        $checked = $label->getChIds() != "" ? explode(";", $label->getChIds()) : array();
        
        return $this->render("TorgovorotTorgBundle:Default:add_car.html.twig", array(
                "form" => $form->createView(),
                "chs" => $label_arr,
                "checked" => $checked
            ));
    }
    
}
