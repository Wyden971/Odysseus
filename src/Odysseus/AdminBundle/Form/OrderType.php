<?php

namespace Odysseus\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Odysseus\AdminBundle\Form\OrderDetailsType;

class OrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('createdAt')
            //->add('validatedAt')
            //->add('user')
            ->add('paymentMethod', 'entity', array(
                'class' => 'OdysseusAdminBundle:PaymentMethod',
                'expanded' => true,
            ))
            //->add('builling', new OrderDetailsType())
            ->add('shipping', new OrderDetailsType())
            ->add('shippingMethod', 'entity', array(
                'class' => 'OdysseusAdminBundle:ShippingMethod',
                'expanded' => true,
            ))
            //->add('status')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Odysseus\AdminBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'odysseus_adminbundle_order';
    }
}
