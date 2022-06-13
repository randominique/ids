<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('raisonSocial')->add('nif')->add('priority')->add('status')->add('updatedAt')->add('createdAt')->add('objectId')->add('courrierId')->add('yearCourr')->add('titre')->add('objetCourrier')->add('numeroCourrier')->add('delegationDate')->add('traitementDate')->add('attribution')->add('commentaires')->add('auteur')->add('services')->add('gestionnaires');
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
