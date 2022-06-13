<?php

namespace SIGTASBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RAR_PERIODEType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fISCALNO')->add('tAXCENTRECODE')->add('nOM')->add('tAXPERIODNO')->add('tPERYEAR')->add('tPERMONTH')->add('nATURE')->add('tYPE')->add('mONTANT');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SIGTASBundle\Entity\RAR_PERIODE'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sigtasbundle_rar_periode';
    }


}
