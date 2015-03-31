<?php

namespace Odysseus\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Odysseus\FrontBundle\Form\SellerInfosType;
use Odysseus\FrontBundle\Form\UserInfosType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('receiveSpecialOffers')
            ->add('receivePartnersSpecialOffers')
            //->add('sellerInfos', new SellerInfosType())
            ->add('birthdate', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy'
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => false
                ),
                'second_options' => array(
                    'label' => false
                ),
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ))
            ->add('infos', 'collection', array(
                'type' => new UserInfosType(),
                'allow_add' => false,
                'by_reference' => true,
                'cascade_validation' => true,
                'prototype' => false,
                'options' => array(
                    'data_class' => 'Odysseus\AdminBundle\Entity\UserInfos'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Odysseus\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odysseus_adminbundle_user';
    }
}
