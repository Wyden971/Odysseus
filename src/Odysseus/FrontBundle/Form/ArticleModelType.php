<?php

namespace Odysseus\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Odysseus\AdminBundle\Form\ImageType;

class ArticleModelType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', 'number')
            ->add('weight', 'number')
            ->add('width', 'number')
            ->add('height', 'number')
            ->add('depth', 'number')
            //->add('article')
            //->add('user')
            ->add('status')
            ->add('image', 'collection', array(
                'type' => new ImageType(),
                'allow_add' => true,
                'by_reference' => true,
                'cascade_validation' => true,
                'prototype' => true,
                'options' => array(
                    'data_class' => 'Odysseus\AdminBundle\Entity\Image'
                )
            ))
            //->add('cart')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Odysseus\AdminBundle\Entity\ArticleModel'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {

        return 'odysseus_adminbundle_articlemodel';

    }
}
