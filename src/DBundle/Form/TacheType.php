<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use DBundle\Entity\User;
use DBundle\Entity\TacheObjet;
use DBundle\Entity\TacheObservation;

class TacheType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultDate = new \Datetime('now');

        $builder
            ->add('nif', TextType::class, [
                'label' => 'N° d\'identification fiscale',
                'required' => false,
                'attr' => ['class' => 'form-control nif', 'style' => 'width:140px;text-align:center', 'autocomplete' => 'off'],
            ])
            ->add('rs', TextType::class, [
                'label' => 'Raison sociale',
                'required' => false,
                'attr' => ['class' => 'form-control rs', 'style' => 'width:500px', 'autocomplete' => 'off'],
            ])
            ->add('priority', ChoiceType::class, [
                'label' => 'Priorité',
                'placeholder' => 'Choisissez',
                'choices' => [
                    "Normal" => "Normal",
                    "Urgent" => "Urgent",
                    "Très Urgent" => "Très Urgent"
                ],
                'attr' => [
                    'style' => 'width:150px;text-align:left'
                ]
            ])
            // ->add('gestionnaire', TextType::class, [
            //     'label' => 'Gestionnaire',
            //     'required' => true,
                // 'attr' => ['class' => 'form-control rs', 'style' => 'width:500px', 'autocomplete' => 'off'],
            // ])
            // ->add('gestionnaire', EntityType::class, [
            //     'placeholder' => 'Choisissez',
            //     'class' => User::class,
            //     'query_builder' => function ($er) {
            //         return $er->createQueryBuilder('u')
            //             // ->where('u.service = ' . $er->getUser()->getService()->getId() . '')
            //             // ->where('u.service = '.$this->getAuteur()->getService()->getId().'')
            //             // ->where('u.service = '.$this->getService()->getId().'')
            //             ->orderBy('u.nom', 'ASC');
            //     },
            //     'choice_label' => function ($gestionnaire) {
            //         $name = $gestionnaire->getNom() . ' ' . $gestionnaire->getPrenom() . ' - ' . $gestionnaire->getCorps()/*.'('.$gestionnaire->getService()->getNom().')*/;
            //         return $name;
            //     },
            //     'multiple' => false,
            //     'expanded' => false,
            // ])
            ->add('object', EntityType::class, [
                'label' => 'Objet',
                'placeholder' => 'Choisissez',
                'class' => TacheObjet::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('tacheDescription', TextAreaType::class, [
                'label' => 'Description de tâche',
                'attr' => [
                    'style' => 'height:100px;margin:0px'
                ],
            ])
            ->add('observationContent', TextAreaType::class, [
                'label' => 'Observations',
                'attr' => [
                    'style' => 'height:100px;margin:0px;width:400px'
                ],
            ])
            ->add('deliveredDate', DateTimeType::class, [
                'label' => 'Date butoir',
                'widget' => 'single_text',
                'html5' => false,
                'required' => true,
                'attr' => ['class' => 'form-control js-datepicker', 'style' => 'width:140px', 'autocomplete' => 'off'],
            ])
            ->add('Sauvegarder', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'save btn btn-primary', 'style' => 'width:120px']
            ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\Tache'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_tache';
    }
}
