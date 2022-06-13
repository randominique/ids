<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use DBundle\Entity\EntrantObjet;
use DBundle\Entity\EntrantObservation;

class EntrantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('priority', ChoiceType::class, [
            'label' => 'Priorité',
            'placeholder' => 'Choisissez', 
            'choices' => [
                "NORMAL" => "NORMAL",
                "URGENT" => "URGENT",
                "TRES URGENT" => "TRES URGENT"
            ]
        ])
        ->add('status', ChoiceType::class, [
            'label' => 'Etat',
            'placeholder' => 'Choisissez', 
            'choices' => [
                "Nouveau" => "Nouveau",
                "Ouvert" => "Ouvert",
                "En cours" => "En cours",
                "Traité" => "Traité",
                "Fermé" => "Fermé"
            ]
        ])/*
        ->add('object', EntityType::class, [
            'label'=> 'Objet',
            'placeholder' => 'Choisissez',  
            'class' => EntrantObjet::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
        ])
        */
        ->add('observation_content', TextareaType::class, [     
            'label' => "Observations"
        ])
        ->add('Sauvegarder', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => ['class' => 'save btn btn-primary']])
            
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\Entrant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_entrant';
    }


}
