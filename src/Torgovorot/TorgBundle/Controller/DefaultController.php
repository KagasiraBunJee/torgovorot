<?php

namespace Torgovorot\TorgBundle\Controller;

use Torgovorot\TorgBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Torgovorot\TorgBundle\Entity\Cats;
use Torgovorot\TorgBundle\Entity\CatBind;
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsVitrina;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsCars;
use Torgovorot\TorgBundle\Entity\Cars;
use Torgovorot\TorgBundle\Helper\Uploader\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Torgovorot\TorgBundle\Entity\SearchSeo;
//use Erivello\SimpleHtmlDomBundle\ErivelloSimpleHtmlDomBundle;
use Erivello\SimpleHtmlDomBundle;
use simple_html_dom;
use Torgovorot\TorgBundle\Helper\Parser\Parser;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $menu = $this->getMenuLink();
        $user = $this->get('security.context')->getToken()->getUser();
        //$doc = new Document(null, 0, $this->getDoctrine()->getManager());
        //$doc->resizeAll();

        return $this->render('TorgovorotTorgBundle:Default:index.html.twig', array('menu' => 'main'));
    }
    
    public function catsAction()
    {
        return $this->render('TorgovorotTorgBundle:Default:cats.html.twig');
    }
    
    
    
    private function getMenuLink()
    {
        $menu_str = "";
        $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Menu');
        $menu = $repository->findAll();
        
        return $menu;
    }
    
    private function realtyAction($cat_id = 0)
    {
        $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty');
        
    }
    
    public function resumeAction($cat_id = 0, Request $request = null)
    {

        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $resume = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsResume p
                    where p.position LIKE :name
                    or 
                    p.fio LIKE :name"
                )->setParameter('name', "%".$search_str."%");
            $resume = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(3);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume');
            $resume = $repository->findAll();
        }
        
        
        $cat = new Cats();
        $cat->setName("Общее");

        
        $all_cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findAll();
        $count = 0;
        
        foreach ($all_cats as $key => $values)
        {
            foreach($resume as $vacs)
            {
                $vacan = new AdsResume();
                $vacan = $vacs;
                $cats = $vacan->getJobsIds();
                $cats = explode(";", $cats);
                foreach($cats as $catty)
                {
                    if($values->getId() == $catty)
                    {
                        $count++;
                        break;
                    }
                }
            }
            if($count == 0)
            {
                unset($all_cats[$key]);
            }
            else
            {
                $values->setCount($count);                
            }
            $count = 0;
        }        
        
        
        if($cat_id != 0)
        {
            $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->find($cat_id);
        }
        
        //getting resumes from category
        $array = array();
        if($cat_id > 0)
        {
            foreach($resume as $res)
            {
                $resumes = new AdsResume();
                $resumes = $res;
            
                $explode = explode(";",$resumes->getJobsIds());
                foreach($explode as $value)
                {
                
                    if($value == $cat_id && $value != "")
                    {
                    
                        $array[] = $resumes;
                    }
                }
            
            }
        }
        else
        {
            $array = $resume;
        }
        /*
         * Page calculation
         * 
         */
        //pagination parameters
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        $o_count = count($array);
        $array = $this->getList($offset, $array, $limit);
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', 
                array(
                    'items' => $array,
                    'type' => 3,
                    'cat' => $cat,
                    'all_cats' => $all_cats,
                    'paginator' => $pagination,
                    'catid' => $cat_id,
                    'count' => $o_count,
                    'onpage' => $limit
                ));
    }
    
    public function vacanceAction($cat_id = 0, Request $request = null)
    {
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $vacanse = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsVacance p
                    where p.requirement LIKE :name
                    or 
                    p.title LIKE :name"
                )->setParameter('name', "%".$search_str."%");
            $vacanse = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(2);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance');
            $vacanse = $repository->findAll();
        }
        
        $short_desc = array();
        
        foreach($vacanse as $vacs_1)
        {
            $descr = $vacs_1->getRequirement();
            
            $new_descr_arr = explode(" ", $descr);
            
            $short = "";
            
            foreach($new_descr_arr as $key=>$dc)
            {
                if($key < 8)
                    $short .= addslashes($dc)." ";
                elseif($key == 8)
                {
                    $short .= "...";
                    break;
                }
            }
            $vacs_1->setShortDesc($short);
        }
        
        $all_cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findAll();
        $count = 0;
        
        foreach ($all_cats as $key => $values)
        {
            foreach($vacanse as $vacs)
            {
                $vacan = new AdsVacance();
                $vacan = $vacs;
                $cats = $vacan->getVacancyIds();
                $cats = explode(";", $cats);
                foreach($cats as $catty)
                {
                    if($values->getId() == $catty)
                    {
                        $count++;
                        break;
                    }
                }
            }
            if($count == 0)
            {
                unset($all_cats[$key]);
            }
            else
            {
                $values->setCount($count);                
            }
            $count = 0;
        }
        
        /*echo "<pre>";
        print_r($all_cats);
        echo "</pre>";*/
        
        $cat = new Cats();
        $cat->setName("Общее");
        if($cat_id != 0)
        {
            $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->find($cat_id);
        }
        //getting resumes from category
        $array = array();
        if($cat_id > 0)
        {
            foreach($vacanse as $vac)
            {
                $vacanses = new AdsVacance();
                $vacanses = $vac;
            
                $explode = explode(";",$vacanses->getVacancyIds());
                foreach($explode as $value)
                {
                
                    if($value == $cat_id)
                    {
                    
                        $array[] = $vacanses;
                    }
                }
            
            }
        }
        else $array = $vacanse;
        
        
        /*
         * Page calculation
         * 
         */
        //pagination parameters
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        $o_count = count($array);
        $array = $this->getList($offset, $array, $limit);        
        
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 2,
            'cat' => $cat,
            'all_cats' => $all_cats,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'count' => $o_count,
            'onpage' => $limit
        ));
    }
    
    public function uploadAction(Request $request)
    {
        $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('uploader'))
            ->add('type', 'file')
            ->add('save', 'submit')
            ->getForm();
        
        
        $form->handleRequest($request);
        if($form->isValid())
        {
           $data = $form->getData();
           
           $document = new Document($data['type']);
           
           $document->upload();
           
           //return $this->redirect($this->generateUrl('uploader')); 
        }
        
        return $this->render('TorgovorotTorgBundle:Default:uploader.html.twig', array(
            'form' => $form->createView() ,
        ));
    }
    
    private function getList($offset, $items, $limit)
    {
        $start = ($offset-1)*$limit;
        if($offset == 1)
        {
            $start = 0;
        }
        
        $end = $start + $limit;
        
        foreach($items as $key => $value)
        {
            if($key >= $start and $key < $end){}
                
            else unset($items[$key]);
        }
        
        return $items;
    }
    
    
    public function realtiesAction($cat_id = 0, Request $request = null)
    {
        //search string
        $search_str = $request->get('search_str') ? $request->get('search_str') : false; //current count on page
        //seach realty type
        $search_type = $request->get('search_type') ? $request->get('search_type') : false;
        //seach trade type
        $trade_type = $request->get('trade_type') ? $request->get('trade_type') : false;
        //price from
        $price_from = $request->get('price_from') ? $request->get('price_from') : false;
        //price to
        $price_to = $request->get('price_to') ? $request->get('price_to') : false;
        //sorting by time
        $time = $request->get('time') ? $request->get('time') : false;
        
        $realty = array();
        
        $sql_query = "";
        
        if($search_str)
        {
            $sql_query .= " p.description LIKE '%$search_str%' or p.title LIKE '%$search_str%' ";
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(1);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        if($search_type and $search_type > 0)
        {
            if($sql_query != "")
            {
                $sql_query .= " and";
            }
            $sql_query .= " p.adsType = $search_type";
        }
        if($trade_type and $trade_type > 0)
        {
            if($sql_query != "")
            {
                $sql_query .= " and";
            }
            $sql_query .= " p.otherType = $trade_type";
        }
        if($price_from)
        {
            if($sql_query != "")
            {
                $sql_query .= " and";
            }
            $sql_query .= " p.price >= $price_from";
        }
        if($price_to)
        {
            if($sql_query != "")
            {
                $sql_query .= " and";
            }
            if($price_to < $price_from)
            {
                $price_to = $price_from;
            }
            $sql_query .= " p.price <= $price_to";
        }
        
        
        //if($sql_query != "")
        //{
            if($sql_query != "")
            {
                $sql_query = "where ".$sql_query;
            }
            if($time)
            {
                $sql_query .= " ORDER BY p.time $time";
            }
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsRealty p
                    $sql_query"
                );
            $realty = $repository->getResult();
        //}
        //else
        //{
            //$repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty');
            //$realty = $repository->findAll();
        //}
        //Filter parameters for select
        $findBy = array();
        if($cat_id > 0)
        {
            $findBy['adsType'] = $cat_id;
        }
        $type = $request->get('type') ? $request->get('type') : 0;
        if($type > 0)
        {
                $findBy['otherType'] = $type;
        }
        if(!empty($findBy))
        {
            $realty = $repository->findBy($findBy);
        }
        
        //
        $all_cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:RealtyType')->findAll();
        $count = 0;
        
        foreach ($all_cats as $key => $values)
        {
            foreach($realty as $vacs)
            {
                $vacan = new AdsRealty();
                $vacan = $vacs;
                $cats = $vacan->getAdsType();
                $cats = explode(";", $cats);
                foreach($cats as $catty)
                {
                    if($values->getId() == $catty)
                    {
                        $count++;
                        break;
                    }
                }
            }
            if($count == 0)
            {
                unset($all_cats[$key]);
            }
            else
            {
                $values->setCount($count);                
            }
            $count = 0;
        }
        
        
        
        /*$cat = new Cats();
        $cat->setName("Общее");
        if($cat_id != 0)
        {
            $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:RealtyType')->find($cat_id);
        }
        //getting resumes from category
        $array = array();
        if($cat_id > 0)
        {
            foreach($realty as $vac)
            {
                $realties = new AdsRealty();
                $realties = $vac;
            
                $explode = explode(";",$realties->getCategoryId());
                foreach($explode as $value)
                {
                
                    if($value == $cat_id)
                    {
                    
                        $array[] = $realties;
                    }
                }
            
            }
        }
        else $array = $realty;*/
        $array = $realty;
        //overall count
        $o_count = count($realty);
        //pagination parameters
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit);        
        
        foreach($array as $arr_one)
        {
            
            $photo_url = "";
            if($arr_one->getPhotoIds() != "")
            {
                $photo_arr = explode(";", $arr_one->getPhotoIds());
                
                $photo_object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdvImages')->findOneBy(array('id' => $photo_arr[0]));
                
                if($photo_object != null)
                {
                    $photo_url = $photo_object->getIName();
                }
                
                
                
            }
            
            $arr_one->setPhotoUrl($photo_url);
            
        }
        /*echo "<pre>";
        print_r($array);
        echo "</pre>";*/
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 1,
//            'cat' => $cat,
            'all_cats' => $all_cats,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'onpage' => $limit,
            'atype' => $trade_type,
            'count' => $o_count
        ));        
        
    }
    
    public function goodAction($cat_id = 0, Request $request = null)
    {
        
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $event = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsGoods p
                    where p.text LIKE :name
                    or 
                    p.title LIKE :name"
                )->setParameter('name', "%".$search_str."%");
            $event = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(4);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods');
            $event = $repository->findAll();
        }

        
        $all_cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findAll();
        $count = 0;
        
        foreach ($all_cats as $key => $values)
        {
            foreach($event as $vacs)
            {
                $vacan = new AdsGoods();
                $vacan = $vacs;
                $cats = $vacan->getCatId();
                $cats = explode(";", $cats);
                foreach($cats as $catty)
                {
                    if($values->getId() == $catty)
                    {
                        $count++;
                        break;
                    }
                }
            }
            if($count == 0)
            {
                unset($all_cats[$key]);
            }
            else
            {
                $values->setCount($count);                
            }
            $count = 0;
        }
        
        /*echo "<pre>";
        print_r($all_cats);
        echo "</pre>";*/
        
        $cat = new Cats();
        $cat->setName("Общее");
        if($cat_id != 0)
        {
            $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->find($cat_id);
        }
        //getting resumes from category
        $array = array();
        if($cat_id > 0)
        {
            foreach($event as $vac)
            {
                $events = new AdsGoods();
                $events = $vac;
            
                $explode = explode(";",$events->getCatId());
                foreach($explode as $value)
                {
                
                    if($value == $cat_id)
                    {
                    
                        $array[] = $events;
                    }
                }
            
            }
        }
        else $array = $event;
        
        
        /*
         * Page calculation
         * 
         */
        //pagination parameters
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit);        
        
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 4,
            'cat' => $cat,
            'all_cats' => $all_cats,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'count' => $itemsCount,
            'onpage' => $limit
        ));
    }
    
    
    public function eventAction($cat_id = 0, Request $request = null)
    {
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $event = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsEvents p
                    where p.description LIKE :name
                    or 
                    p.title LIKE :name"
                )->setParameter('name', "%".$search_str."%");
            $event = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(5);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsEvents');
            $event = $repository->findAll();
        }

        $all_cats = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findAll();
        $count = 0;
        
        foreach ($all_cats as $key => $values)
        {
            foreach($event as $vacs)
            {
                $vacan = new AdsEvents();
                $vacan = $vacs;
                $cats = $vacan->getCategoryIds();
                $cats = explode(";", $cats);
                foreach($cats as $catty)
                {
                    if($values->getId() == $catty)
                    {
                        $count++;
                        break;
                    }
                }
            }
            if($count == 0)
            {
                unset($all_cats[$key]);
            }
            else
            {
                $values->setCount($count);                
            }
            $count = 0;
        }
        
        /*echo "<pre>";
        print_r($all_cats);
        echo "</pre>";*/
        
        $cat = new Cats();
        $cat->setName("Общее");
        if($cat_id != 0)
        {
            $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->find($cat_id);
        }
        //getting resumes from category
        $array = array();
        if($cat_id > 0)
        {
            foreach($event as $vac)
            {
                $events = new AdsEvents();
                $events = $vac;
            
                $explode = explode(";",$events->getCategoryIds());
                foreach($explode as $value)
                {
                
                    if($value == $cat_id)
                    {
                    
                        $array[] = $events;
                    }
                }
            
            }
        }
        else $array = $event;
        
        
        /*
         * Page calculation
         * 
         */
        //pagination parameters
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit);        
        
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 5,
            'cat' => $cat,
            'all_cats' => $all_cats,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'count' => $itemsCount,
            'onpage' => $limit
        ));
    }
    
    public function usersAction($cat_id = 0, Request $request = null)
    {
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $array = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Users p
                    where p.companyName LIKE :name
                    "
                )->setParameter('name', "%".$search_str."%");
            $array = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(6);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Users');
            $array = $repository->findAll();
        }

        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit);
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 6,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'count' => $itemsCount,
            'onpage' => $limit
        ));
    }
    
    public function discountsAction($cat_id = 0, Request $request = null)
    {
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $array = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsDiscounts p
                    where p.title LIKE :name
                    "
                )->setParameter('name', "%".$search_str."%");
            $array = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(7);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsDiscounts');
            $array = $repository->findAll();
        }

        /*foreach($array as $key => $object)
        {
            if($object->getDiscount() != "" || $object->getDiscount() > 0)
            {
                
            }
            else unset($array[$key]);
        }*/
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit);
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $array,
            'type' => 7,
            'paginator' => $pagination,
            'catid' => $cat_id,
            'count' => $itemsCount,
            'onpage' => $limit
        ));
    }
    
    public function pageAction($name = "")
    {
        $page = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Pages')->findOneBy(array('chpu' => $name));
        
        if($page == null)
        {
            $page = "empty";
        }
        
        return $this->render('TorgovorotTorgBundle:Default:page.html.twig', array(
            'item' => $page
        ));
    }
    
    public function workAction()
    {
        
        $vacance_count = 0;
        
        $resume_count = 0;
        
        $resume_count = count($this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->findAll());
        $vacance_count = count($this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->findAll());
        
        return $this->render('TorgovorotTorgBundle:Default:work.html.twig', array(
            'vac_count' => $vacance_count,
            'res_count' => $resume_count
        ));
    }
    
    public function katalogAction()
    {

        return $this->render('TorgovorotTorgBundle:Default:katalog.html.twig', array(

        ));
    }
    
    public function carsAction(Request $request = null)
    {
        $entities = array();
        
        $search_str = $request->get('search_str') ? $request->get('search_str') : ""; //current count on page
        
        $entities = array();
        
        if($search_str != "")
        {
            
            $repository = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:AdsCars p
                    where p.title LIKE :name
                    "
                )->setParameter('name', "%".$search_str."%");
            $entities = $repository->getResult();
            
            $seo = new SearchSeo();
            $seo->setSearchStr($search_str);
            $seo->setAdsType(8);
            $this->getDoctrine()->getManager()->persist($seo);
            $this->getDoctrine()->getManager()->flush();
        }
        else
        {
            $repository = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsCars');
            $entities = $repository->findAll();
        }

        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($entities); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $entities = $this->getList($offset, $entities, $limit);
        
        return $this->render('TorgovorotTorgBundle:Default:items.html.twig', array(
            'items' => $entities,
            'type' => 8,
            'paginator' => $pagination,
            'catid' => 0,
            'count' => $itemsCount,
            'onpage' => $limit
        ));
    }
    
    public function mapAction()
    {
        return $this->render('TorgovorotTorgBundle:Default:map.html.twig');
    }
}
