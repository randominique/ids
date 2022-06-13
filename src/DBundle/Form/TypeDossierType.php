<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeDossierType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nif')
            ->add('nature')
            ->add('dateDepot')
            ->add('comments')
            ->add('createdBy')
            ->add('createdAt')
            ->add('status')
            ->add('Enregistrer', SubmitType::class, ['attr' => ['class' => 'save btn btn-primary']]);
            
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\TypeDossier'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_typedossier';
    }


}
