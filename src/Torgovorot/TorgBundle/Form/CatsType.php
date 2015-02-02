<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
                'label' => 'Название',
                'required' => true
            ))
            ->add('pos','text',array(
                'label' => 'Позиция в топе',
                'required' => false
            ))
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\Cats'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_cats';
    }
}
