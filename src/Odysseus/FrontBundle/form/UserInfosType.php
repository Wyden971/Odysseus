<?php

namespace Odysseus\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserInfosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('address1')
            ->add('address2')
            ->add('zipCode')
            ->add('city')
            ->add('telephone')
            ->add('createdAt')
            ->add('modifiedAt')
            ->add('isDefault')
            ->add('isBuilling')
            ->add('country')
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Odysseus\AdminBundle\Entity\UserInfos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odysseus_adminbundle_userinfos';
    }
}
