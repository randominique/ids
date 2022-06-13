<?php

namespace DBundle\Form;

use Doctrine\ORM\Query\AST\Subselect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommunicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('createdAt')
            ->add('typecommunication', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Téléphone' => 'Téléphone',
                    'E-mail' => 'E-mail',
                    'Visite' => 'Visite',
                ],
                'attr' => [
                    'style' => 'width:150px;margin:0px'
                ],
            ])
            ->add('nif', TextType::class, [
                'label' => 'N° d\'identification fiscale',
                'required' => false,
                // 'disabled' => true,
                'attr' => [
                    'style' => 'width:180px;margin:0px;text-align:center'
                ],
            ])
            ->add('rs', TextType::class, [
                'label' => 'Raison sociale',
                'required' => false
            ])
            ->add('contact', TextType::class, [
                'label' => 'Contact',
                'required' => false
            ])
            ->add('interlocuteur', TextType::class, [
                'label' => 'Interlocuteur',
                'required' => false
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet de la communication',
                'attr' => [
                    'style' => 'height:150px;margin:0px'
                ],
            ])
            ->add('resolutions', TextType::class, [
                'label' => 'Résolutions',
                'attr' => [
                    'style' => 'height:150px;margin:0px'
                ],
            ]);
            // ->add('Enregistrer', SubmitType::class, [
            //     'label' => 'Enregistrer',
            //     'attr' => [
            //         'style' => 'width:120px;button:btn btn-primary'
            //     ]
            // ]);
        // ->add('utilisateur')
        // ->add('updatedAt')
        // ->add('updatedUser');
        // ->add('Sauvegarder', SubmitType::class, ['attr' => ['class' => 'save btn btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\Communication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_communication';
    }
}
