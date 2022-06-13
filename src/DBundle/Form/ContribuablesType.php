<?php

namespace DBundle\Form;

use DBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContribuablesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('nif')
            ->add('nif',TextType::class, [
                'label' => 'NIF',
                'disabled' => true])
            // ->add('taxpayerNo')
            ->add('raisonSociale',TextType::class, [
                'label' => 'N° Identification Fiscale',
                'disabled' => true])
            // ->add('nomCommercial')
            ->add('adresse',TextType::class, [
                'label' => 'N° Identification Fiscale',
                'disabled' => true])
            // ->add('email')
            // ->add('telephone')
            // ->add('regimeFiscal')
            // ->add('secteurActivite')
            // ->add('nomDirigeant')
            // ->add('dateCreation')
            // ->add('dateArriveeDGE')
            // ->add('exerciceFiscalDebut')
            // ->add('exerciceFiscalFin')
            // ->add('gestionnaire')
            ->add('gestionnaire', EntityType::class, [
                'label' => 'Gestionnaire',
                'placeholder' => 'Choisissez',
                'class' => User::class,
                'choice_label' => function(User $user) {
                    // $user->createQueryBuiler('u')
                    // ->where('u.service = :service')
                    // ->setParameter('service', '3');
                    return $user->getNom() . ' ' . $user->getPrenom(); },
                'multiple' => false,
                'expanded' => false,
            ]);
            // ->add('gestionnaire', EntityType::class, [
            //     'label' => 'Gestionnaire',
            //     'placeholder' => 'Choisissez',
            //     'class' => User::class,
            //     'choice_label' => 'nom',
            //     'query_builder' => function($er) {
            //         return $er->createQueryBuilder('u')
            //         ->where('u.service = :service')
            //         ->setParameter('service', '3');
            //     },
            // ]);
            // ->add('inactifDate');
    }

    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\Contribuables'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_contribuables';
    }


}
