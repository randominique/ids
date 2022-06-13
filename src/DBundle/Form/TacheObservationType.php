<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TacheObservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('message')->add('createdAt')->add('user')->add('courrier');
        $builder
        ->add('message',TextType::class,[
            'label' => 'Observations',
            'required' => false,
        ])
        ->add('Enregistrer', SubmitType::class, ['attr' => ['class' => 'save btn btn-primary']]);
        // ->add('createdAt')
        // ->add('user')
        // ->add('courrier');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\TacheObservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_tacheobservation';
    }


}
