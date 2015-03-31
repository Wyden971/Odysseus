<?php

namespace Odysseus\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Odysseus\AdminBundle\Form\ArticleModelType;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('brand')
            ->add('description')
            //->add('createdAt')
            //->add('modifiedAt')
            //->add('validatedAt')
            ->add('category')
            ->add('models', 'collection', array(
                'type' => new ArticleModelType(),
                'allow_add' => false,
                'by_reference' => true,
                'cascade_validation' => true,
                'prototype' => false,
                'options' => array(
                    'data_class' => 'Odysseus\AdminBundle\Entity\ArticleModel'
                )
            ))
            //->add('user')
            //->add('image')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Odysseus\AdminBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odysseus_adminbundle_article';
    }
}
