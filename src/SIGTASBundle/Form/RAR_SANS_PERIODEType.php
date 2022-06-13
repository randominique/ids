<?php

namespace SIGTASBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RAR_SANS_PERIODEType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nIF')->add('tAXCENTRECODE')->add('rS')->add('tAXPERIODNO')->add('aNNEE')->add('mOIS')->add('nATURE')->add('tYPE')->add('mONTANT');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SIGTASBundle\Entity\RAR_SANS_PERIODE'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigtasbundle_rar_sans_periode';
    }


}
