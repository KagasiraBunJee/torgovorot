<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AdsCarsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array(
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
            ->add('bodyId')
            ->add('runDistance', 'text', array(
                "label" => "Пробег"
            ))
            ->add('yearsOld')
            ->add('wincode', 'text', array(
                "label" => "Винкод"
            ))
            ->add('volume', 'text', array(
                "label" => "Объем"
            ))
            ->add('power', 'text', array(
                "label" => "Мощность"
            ))
            //->add('engineId')
            //->add('conditionId')
            ->add('color', 'text', array(
                "label" => "Цвет"
            ))
            //->add('photoIds')
            /*->add('chIds', 'entity', array(
                'class' => 'TorgovorotTorgBundle:Characteristics',
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQuery('SELECT DISTINCT u.name FROM TorgovorotTorgBundle:Characteristics u JOIN TorgovorotTorgBundle:CharacterBind b ON u.id = b.characterId where b.adsType = 7')->getResult();
                        },
                'property' => 'name'
            ))*/
            ->add('chIds', 'entity', array(
                'class' => 'TorgovorotTorgBundle:Characteristics',
                'query_builder' => function(EntityRepository $er) {
                        
                        $e = $er->createQueryBuilder("c")
                                  
                                  ->join('TorgovorotTorgBundle:CharacterBind', "b" , "WITH" , "b.characterId = c.id")
                                  ->join('TorgovorotTorgBundle:Labels', "l" , "WITH" , "l.id = b.labelId")
                                  ->where("b.adsType = :parent")
                                  ->setParameter("parent", 7);
                        return $e;
                        },
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => array('style' => 'float:left'),
                'label' => 'Комплектация',
                'group_by' => 'l.name'
            ))
            /*->add('chIds', 'choice', array(
                "choices" => array(null => "1"),
                'multiple' => true,
                'expanded' => true
            ))*/
            ->add('price', 'text', array(
                "label" => "Цена"
            ))
            ->add('torg', 'checkbox', array(
                "label" => "торг"
            ))
            ->add('isYourTown', 'choice', array(
                "label" => "Наличие в городе продажи",
                "choices" => array("1"=>"Да","2"=>"Нет")
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\AdsCars'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_adscars';
    }
}
