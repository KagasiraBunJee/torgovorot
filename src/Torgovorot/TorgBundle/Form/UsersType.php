<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsersType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fio','text',array(
                'label' => 'ФИО'
            ))
            ->add('mobile','text',array(
                'label' => 'Мобильный',
                'required' => false
            ))
            ->add('companyName','text',array(
                'label' => 'Название компании'
            ))
            ->add('userName','text',array(
                'label' => 'Логин',
                'required' => false
            ))
            ->add('email','text',array(
                'label' => 'E-Mail'
            ))
            ->add('about','textarea',array(
                'label' => 'О компании',
                'required' => false
            ))
            ->add('contacts','textarea',array(
                'label' => 'Контакты',
                'required' => false
            ))

            /*->add('tel','text',array(
                'label' => 'Тел.',
                'required' => false
            ))*/
            /*->add('fax','text',array(
                'label' => 'Факс',
                'required' => false
            ))*/
            /*->add('discounts','text',array(
                'label' => 'Скидки',
                'required' => false
            ))*/
            ->add("photo", "file", array(
                "mapped" => false,
                "required" => false,
                "label" => "Фото"
            ))
            ->add('save','submit',array(
                'label' => 'Обновить'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\Users'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_users';
    }
}
