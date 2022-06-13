<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RelanceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('type')
        ->add('nif')
        ->add('adresse')
        ->add('ville')
        ->add('impotsConcernes')
        ->add('periodeConcernes')
        ->add('objet')
        ->add('droitApplicable')
        // ->add('dateAccuse')
        // ->add('nomReceptionnaire')
        ->add('Sauvegarder', SubmitType::class, ['attr' => ['class' => 'save btn btn-primary']]);
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\Relance'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_relance';
    }


}
