<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

use Torgovorot\TorgBundle\Entity\Cats;

class AdsGoodsType extends AbstractType
{
    
    
    private $em;
    
    public function __construct(EntityManager $em1, $mid = 0)
    {
        $this->em = $em1;
        //$this->mid = $mid;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $doctr = $this->em;
        
        $cat = $doctr->getRepository('TorgovorotTorgBundle:Cats')->findBy(array('type' => '3'));
            
            $cat_arr = array();
            
            foreach($cat as $cat_b)
            {
                
                $pod_arr = array();
                
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
                }
                
            }
        
        
        $builder
            //->add('ownerId')
            ->add('title','text',array(
                'label' => 'Название'
            ))
            ->add('text','textarea',array(
                'label' => 'Описание',
                'required' => false
            ))
            ->add('shortDesc','textarea',array(
                'label' => 'Краткое описание',
                'required' => false
            ))
            ->add('catId','choice', array(
                    'choices' => $cat_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'label' => 'Категории',
                    'required'    => false))
            //->add('type')
            ->add('days', 'choice', array(
                    'choices' => array('7' => '7', '14' => '14', '30' => '30'),
                    'expanded' => false,
                    'empty_value' => false,
                    'label' => 'Время',
                    'required'    => false))
            //->add('time')
            //->add('updateTime')
            ->add('photo', 'file', array(
                'mapped' => false,
                'required' => false
            ))
            ->add('price','text',array(
                'label' => 'Цена'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\AdsGoods'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_adsgoods';
    }
}
