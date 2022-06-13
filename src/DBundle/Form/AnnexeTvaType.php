<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnexeTvaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nif', TextType::class, [
            'label' => 'NIF',
            'required' => true
        ])
        ->add('dateEnvoi', DateTimeType::class, [
            'label' => 'Date d\'envoi',
            'required' => true
        ])
        ->add('dateReponse', DateTimeType::class, [
            'label' => 'Date de réponse',
            'required' => false
        ])
        ->add('observation');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\AnnexeTva'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_annexetva';
    }


}
